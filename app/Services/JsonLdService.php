<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Home;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class JsonLdService
{
    private const SCHEMA_CONTEXT = 'https://schema.org';

    /**
     * @param  array<string, mixed>  $viewData
     * @return array<string, mixed>|null
     */
    public static function resolve(Request $request, array $viewData = []): ?array
    {
        $routeName = $request->route()?->getName();

        if (self::shouldSkip($routeName)) {
            return null;
        }

        if (! empty($viewData['home']) && $viewData['home'] instanceof Home
            && is_string($routeName) && str_starts_with($routeName, 'main.homes.show')) {
            return self::wrapGraph(self::forHome($viewData['home']));
        }

        if (! empty($viewData['article']) && $viewData['article'] instanceof Article
            && $routeName === 'main.articles.show') {
            return self::wrapGraph(self::forArticle($viewData['article']));
        }

        if (! empty($viewData['landingPage']) && $viewData['landingPage'] instanceof LandingPage
            && $routeName === 'main.landing-pages.show'
            && ! empty($viewData['homes']) && $viewData['homes'] instanceof LengthAwarePaginator) {
            return self::wrapGraph(self::forLandingPage($viewData['landingPage'], $viewData['homes']));
        }

        if (! empty($viewData['homes']) && $viewData['homes'] instanceof LengthAwarePaginator
            && $routeName === 'main.homes.index') {
            return self::wrapGraph(self::forHomesIndex($viewData['homes'], $request, $viewData));
        }

        return match ($routeName) {
            'main.index' => self::wrapGraph(self::forIndex($viewData)),
            'main.login.form' => self::wrapGraph(self::forAuthPage(
                route('main.login.form'),
                setting('seo:login-title') ?: __('title.login'),
                setting('seo:login-meta-description') ?: 'ورود به حساب کاربری '.siteName()
            )),
            'main.register.form' => self::wrapGraph(self::forAuthPage(
                route('main.register.form'),
                setting('seo:register-title') ?: __('title.register'),
                setting('seo:register-meta-description') ?: 'ثبت نام در '.siteName()
            )),
            'main.submit.home' => self::wrapGraph(self::forAuthPage(
                route('main.submit.home'),
                setting('seo:submit-home-title') ?: setting('submit-home:page-title') ?: 'ثبت اقامتگاه',
                SeoService::truncate((string) (setting('seo:submit-home-meta-description') ?: setting('submit-home:first-description')))
            )),
            default => null,
        };
    }

    /**
     * @param  array<string, mixed>  $viewData
     * @return array<int, array<string, mixed>>
     */
    public static function forIndex(array $viewData): array
    {
        $siteUrl = url('/');
        $canonical = route('main.index');
        $title = indexPageTitleSegment();
        $description = indexPageSeoDescription();

        $graph = [
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl.'#website',
                'url' => $siteUrl,
                'name' => siteName(),
                'description' => $description,
                'inLanguage' => 'fa-IR',
                'publisher' => ['@id' => $siteUrl.'#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('main.homes.index').'?name={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            self::organization(),
            self::siteNavigation(),
            [
                '@type' => 'WebPage',
                '@id' => $canonical.'#webpage',
                'url' => $canonical,
                'name' => $title,
                'description' => $description,
                'inLanguage' => 'fa-IR',
                'isPartOf' => ['@id' => $siteUrl.'#website'],
            ],
        ];

        $featuredHomes = self::collectIndexFeaturedHomes($viewData);
        if ($featuredHomes->isNotEmpty()) {
            $graph = array_merge($graph, self::indexFeaturedHomeList($featuredHomes));
        }

        $comments = $viewData['comments'] ?? null;
        if ($comments instanceof \Illuminate\Support\Collection && $comments->isNotEmpty()) {
            foreach ($comments->take(6) as $comment) {
                $review = self::forIndexReview($comment);
                if ($review !== null) {
                    $graph[] = $review;
                }
            }
        }

        return $graph;
    }

    /**
     * @param  array<string, mixed>  $viewData
     * @return \Illuminate\Support\Collection<int, Home>
     */
    private static function collectIndexFeaturedHomes(array $viewData): \Illuminate\Support\Collection
    {
        $byId = collect();

        foreach (['popular_homes', 'cheap_homes', 'last_homes', 'expensive_homes'] as $key) {
            foreach ($viewData[$key] ?? [] as $home) {
                if ($home instanceof Home) {
                    $byId->put($home->id, $home);
                }
            }
        }

        return $byId->take(10)->values();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Home>  $homes
     * @return array<int, array<string, mixed>>
     */
    private static function indexFeaturedHomeList(\Illuminate\Support\Collection $homes): array
    {
        $listUrl = route('main.index').'#featured-homes';
        $items = [];
        $position = 0;

        foreach ($homes as $home) {
            $position++;
            $url = route('main.homes.show', $home);

            $items[] = self::homeListItem($home, $position);
        }

        return [
            [
                '@type' => 'ItemList',
                '@id' => $listUrl,
                'name' => 'اقامتگاه‌های پیشنهادی',
                'url' => route('main.index'),
                'numberOfItems' => $homes->count(),
                'itemListElement' => $items,
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function forIndexReview(mixed $comment): ?array
    {
        if (! is_object($comment) || empty($comment->comment)) {
            return null;
        }

        $authorName = trim((string) ($comment->full_name ?? optional($comment->user)->full_name ?? ''));
        if ($authorName === '') {
            return null;
        }

        $review = [
            '@type' => 'Review',
            'author' => [
                '@type' => 'Person',
                'name' => $authorName,
            ],
            'reviewBody' => SeoService::truncate(strip_tags((string) $comment->comment), 500),
            'inLanguage' => 'fa-IR',
        ];

        if (! empty($comment->commentable) && $comment->commentable instanceof Home) {
            $home = $comment->commentable;
            $review['itemReviewed'] = [
                '@type' => 'LodgingBusiness',
                'name' => $home->name,
                'url' => route('main.homes.show', $home),
            ];
        }

        return $review;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forHome(Home $home): array
    {
        $canonical = route('main.homes.show', $home);
        $description = self::homeDescription($home);

        $lodging = [
            '@type' => ['LodgingBusiness', 'VacationRental'],
            '@id' => $canonical.'#lodging',
            'name' => $home->name,
            'description' => $description,
            'url' => $canonical,
            'image' => self::homeImages($home),
            'identifier' => self::homeIdentifier($home),
            'containsPlace' => self::vacationRentalPlace($home),
            'address' => self::postalAddress($home),
            'offers' => self::lodgingOffer($home, $canonical),
            'inLanguage' => 'fa-IR',
        ];

        if ($home->latitude && $home->longitude) {
            $lodging['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $home->latitude,
                'longitude' => (float) $home->longitude,
            ];
        }

        $bedroomCount = $home->relationLoaded('sleepPlaces')
            ? $home->sleepPlaces->where('is_share', false)->count()
            : $home->sleepPlaces()->where('is_share', false)->count();

        if ($bedroomCount > 0) {
            $lodging['numberOfRooms'] = $bedroomCount;
        }

        $maxGuests = (int) $home->main_guest + (int) $home->extra_guest;
        if ($maxGuests > 0) {
            $lodging['occupancy'] = [
                '@type' => 'QuantitativeValue',
                'maxValue' => $maxGuests,
                'unitText' => 'نفر',
            ];
        }

        if ($home->hasGuestReviews()) {
            $lodging['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round((float) $home->score, 1),
                'reviewCount' => (int) $home->count_comments,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }

        $amenities = self::amenityFeatures($home);
        if ($amenities !== []) {
            $lodging['amenityFeature'] = $amenities;
        }

        $typeLabel = $home->typeLabel();
        if ($typeLabel !== '') {
            $lodging['additionalProperty'] = [
                '@type' => 'PropertyValue',
                'name' => 'نوع اقامتگاه',
                'value' => $typeLabel,
            ];
        }

        return [
            $lodging,
            self::breadcrumbList([
                ['name' => siteName(), 'item' => route('main.index')],
                ['name' => 'اقامتگاه‌ها', 'item' => route('main.homes.index')],
                ['name' => $home->name, 'item' => $canonical],
            ]),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forLandingPage(LandingPage $landingPage, LengthAwarePaginator $homes): array
    {
        $canonical = $landingPage->url;
        $list = self::buildHomeItemList($homes, $canonical.'#itemlist', $landingPage->title, $canonical);

        return array_merge(
            [
                [
                    '@type' => 'WebPage',
                    '@id' => $canonical.'#webpage',
                    'url' => $canonical,
                    'name' => $landingPage->title,
                    'description' => SeoService::truncate($landingPage->seoDescription(), 500),
                    'inLanguage' => 'fa-IR',
                    'isPartOf' => ['@id' => url('/').'#website'],
                ],
                self::breadcrumbList([
                    ['name' => siteName(), 'item' => route('main.index')],
                    ['name' => 'اقامتگاه‌ها', 'item' => route('main.homes.index')],
                    ['name' => $landingPage->title, 'item' => $canonical],
                ]),
            ],
            $list
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    /**
     * @param  array<string, mixed>  $viewData
     * @return array<int, array<string, mixed>>
     */
    public static function forHomesIndex(LengthAwarePaginator $homes, ?Request $request = null, array $viewData = []): array
    {
        $request = $request ?? request();
        $context = SeoService::homesIndexContext($request, array_merge($viewData, ['homes' => $homes]));
        $canonical = route('main.homes.index');
        $title = (string) ($context['title_segment'] ?? 'اجاره ویلا و سوئیت');
        $description = SeoService::truncate((string) ($context['description'] ?? ''), 500);
        $listName = (string) ($context['list_name'] ?? 'لیست اقامتگاه‌ها');

        $items = [];
        $position = ($homes->currentPage() - 1) * $homes->perPage();

        foreach ($homes as $home) {
            if (! $home instanceof Home) {
                continue;
            }

            $position++;
            $url = route('main.homes.show', $home);

            $items[] = self::homeListItem($home, $position);
        }

        $list = self::buildHomeItemList(
            $homes,
            $canonical.'#itemlist',
            $listName,
            $canonical,
            $items
        );

        return array_merge(
            [
                [
                    '@type' => 'WebPage',
                    '@id' => $canonical.'#webpage',
                    'url' => $canonical,
                    'name' => $title,
                    'description' => $description,
                    'inLanguage' => 'fa-IR',
                    'isPartOf' => ['@id' => url('/').'#website'],
                ],
                self::breadcrumbList([
                    ['name' => siteName(), 'item' => route('main.index')],
                    ['name' => 'اجاره ویلا و سوئیت', 'item' => $canonical],
                ]),
            ],
            $list
        );
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $prebuiltItems
     * @return array<int, array<string, mixed>>
     */
    private static function buildHomeItemList(
        LengthAwarePaginator $homes,
        string $listId,
        string $listName,
        string $listUrl,
        ?array $prebuiltItems = null
    ): array {
        $items = $prebuiltItems;

        if ($items === null) {
            $items = [];
            $position = ($homes->currentPage() - 1) * $homes->perPage();

            foreach ($homes as $home) {
                if (! $home instanceof Home) {
                    continue;
                }

                $position++;
                $url = route('main.homes.show', $home);

                $items[] = self::homeListItem($home, $position);
            }
        }

        if ($items === []) {
            return [self::organization()];
        }

        return [
            self::organization(),
            [
                '@type' => 'ItemList',
                '@id' => $listId,
                'name' => $listName,
                'url' => $listUrl,
                'numberOfItems' => $homes->total(),
                'itemListElement' => $items,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forArticle(Article $article): array
    {
        $canonical = $article->link;

        $posting = array_filter([
            '@type' => 'BlogPosting',
            '@id' => $canonical.'#article',
            'headline' => $article->title,
            'description' => SeoService::truncate($article->summary ?: strip_tags((string) $article->description), 500),
            'url' => $canonical,
            'image' => self::absoluteUrl($article->image_path),
            'datePublished' => optional($article->created_at)?->toIso8601String(),
            'dateModified' => optional($article->updated_at)?->toIso8601String(),
            'author' => optional($article->author)->full_name
                ? ['@type' => 'Person', 'name' => $article->author->full_name]
                : null,
            'publisher' => self::organization(),
            'inLanguage' => 'fa-IR',
        ], fn ($value) => $value !== null && $value !== '');

        return [
            $posting,
            self::breadcrumbList([
                ['name' => siteName(), 'item' => route('main.index')],
                ['name' => 'مقالات', 'item' => route('main.articles.index')],
                ['name' => $article->title, 'item' => $canonical],
            ]),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forWebsite(): array
    {
        $siteUrl = url('/');

        return [
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl.'#website',
                'url' => $siteUrl,
                'name' => siteName(),
                'description' => indexPageSeoDescription(),
                'inLanguage' => 'fa-IR',
                'publisher' => ['@id' => $siteUrl.'#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('main.homes.index').'?name={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            self::organization(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function forAuthPage(string $canonical, string $title, string $description): array
    {
        $siteUrl = url('/');

        return [
            self::organization(),
            [
                '@type' => 'WebPage',
                '@id' => $canonical.'#webpage',
                'url' => $canonical,
                'name' => $title,
                'description' => $description,
                'inLanguage' => 'fa-IR',
                'isPartOf' => ['@id' => $siteUrl.'#website'],
            ],
            self::breadcrumbList([
                ['name' => siteName(), 'item' => route('main.index')],
                ['name' => $title, 'item' => $canonical],
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function siteNavigation(): array
    {
        $siteUrl = url('/');
        $items = [];

        foreach (prioritySitelinks() as $index => $link) {
            $items[] = [
                '@type' => 'SiteNavigationElement',
                'position' => $index + 1,
                'name' => $link['name'],
                'url' => $link['url'],
            ];
        }

        return [
            '@type' => 'ItemList',
            '@id' => $siteUrl.'#priority-navigation',
            'name' => 'دسترسی سریع',
            'itemListElement' => $items,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $graph
     * @return array<string, mixed>
     */
    private static function wrapGraph(array $graph): array
    {
        $graph = array_values(array_filter($graph));

        return [
            '@context' => self::SCHEMA_CONTEXT,
            '@graph' => $graph,
        ];
    }

    /**
     * @param  array<int, array{name: string, item: string}>  $items
     * @return array<string, mixed>
     */
    private static function breadcrumbList(array $items): array
    {
        $elements = [];

        foreach ($items as $index => $item) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['item'],
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function organization(): array
    {
        $siteUrl = url('/');
        $organization = [
            '@type' => 'Organization',
            '@id' => $siteUrl.'#organization',
            'name' => siteName(),
            'url' => $siteUrl,
        ];

        $logo = settingFilePath('app:logo') ?: settingFaviconUrl(192);
        if ($logo) {
            $organization['logo'] = self::absoluteUrl($logo);
        }

        return $organization;
    }

    /**
     * @return array<string, mixed>
     */
    private static function lodgingOffer(Home $home, string $canonical): array
    {
        $price = max(0, $home->minBaseNightlyPrice());

        return [
            '@type' => 'Offer',
            '@id' => $canonical.'#offer',
            'url' => $canonical,
            'price' => $price,
            'priceCurrency' => 'IRR',
            'availability' => 'https://schema.org/InStock',
            'validFrom' => now()->toIso8601String(),
            'priceSpecification' => [
                '@type' => 'UnitPriceSpecification',
                'price' => $price,
                'priceCurrency' => 'IRR',
                'unitText' => 'شب',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function postalAddress(Home $home): array
    {
        return array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $home->address,
            'addressLocality' => $home->city->name ?? null,
            'addressRegion' => $home->province->name ?? null,
            'addressCountry' => 'IR',
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function amenityFeatures(Home $home): array
    {
        if (! $home->relationLoaded('options')) {
            return [];
        }

        return $home->options
            ->map(fn ($option) => [
                '@type' => 'LocationFeatureSpecification',
                'name' => $option->title,
                'value' => true,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function homeImages(Home $home): array
    {
        $images = collect($home->covers ?? [])
            ->map(fn ($path) => self::absoluteUrl((string) $path))
            ->filter()
            ->unique()
            ->take(10)
            ->values()
            ->all();

        if ($images !== []) {
            return $images;
        }

        $cover = self::absoluteUrl($home->cover_path);

        return $cover ? [$cover] : [];
    }

    /**
     * @return array<string, mixed>
     */
    private static function homeListItem(Home $home, int $position): array
    {
        $url = route('main.homes.show', $home);

        return [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => $url,
            'name' => $home->name,
        ];
    }

    private static function homeIdentifier(Home $home): string
    {
        return (string) ($home->code ?: $home->id);
    }

    /**
     * @return array<string, mixed>
     */
    private static function vacationRentalPlace(Home $home): array
    {
        $place = [
            '@type' => 'Accommodation',
            'name' => $home->name,
            'address' => self::postalAddress($home),
        ];

        if ($home->latitude && $home->longitude) {
            $place['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $home->latitude,
                'longitude' => (float) $home->longitude,
            ];
        }

        $bedroomCount = $home->relationLoaded('sleepPlaces')
            ? $home->sleepPlaces->where('is_share', false)->count()
            : $home->sleepPlaces()->where('is_share', false)->count();

        if ($bedroomCount > 0) {
            $place['numberOfRooms'] = $bedroomCount;
        }

        return $place;
    }

    private static function homeDescription(Home $home): string
    {
        $city = $home->city->name ?? '';
        $province = $home->province->name ?? '';
        $type = $home->typeLabel();

        $base = "اجاره {$type} {$home->name} در {$city}، {$province}.";

        if ($home->description) {
            return SeoService::truncate($base.' '.strip_tags((string) $home->description), 500);
        }

        return SeoService::truncate($base, 500);
    }

    private static function absoluteUrl(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return url($url);
    }

    private static function shouldSkip(?string $routeName): bool
    {
        if ($routeName === null) {
            return true;
        }

        if (str_starts_with($routeName, 'admin.') || str_starts_with($routeName, 'dashboard.')) {
            return true;
        }

        $noindexRoutes = [
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

        return in_array($routeName, $noindexRoutes, true);
    }
}
