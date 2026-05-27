<?php

namespace App\Services;

use App\Models\City;
use App\Models\Home;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HomeIndexSectionService
{
    public static function hasHomesForCategory(string $slug): bool
    {
        $query = Home::query()->active();

        return match ($slug) {
            'off' => (clone $query)->lastMinuteOffAvailable()->exists(),
            'open-tomorrow' => (clone $query)->openTomorrow()->exists(),
            'popular' => (clone $query)->exists(),
            'cheap' => (clone $query)->exists(),
            'expensive' => (clone $query)->exists(),
            'last' => (clone $query)->exists(),
            default => false,
        };
    }

    /**
     * @return list<array{slug: string, title: string, more_url: string}>
     */
    public static function suggestionCategories(): array
    {
        $categories = [
            ['slug' => 'off', 'title' => 'تخفیف'],
            ['slug' => 'popular', 'title' => 'محبوب'],
            ['slug' => 'cheap', 'title' => 'ارزان'],
            ['slug' => 'expensive', 'title' => 'گران'],
            ['slug' => 'last', 'title' => 'آخرین'],
            ['slug' => 'open-tomorrow', 'title' => 'فردا'],
        ];

        return array_values(array_filter(
            array_map(function (array $category) {
                $category['more_url'] = self::categoryListUrl($category['slug']);

                return $category;
            }, $categories),
            fn (array $category) => self::hasHomesForCategory($category['slug'])
        ));
    }

    public static function categoryListUrl(string $slug): string
    {
        return match ($slug) {
            'off' => route('main.homes.index', ['filter' => 'off']),
            'open-tomorrow' => route('main.homes.index', ['filter' => 'open_tomorrow']),
            'popular' => route('main.homes.index', ['sort' => 'popular']),
            'cheap' => route('main.homes.index', ['sort' => 'cheap']),
            'expensive' => route('main.homes.index', ['sort' => 'expensive']),
            'last' => route('main.homes.index', ['sort' => 'latest']),
            default => route('main.homes.index'),
        };
    }

    public static function homesForCategory(string $slug, int $limit = 6): Collection
    {
        $date = now()->startOfDay();

        $query = Home::query()
            ->without(['images'])
            ->active()
            ->withCount(['sleepPlaces as bedroom_count' => function ($query) {
                $query->where('is_share', false);
            }]);

        switch ($slug) {
            case 'off':
                $query->lastMinuteOffAvailable($date);
                break;
            case 'open-tomorrow':
                $date = now()->addDay()->startOfDay();
                $query->openTomorrow()->latest();
                break;
            case 'popular':
                $query->orderByDesc('fake_score');
                break;
            case 'cheap':
                $query->orderBy('week_price');
                break;
            case 'expensive':
                $query->orderByDesc('week_price');
                break;
            case 'last':
                $query->latest();
                break;
            default:
                return collect();
        }

        return $query
            ->limit($limit)
            ->get()
            ->map(fn (Home $home) => self::formatListingHome($home, $date));
    }

    public static function hasOpenTomorrowHomes(): bool
    {
        return self::hasHomesForCategory('open-tomorrow');
    }

    public static function hasOffHomes(): bool
    {
        return self::hasHomesForCategory('off');
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    public static function offCities(): array
    {
        $cityIds = self::offHomesQuery()
            ->whereNotNull('homes.city_id')
            ->distinct()
            ->pluck('homes.city_id');

        if ($cityIds->isEmpty()) {
            return [];
        }

        return City::query()
            ->whereIn('id', $cityIds)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (City $city) => ['id' => $city->id, 'name' => $city->name])
            ->values()
            ->all();
    }

    public static function offHomes(?int $cityId = null, int $limit = 10): Collection
    {
        $date = now()->startOfDay();
        $query = self::offHomesQuery($date)
            ->withCount(['sleepPlaces as bedroom_count' => function ($query) {
                $query->where('is_share', false);
            }]);

        if ($cityId) {
            $query->where('homes.city_id', $cityId);
        }

        return $query
            ->limit($limit)
            ->get()
            ->map(fn (Home $home) => self::formatListingHome($home, $date));
    }

    private static function offHomesQuery(?Carbon $date = null)
    {
        $date = $date ?? now()->startOfDay();

        return Home::query()
            ->without(['images'])
            ->active()
            ->lastMinuteOffAvailable($date);
    }

    /**
     * @return array<string, mixed>
     */
    public static function formatListingHome(Home $home, Carbon $date): array
    {
        $off_price = $home->getPrice($date);
        $price = $home->getPrice($date, true);
        $percent = $price > 0 ? round(100 - (($off_price * 100) / $price)) : 0;
        $maxGuests = (int) $home->main_guest + (int) $home->extra_guest;

        return [
            'id' => $home->id,
            'name' => $home->name,
            'cover' => $home->cover_path,
            'cover_path' => $home->cover_path,
            'city' => $home->city ? ['id' => $home->city->id, 'name' => $home->city->name] : null,
            'province' => $home->province ? ['id' => $home->province->id, 'name' => $home->province->name] : null,
            ...$home->guestRatingPayload(),
            'discount_percent' => $percent,
            'off_price' => $off_price,
            'original_price' => $price,
            'bedroom_count' => (int) ($home->bedroom_count ?? 0),
            'infrastructure_meter' => $home->infrastructure_meter,
            'main_guest' => $home->main_guest,
            'max_guests' => $maxGuests > 0 ? $maxGuests : (int) $home->main_guest,
            'type_label' => $home->typeLabel(),
            'is_favorite' => $home->isFavorite(),
        ];
    }
}
