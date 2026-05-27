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

        if (! empty($viewData['home']) && $viewData['home'] instanceof Home) {
            return self::wrapGraph(self::forHome($viewData['home']));
        }

        if (! empty($viewData['article']) && $viewData['article'] instanceof Article) {
            return self::wrapGraph(self::forArticle($viewData['article']));
        }

        if (! empty($viewData['landingPage']) && $viewData['landingPage'] instanceof LandingPage
            && ! empty($viewData['homes']) && $viewData['homes'] instanceof LengthAwarePaginator) {
            return self::wrapGraph(self::forLandingPage($viewData['landingPage'], $viewData['homes']));
        }

        if (! empty($viewData['homes']) && $viewData['homes'] instanceof LengthAwarePaginator) {
            return self::wrapGraph(self::forHomesIndex($viewData['homes']));
        }

        return match ($routeName) {
            'main.index' => self::wrapGraph(self::forWebsite()),
            default => null,
        };
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
                ['name' => config('app.name'), 'item' => route('main.index')],
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
                    ['name' => config('app.name'), 'item' => route('main.index')],
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
    public static function forHomesIndex(LengthAwarePaginator $homes): array
    {
        $items = [];
        $position = ($homes->currentPage() - 1) * $homes->perPage();

        foreach ($homes as $home) {
            if (! $home instanceof Home) {
                continue;
            }

            $position++;
            $url = route('main.homes.show', $home);

            $items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'url' => $url,
                'item' => [
                    '@type' => 'LodgingBusiness',
                    '@id' => $url.'#lodging',
                    'name' => $home->name,
                    'url' => $url,
                    'image' => self::absoluteUrl($home->cover_path),
                    'offers' => self::lodgingOffer($home, $url),
                ],
            ];
        }

        return self::buildHomeItemList(
            $homes,
            route('main.homes.index').'#itemlist',
            'لیست اقامتگاه‌ها',
            route('main.homes.index'),
            $items
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

                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'url' => $url,
                    'item' => [
                        '@type' => 'LodgingBusiness',
                        '@id' => $url.'#lodging',
                        'name' => $home->name,
                        'url' => $url,
                        'image' => self::absoluteUrl($home->cover_path),
                        'offers' => self::lodgingOffer($home, $url),
                    ],
                ];
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
                ['name' => config('app.name'), 'item' => route('main.index')],
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
                'name' => config('app.name'),
                'description' => SeoService::defaultDescription(),
                'inLanguage' => 'fa-IR',
                'publisher' => ['@id' => $siteUrl.'#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('main.homes.index').'?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            self::organization(),
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
            'name' => config('app.name'),
            'url' => $siteUrl,
        ];

        $logo = settingFilePath('app:logo');
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

        return in_array($routeName, $noindexRoutes, true);
    }
}
