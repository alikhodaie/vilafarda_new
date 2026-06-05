<?php

namespace App\Services;

use App\Models\Article;
use App\Models\City;
use App\Models\Home;
use App\Models\LandingPage;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SeoService
{
    public const DESCRIPTION_LIMIT = 150;

    /** حداکثر طول پیشنهادی تگ title برای نمایش در گوگل (کاراکتر) */
    public const TITLE_LIMIT = 60;

    /** @var string[] */
    private const NOINDEX_ROUTE_PREFIXES = [
        'dashboard.',
        'admin.',
    ];

    /** @var string[] */
    private const NOINDEX_ROUTE_NAMES = [
        'main.login',
        'main.login.temp.send.form',
        'main.login.temp.send',
        'main.login.temp.form',
        'main.login.temp',
        'main.register',
        'main.call-back',
        'main.add-to-home.ios',
        'main.add-to-home.android',
    ];

    /**
     * @param  array<string, mixed>  $viewData
     * @return array<string, mixed>
     */
    public static function resolve(Request $request, array $viewData = []): array
    {
        if (! empty($viewData['seo']) && is_array($viewData['seo'])) {
            return self::normalize($viewData['seo']);
        }

        $routeName = $request->route()?->getName();
        $viewTitle = trim((string) ($viewData['title'] ?? ''));

        if (self::shouldNoindexRoute($routeName)) {
            return self::normalize([
                'robots' => 'noindex, nofollow',
                'canonical' => self::canonicalUrl($request),
                'title_segment' => $viewTitle,
            ]);
        }

        if (! empty($viewData['home']) && $viewData['home'] instanceof Home
            && is_string($routeName) && str_starts_with($routeName, 'main.homes.show')) {
            return self::normalize(self::forHome($viewData['home']));
        }

        if (! empty($viewData['article']) && $viewData['article'] instanceof Article
            && $routeName === 'main.articles.show') {
            return self::normalize(self::forArticle($viewData['article']));
        }

        if (! empty($viewData['landingPage']) && $viewData['landingPage'] instanceof LandingPage
            && $routeName === 'main.landing-pages.show') {
            return self::normalize(self::forLandingPage($viewData['landingPage']));
        }

        if ($routeName === 'main.homes.index') {
            return self::normalize(self::forHomesIndexPage($request, $viewData));
        }

        return self::normalize(self::forRoute($routeName, $request, $viewTitle));
    }

    public static function homesIndexIsFiltered(Request $request): bool
    {
        if ((int) $request->get('page', 1) > 1) {
            return true;
        }

        $query = $request->query();
        unset($query['page']);

        return $query !== [];
    }

    /**
     * @param  array<string, mixed>  $viewData
     * @return array<string, mixed>
     */
    public static function homesIndexContext(Request $request, array $viewData = []): array
    {
        $defaultTitle = setting('seo:homes-title') ?: 'اجاره ویلا و سوئیت';
        $defaultDescription = (string) (setting('seo:homes-meta-description') ?: self::defaultDescription());
        $filtered = self::homesIndexIsFiltered($request);
        $total = ($viewData['homes'] ?? null) instanceof LengthAwarePaginator
            ? (int) $viewData['homes']->total()
            : 0;

        $location = self::homesIndexLocationLabel($request);
        $searchTerms = self::homesIndexSearchTerms($request);

        $h1 = $defaultTitle;
        $titleSegment = $defaultTitle;
        $listName = 'لیست اقامتگاه‌ها';
        $description = $defaultDescription;

        if ($location !== '') {
            $h1 = "اجاره ویلا و سوئیت در {$location}";
            $titleSegment = $h1;
            $listName = "اقامتگاه‌های {$location}";
            $description = self::homesIndexFilteredDescription($total, $location, $request);
        } elseif ($searchTerms !== []) {
            $termsLabel = implode('، ', $searchTerms);
            $h1 = 'اجاره ویلا و سوئیت';
            $titleSegment = "جستجوی {$termsLabel}";
            $listName = "نتایج جستجو برای {$termsLabel}";
            $description = self::homesIndexFilteredDescription($total, $termsLabel, $request);
        }

        return [
            'filtered' => $filtered,
            'h1' => $h1,
            'title_segment' => $titleSegment,
            'description' => $description,
            'list_name' => $listName,
            'location' => $location,
            'search_terms' => $searchTerms,
            'results_total' => $total,
        ];
    }

    /**
     * @param  array<string, mixed>  $viewData
     * @return array<string, mixed>
     */
    private static function forHomesIndexPage(Request $request, array $viewData): array
    {
        $context = self::homesIndexContext($request, $viewData);
        $canonical = route('main.homes.index');
        $ogImage = settingFilePath('seo:default-og-image') ?: settingFilePath('app:logo');
        $description = self::truncate((string) $context['description']);
        $titleSegment = trim((string) $context['title_segment']);

        if ($description === '') {
            $description = self::defaultDescription();
        }

        return [
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $context['filtered'] ? 'noindex, follow' : 'index, follow',
            'title_segment' => $titleSegment,
            'og' => [
                'title' => $titleSegment,
                'description' => $description,
                'image' => $ogImage,
                'url' => $canonical,
                'type' => 'website',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $titleSegment,
                'description' => $description,
                'image' => $ogImage,
            ],
        ];
    }

    private static function homesIndexLocationLabel(Request $request): string
    {
        if ($request->filled('city')) {
            $city = City::query()->find($request->get('city'));

            return trim((string) ($city?->name ?? ''));
        }

        if ($request->filled('province')) {
            $province = Province::query()->find($request->get('province'));

            return trim((string) ($province?->name ?? ''));
        }

        return '';
    }

    /**
     * @return list<string>
     */
    private static function homesIndexSearchTerms(Request $request): array
    {
        $terms = $request->get('q', []);
        if (! is_array($terms)) {
            $terms = $terms ? [(string) $terms] : [];
        }

        return array_values(array_unique(array_filter(array_map(
            fn ($term) => trim((string) $term),
            $terms
        ), fn ($term) => $term !== '')));
    }

    private static function homesIndexFilteredDescription(int $total, string $label, Request $request): string
    {
        $parts = [];

        if ($total > 0) {
            $parts[] = 'مشاهده و رزرو '.persianNumber($total).' ویلا و سوئیت';
        } else {
            $parts[] = 'جستجوی ویلا و سوئیت';
        }

        $parts[] = "در {$label}";

        if ($request->filled('start_at') && $request->filled('end_at')) {
            $parts[] = 'از '.$request->get('start_at').' تا '.$request->get('end_at');
        }

        $parts[] = 'با امکان فیلتر قیمت، تاریخ و امکانات.';

        return self::truncate(implode(' ', $parts));
    }

    /**
     * عنوان کامل تگ &lt;title&gt; با فرمت «بخش اصلی | نام برند».
     * اگر بخش اصلی از قبل نام برند را دارد، تکرار نمی‌شود (جلوگیری از vilafarda: در گوگل).
     */
    public static function documentTitle(string $segment): string
    {
        $segment = trim(preg_replace('/\s+/u', ' ', strip_tags($segment)));
        $brand = siteName();

        if ($segment === '') {
            return $brand !== '' ? $brand : '';
        }

        if ($brand === '' || self::segmentContainsBrand($segment, $brand)) {
            return self::limitTitleLength($segment);
        }

        $brandSuffix = ' | '.$brand;
        if (str_ends_with($segment, $brandSuffix) || $segment === $brand) {
            return self::limitTitleLength($segment);
        }

        $separator = ' | ';
        $suffix = $separator.$brand;
        $maxSegment = self::TITLE_LIMIT - mb_strlen($suffix);

        if ($maxSegment < 10) {
            return self::limitTitleLength($segment.$suffix);
        }

        if (mb_strlen($segment) > $maxSegment) {
            $segment = Str::limit($segment, $maxSegment, '…');
        }

        return $segment.$suffix;
    }

    public static function segmentContainsBrand(string $segment, ?string $brand = null): bool
    {
        $brand = trim((string) ($brand ?? siteName()));
        if ($brand === '') {
            return false;
        }

        $haystack = mb_strtolower($segment);
        $aliases = array_unique(array_filter([
            $brand,
            trim((string) config('app.name')),
            'ویلافردا',
            'ویلا فردا',
            'vilafarda',
            'villa farda',
        ]));

        foreach ($aliases as $alias) {
            if ($alias !== '' && str_contains($haystack, mb_strtolower($alias))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $seo
     * @return array<string, mixed>
     */
    public static function normalize(array $seo): array
    {
        if (! empty($seo['description'])) {
            $seo['description'] = self::truncate((string) $seo['description']);
        }

        if (! empty($seo['og']['description'])) {
            $seo['og']['description'] = Str::limit(strip_tags((string) $seo['og']['description']), 300);
        }

        if (empty($seo['document_title'])) {
            $segment = trim((string) ($seo['title_segment'] ?? $seo['og']['title'] ?? ''));
            if ($segment !== '') {
                $seo['document_title'] = self::documentTitle($segment);
            }
        }

        unset($seo['title_segment']);

        if (empty($seo['document_title'])) {
            $seo['document_title'] = siteName();
        }

        if (! empty($seo['og']) && empty($seo['og']['title']) && ! empty($seo['document_title'])) {
            $seo['og']['title'] = $seo['document_title'];
        }

        return $seo;
    }

    public static function truncate(?string $text, int $limit = self::DESCRIPTION_LIMIT): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)));

        return Str::limit($text, $limit, '…');
    }

    public static function canonicalUrl(Request $request, ?string $explicit = null): string
    {
        if ($explicit !== null && $explicit !== '') {
            return $explicit;
        }

        return url($request->path());
    }

    public static function defaultDescription(): string
    {
        return self::truncate((string) setting('seo:default-description', ''));
    }

    private static function limitTitleLength(string $text): string
    {
        return mb_strlen($text) > self::TITLE_LIMIT
            ? Str::limit($text, self::TITLE_LIMIT, '…')
            : $text;
    }

    public static function shouldNoindexRoute(?string $routeName): bool
    {
        if ($routeName === null) {
            return false;
        }

        if (in_array($routeName, self::NOINDEX_ROUTE_NAMES, true)) {
            return true;
        }

        foreach (self::NOINDEX_ROUTE_PREFIXES as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }

    public static function homeTitleSegment(Home $home): string
    {
        $city = trim((string) ($home->city->name ?? ''));
        $type = trim($home->typeLabel());
        $name = trim((string) $home->name);

        if ($type !== '' && $city !== '') {
            return "اجاره {$type} {$name} در {$city}";
        }

        if ($city !== '') {
            return trim("{$name} در {$city}");
        }

        return $name;
    }

    /**
     * @return array<string, mixed>
     */
    private static function forHome(Home $home): array
    {
        $titleSegment = self::homeTitleSegment($home);
        $city = $home->city->name ?? '';
        $province = $home->province->name ?? '';
        $type = $home->typeLabel();
        $description = self::truncate(
            $home->description
                ? "اجاره {$type} {$home->name} در {$city}، {$province}. ".strip_tags((string) $home->description)
                : "اجاره {$type} {$home->name} در {$city}، {$province}."
        );

        $canonical = route('main.homes.show', $home);
        $image = $home->cover_path;

        return [
            'description' => $description,
            'canonical' => $canonical,
            'robots' => 'index, follow',
            'title_segment' => $titleSegment,
            'og' => [
                'title' => $titleSegment,
                'description' => $home->description ?? $description,
                'image' => $image,
                'url' => $canonical,
                'type' => 'website',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $titleSegment,
                'description' => $home->description ?? $description,
                'image' => $image,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function forArticle(Article $article): array
    {
        $titleSegment = trim((string) $article->title);
        $description = self::truncate($article->summary ?: strip_tags((string) $article->description));
        $canonical = $article->link;
        $keywords = is_array($article->meta) ? $article->meta : [];

        return [
            'description' => $description,
            'canonical' => $canonical,
            'robots' => 'index, follow',
            'keywords' => $keywords,
            'author' => optional($article->author)->full_name,
            'title_segment' => $titleSegment,
            'og' => [
                'title' => $titleSegment,
                'description' => $description,
                'image' => $article->image_path,
                'url' => $canonical,
                'type' => 'article',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $titleSegment,
                'description' => $description,
                'image' => $article->image_path,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function forLandingPage(LandingPage $landingPage): array
    {
        $titleSegment = $landingPage->seoTitleSegment();
        $description = self::truncate($landingPage->seoDescription());
        $canonical = $landingPage->url;
        $ogImage = settingFilePath('seo:default-og-image') ?: settingFilePath('app:logo');

        return [
            'description' => $description,
            'canonical' => $canonical,
            'robots' => 'index, follow',
            'title_segment' => $titleSegment,
            'og' => [
                'title' => $titleSegment,
                'description' => $description,
                'image' => $ogImage,
                'url' => $canonical,
                'type' => 'website',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function forRoute(?string $routeName, Request $request, string $viewTitle = ''): array
    {
        $map = [
            'main.index' => [
                'title_segment' => indexPageTitleSegment(),
                'description' => setting('seo:index-meta-description') ?: setting('index:banner-description'),
                'canonical' => route('main.index'),
                'og_image' => indexPageOgImage(),
            ],
            'main.homes.index' => [
                'title_segment' => setting('seo:homes-title') ?: __('title.homes'),
                'description' => setting('seo:homes-meta-description'),
                'canonical' => route('main.homes.index'),
            ],
            'main.articles.index' => [
                'title_segment' => setting('seo:articles-title') ?: __('title.blog'),
                'description' => setting('seo:articles-meta-description'),
                'canonical' => route('main.articles.index'),
            ],
            'main.contact-us' => [
                'title_segment' => setting('seo:contact-title') ?: setting('contact-us:title'),
                'description' => setting('seo:contact-meta-description') ?: setting('contact-us:description1'),
                'canonical' => route('main.contact-us'),
            ],
            'main.about-us' => [
                'title_segment' => setting('seo:about-title') ?: setting('about-us:page-title'),
                'description' => setting('seo:about-meta-description') ?: setting('about-us:story-description'),
                'canonical' => route('main.about-us'),
            ],
            'main.privacy' => [
                'title_segment' => setting('seo:privacy-title') ?: setting('privacy:title'),
                'description' => setting('seo:privacy-meta-description') ?: setting('privacy:description1'),
                'canonical' => route('main.privacy'),
            ],
            'main.faq' => [
                'title_segment' => setting('seo:faq-title') ?: setting('faq:title'),
                'description' => setting('seo:faq-meta-description') ?: setting('faq:title'),
                'canonical' => route('main.faq'),
            ],
            'main.submit.home' => [
                'title_segment' => setting('seo:submit-home-title') ?: setting('submit-home:page-title'),
                'description' => setting('seo:submit-home-meta-description') ?: setting('submit-home:first-description'),
                'canonical' => route('main.submit.home'),
            ],
            'main.login.form' => [
                'title_segment' => setting('seo:login-title') ?: __('title.login'),
                'description' => setting('seo:login-meta-description') ?: 'ورود به حساب کاربری '.siteName(),
                'canonical' => route('main.login.form'),
            ],
            'main.register.form' => [
                'title_segment' => setting('seo:register-title') ?: __('title.register'),
                'description' => setting('seo:register-meta-description') ?: 'ثبت نام در '.siteName(),
                'canonical' => route('main.register.form'),
            ],
        ];

        $config = $map[$routeName] ?? [
            'description' => self::defaultDescription(),
            'canonical' => self::canonicalUrl($request),
            'title_segment' => $viewTitle,
        ];

        $ogImage = $config['og_image'] ?? settingFilePath('seo:default-og-image') ?: settingFilePath('app:logo');

        $titleSegment = trim((string) ($config['title_segment'] ?? ''));
        if ($titleSegment === '' && $viewTitle !== '') {
            $titleSegment = $viewTitle;
        }

        $description = self::truncate((string) ($config['description'] ?? ''));
        if ($description === '') {
            $description = self::defaultDescription();
        }

        return [
            'description' => $description,
            'canonical' => $config['canonical'] ?? self::canonicalUrl($request),
            'robots' => 'index, follow',
            'title_segment' => $titleSegment,
            'og' => [
                'title' => $titleSegment !== '' ? $titleSegment : null,
                'description' => $description,
                'image' => $ogImage,
                'url' => $config['canonical'] ?? self::canonicalUrl($request),
                'type' => 'website',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $titleSegment !== '' ? $titleSegment : null,
                'description' => $description,
                'image' => $ogImage,
            ],
        ];
    }
}
