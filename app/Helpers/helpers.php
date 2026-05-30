<?php

use App\Models\Home;
use App\Models\Menu\Menu;
use App\Models\Menu\MenuConst;
use App\Models\Navbar;
use App\Models\Setting;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

if (!function_exists('hasFilter')) {
    function hasFilter(): bool
    {
        return (bool)count(request()->except(['page']));
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($string): string
    {
        return preg_replace('/[\s?؟\/\\\\()&%#!،]+/u', '-', trim($string, ' \\/?.'));
    }
}

if (!function_exists('convertFAtoEN')){
    function convertFAtoEN($string): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}

if (!function_exists('navbar')){
    function navbar()
    {
        return cache()->rememberForever(Navbar::CACHE_KEY, function (){
            return Navbar::query()->parent()->orderBy('sort')->with(['children', 'children.children'])->get();
        });
    }
}

if (!function_exists('setting')){
    function setting(string $key, $default = null)
    {
        $setting = cache()->rememberForever(Setting::CACHE_KEY, function (){
            return Setting::all()->pluck('value', 'key');
        });

        return $setting[$key] ?? $default;
    }
}

if (!function_exists('settingFilePath')){
    function settingFilePath(string $setting_key): ?string
    {
        $filename = setting($setting_key);

        if (! $filename) {
            return null;
        }

        $url = Setting::getFilePath($filename);
        $absolute = public_path(Setting::FILE_PATH.ltrim($filename, '/'));

        if (is_file($absolute)) {
            return $url.'?v='.filemtime($absolute);
        }

        return $url;
    }
}

if (! function_exists('forgetSettingsCache')) {
    function forgetSettingsCache(): void
    {
        cache()->forget(Setting::CACHE_KEY);
    }
}

if (! function_exists('ini_size_to_bytes')) {
    /**
     * تبدیل مقدار ini مثل 256M یا 8M به بایت.
     */
    function ini_size_to_bytes($value): int
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '-1') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        switch ($unit) {
            case 'g':
                return (int) round($number * 1024 * 1024 * 1024);
            case 'm':
                return (int) round($number * 1024 * 1024);
            case 'k':
                return (int) round($number * 1024);
            default:
                return (int) round((float) $value);
        }
    }
}

if (! function_exists('index_banner_video_max_upload_bytes')) {
    /** سقف امن آپلود خام ویدئو بنر (بر اساس post_max_size سرور). */
    function index_banner_video_max_upload_bytes(): int
    {
        $postMax = ini_size_to_bytes(ini_get('post_max_size') ?: '8M');
        $cap = 30 * 1024 * 1024;

        if ($postMax <= 0) {
            return $cap;
        }

        return (int) min(floor($postMax * 0.85), $cap);
    }
}

if (! function_exists('footerSettings')) {
    function footerSettings(): array
    {
        $defaults = [
            'first_menu_title' => '',
            'first_menu' => [],
            'second_menu_title' => '',
            'second_menu' => [],
            'third_menu_title' => '',
            'third_menu' => [],
            'enamad_url' => '',
            'enamad_image_url' => '',
            'trust_section_title' => '',
            'phones' => [],
            'socials' => [],
            'mobile_nav' => [],
        ];

        $decoded = json_decode(setting('app:footer', '{}'), true);
        if (! is_array($decoded)) {
            return $defaults;
        }

        $merged = array_replace_recursive($defaults, $decoded);

        if (empty($merged['enamad_url']) && empty($merged['enamad_image_url'])) {
            $merged['enamad_url'] = 'https://trustseal.enamad.ir/?id=341631&Code=Qk98lTGBRYsxA6HLexcG';
            $merged['enamad_image_url'] = 'https://trustseal.enamad.ir/logo.aspx?id=341631&Code=Qk98lTGBRYsxA6HLexcG';
        }

        return $merged;
    }
}

if (! function_exists('footerSocialIconUrl')) {
    function footerSocialIconUrl(array $social): ?string
    {
        if (($social['icon_type'] ?? 'font') !== 'image' || empty($social['icon'])) {
            return null;
        }

        return Setting::rasterImageUrl($social['icon'], 'footer');
    }
}

if (! function_exists('footerNavbarLinks')) {
    function footerNavbarLinks(): array
    {
        $links = [];

        $collect = function ($items) use (&$collect, &$links) {
            foreach ($items as $item) {
                if (! empty($item->link)) {
                    $links[] = [
                        'title' => $item->title,
                        'link' => $item->link,
                    ];
                }

                if ($item->relationLoaded('children') && $item->children->isNotEmpty()) {
                    $collect($item->children);
                }
            }
        };

        $collect(navbar());

        return $links;
    }
}

if (! function_exists('footerSocialPlatform')) {
    function footerSocialPlatform(array $social): string
    {
        $haystack = mb_strtolower(($social['title'] ?? '').' '.($social['icon_class'] ?? ''));

        if (str_contains($haystack, 'telegram') || str_contains($haystack, 'تلگرام')) {
            return 'telegram';
        }

        if (str_contains($haystack, 'instagram') || str_contains($haystack, 'اینستا')) {
            return 'instagram';
        }

        return 'default';
    }
}

if (! function_exists('footerSocialIconClass')) {
    function footerSocialIconClass(array $social): string
    {
        $raw = trim((string) ($social['icon_class'] ?? ''));

        if ($raw !== '') {
            $raw = preg_replace('/\bbi\b/i', '', $raw);
            $raw = trim($raw);

            if ($raw !== '' && ! str_starts_with($raw, 'bi-')) {
                $raw = 'bi-'.$raw;
            }

            return $raw;
        }

        return match (footerSocialPlatform($social)) {
            'instagram' => 'bi-instagram',
            'telegram' => 'bi-telegram',
            default => 'bi-share',
        };
    }
}

if (! function_exists('footerTrustSectionTitle')) {
    function footerTrustSectionTitle(): string
    {
        $footer = footerSettings();
        $title = trim((string) ($footer['trust_section_title'] ?? ''));

        if ($title !== '') {
            return str_replace(':app_name', config('app.name'), $title);
        }

        return 'با خیال راحت به '.config('app.name').' اعتماد کنید';
    }
}

if (! function_exists('persianNumber')) {
    function persianNumber($number, int $decimals = 0): string
    {
        $formatted = number_format((float) $number, $decimals, '.', ',');

        return strtr($formatted, [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
            ',' => '،',
        ]);
    }
}

if (! function_exists('indexPageCities')) {
    /**
     * شهرهای صفحه اصلی از تنظیمات ادمین (index:cities) با URL تصویر.
     */
    function indexPageCities(): array
    {
        $defaultImage = 'https://www.mihmansho.com/mag/Files/Root/Content/Articlecc7bf0af-e472-436c-94f6-224af6ac1864_mihmansho.jpg';

        $cities = json_decode(setting('index:cities') ?? '[]', true) ?: [];
        $cities = array_values(is_array($cities) ? $cities : (array) $cities);
        $result = [];

        foreach ($cities as $item) {
            if (empty($item['province']['id']) || empty($item['city']['id'])) {
                continue;
            }

            $image = ! empty($item['image'])
                ? (Setting::rasterImageUrl($item['image'], 'cities') ?? $defaultImage)
                : $defaultImage;

            $provinceId = (int) $item['province']['id'];

            $result[] = [
                'province' => $item['province'],
                'city' => $item['city'],
                'count_homes' => (int) Home::query()->active()->where('province_id', $provinceId)->count(),
                'image' => $image,
            ];
        }

        return $result;
    }
}

if (! function_exists('indexPageSlider')) {
    /**
     * اسلایدهای بنر صفحه اصلی از تنظیمات ادمین (index:slider).
     */
    function indexPageSlider(): array
    {
        $slides = json_decode(setting('index:slider') ?? '[]', true) ?: [];
        $slides = array_values(is_array($slides) ? $slides : (array) $slides);
        $result = [];

        foreach ($slides as $item) {
            if (empty($item['image'])) {
                continue;
            }

            $image = Setting::rasterImageUrl($item['image'], 'slider');
            if ($image === null) {
                continue;
            }

            $link = trim((string) ($item['link'] ?? ''));
            $result[] = [
                'link' => $link !== '' ? $link : '#',
                'image' => $image,
                'alt' => trim((string) ($item['alt'] ?? '')),
            ];
        }

        return $result;
    }
}

if (! function_exists('indexBannerType')) {
    function indexBannerType(): string
    {
        $type = setting('index:banner-type');

        return in_array($type, ['slider', 'video'], true) ? $type : 'video';
    }
}

if (!function_exists('imageBase64')){
    function imageBase64($path): string
    {
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path, false, stream_context_create($stream_opts));

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}

if (!function_exists('hidden')){
    function hidden(string $string): string
    {
        return preg_replace("/(?!^).(?!$)/", "*", $string);
    }
}

if (!function_exists('persianDate')){
    function persianDate(Carbon $date): Jalalian
    {
        return Jalalian::forge($date);
    }
}

if (! function_exists('public_asset_version')) {
    /**
     * آدرس فایل استاتیک با نسخهٔ filemtime برای bust کردن کش مرورگر.
     */
    function public_asset_version(string $path): string
    {
        $relative = ltrim($path, '/');
        $full = public_path($relative);
        $version = is_file($full) ? (string) filemtime($full) : (string) time();

        return asset($relative).'?v='.$version;
    }
}

if (! function_exists('homeImageAlt')) {
    /**
     * متن alt معنادار برای تصاویر اقامتگاه در سایت عمومی.
     *
     * @param  \App\Models\Home|object|string  $home
     * @param  string|null  $suffix
     * @param  \App\Models\HomeImage|null  $image
     */
    function homeImageAlt($home, ?string $suffix = null, $image = null): string
    {
        if ($image instanceof \App\Models\HomeImage) {
            $imageAlt = trim((string) ($image->alt_text ?? ''));
            if ($imageAlt !== '') {
                return $imageAlt;
            }
        }

        if (is_object($home)) {
            $coverAlt = trim((string) ($home->cover_alt ?? ''));
            if ($coverAlt !== '') {
                return $coverAlt;
            }
        }

        $name = is_object($home) ? trim((string) ($home->name ?? '')) : trim((string) $home);
        if ($name === '') {
            $name = 'اقامتگاه';
        }

        $alt = 'تصویر '.$name;
        if ($suffix !== null && $suffix !== '') {
            $alt .= ' — '.$suffix;
        }

        return $alt;
    }
}

if (! function_exists('homeImageAltDefault')) {
    /**
     * پیش‌نمایش alt پیش‌فرض (وقتی فیلد اختصاصی خالی است).
     */
    function homeImageAltDefault($home): string
    {
        $name = is_object($home) ? trim((string) ($home->name ?? '')) : trim((string) $home);

        return 'تصویر '.($name !== '' ? $name : 'اقامتگاه');
    }
}

if (! function_exists('sliderSlideAlt')) {
    /**
     * متن alt اسلاید بنر صفحه اصلی (از فیلد ادمین یا عنوان بنر).
     */
    function sliderSlideAlt(array $slide, int $index = 0): string
    {
        $custom = trim((string) ($slide['alt'] ?? ''));
        if ($custom !== '') {
            return $custom;
        }

        $banner = trim((string) setting('index:banner-title'));
        if ($banner !== '') {
            return $index === 0 ? $banner : $banner.' — اسلاید '.($index + 1);
        }

        $brand = (string) config('app.name');

        return $index === 0 ? 'بنر '.$brand : 'بنر '.$brand.' — اسلاید '.($index + 1);
    }
}
