<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Home;
use App\Models\LandingPage;
use Illuminate\Http\Request;
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
        'main.login.form',
        'main.login',
        'main.login.temp.send.form',
        'main.login.temp.send',
        'main.login.temp.form',
        'main.login.temp',
        'main.register.form',
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

        if (! empty($viewData['home']) && $viewData['home'] instanceof Home) {
            return self::normalize(self::forHome($viewData['home']));
        }

        if (! empty($viewData['article']) && $viewData['article'] instanceof Article) {
            return self::normalize(self::forArticle($viewData['article']));
        }

        if (! empty($viewData['landingPage']) && $viewData['landingPage'] instanceof LandingPage) {
            return self::normalize(self::forLandingPage($viewData['landingPage']));
        }

        return self::normalize(self::forRoute($routeName, $request, $viewTitle));
    }

    /**
     * عنوان کامل تگ &lt;title&gt; با فرمت «بخش اصلی | نام برند».
     */
    public static function documentTitle(string $segment): string
    {
        $segment = trim(preg_replace('/\s+/u', ' ', strip_tags($segment)));
        $brand = trim((string) config('app.name'));

        if ($segment === '') {
            return $brand !== '' ? $brand : '';
        }

        if ($brand === '') {
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
            $seo['document_title'] = trim((string) config('app.name'));
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
                'title_segment' => setting('seo:index-title') ?: setting('index:page-title'),
                'description' => setting('seo:index-meta-description') ?: setting('index:banner-description'),
                'canonical' => route('main.index'),
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
        ];

        $config = $map[$routeName] ?? [
            'description' => self::defaultDescription(),
            'canonical' => self::canonicalUrl($request),
            'title_segment' => $viewTitle,
        ];

        $titleSegment = trim((string) ($config['title_segment'] ?? ''));
        if ($titleSegment === '' && $viewTitle !== '') {
            $titleSegment = $viewTitle;
        }

        $description = self::truncate((string) ($config['description'] ?? ''));
        if ($description === '') {
            $description = self::defaultDescription();
        }

        $ogImage = settingFilePath('seo:default-og-image') ?: settingFilePath('app:logo');

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
        ];
    }
}
