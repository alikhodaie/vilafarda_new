<?php

namespace App\Services;

use App\Models\Home;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HomeSimilarHomesService
{
    public const LIMIT = 8;

    private const PRICE_TOLERANCE = 0.35;

    /**
     * @return array{categories: list<array{slug: string, title: string, more_url: string}>, homes: array<string, list<array<string, mixed>>>}
     */
    public function groupsFor(Home $home): array
    {
        $home->loadMissing(['options', 'province', 'city']);

        $categories = [];
        $homes = [];

        $locationHomes = $this->byLocation($home);
        if ($locationHomes->isNotEmpty()) {
            $categories[] = [
                'slug' => 'location',
                'title' => 'موقعیت',
                'more_url' => $this->locationMoreUrl($home),
            ];
            $homes['location'] = $locationHomes->values()->all();
        }

        $priceHomes = $this->byPrice($home);
        if ($priceHomes->isNotEmpty()) {
            $categories[] = [
                'slug' => 'price',
                'title' => 'محدوده قیمت',
                'more_url' => $this->priceMoreUrl($home),
            ];
            $homes['price'] = $priceHomes->values()->all();
        }

        $amenityHomes = $this->byAmenities($home);
        if ($amenityHomes->isNotEmpty()) {
            $categories[] = [
                'slug' => 'amenities',
                'title' => 'امکانات',
                'more_url' => $this->amenitiesMoreUrl($home),
            ];
            $homes['amenities'] = $amenityHomes->values()->all();
        }

        return [
            'categories' => $categories,
            'homes' => $homes,
        ];
    }

    private function baseQuery(Home $home): Builder
    {
        return Home::query()
            ->active()
            ->where('homes.id', '!=', $home->id)
            ->with(['province', 'city'])
            ->withCount(['sleepPlaces as bedroom_count' => function ($query) {
                $query->where('is_share', false);
            }]);
    }

    private function byLocation(Home $home): Collection
    {
        if (! $home->city_id) {
            return collect();
        }

        $homes = $this->baseQuery($home)
            ->where('city_id', $home->city_id)
            ->orderByDesc('fake_score')
            ->limit(self::LIMIT)
            ->get();

        if ($homes->isEmpty() && $home->province_id) {
            $homes = $this->baseQuery($home)
                ->where('province_id', $home->province_id)
                ->orderByDesc('fake_score')
                ->limit(self::LIMIT)
                ->get();
        }

        return $homes->map(fn (Home $item) => $this->formatSimilarHome($item));
    }

    private function byPrice(Home $home): Collection
    {
        $base = max($home->minBaseNightlyPrice(), (int) $home->week_price);

        if ($base <= 0) {
            return collect();
        }

        $min = (int) floor($base * (1 - self::PRICE_TOLERANCE));
        $max = (int) ceil($base * (1 + self::PRICE_TOLERANCE));

        return $this->baseQuery($home)
            ->whereBetween('week_price', [$min, $max])
            ->orderByRaw('ABS(week_price - ?)', [$base])
            ->orderByDesc('fake_score')
            ->limit(self::LIMIT)
            ->get()
            ->map(fn (Home $item) => $this->formatSimilarHome($item));
    }

    private function byAmenities(Home $home): Collection
    {
        $optionIds = $home->options->pluck('id')->filter()->values()->all();

        if ($optionIds === []) {
            return collect();
        }

        return $this->baseQuery($home)
            ->whereHas('options', function ($query) use ($optionIds) {
                $query->whereIn('options.id', $optionIds);
            })
            ->withCount(['options as matching_options_count' => function ($query) use ($optionIds) {
                $query->whereIn('options.id', $optionIds);
            }])
            ->orderByDesc('matching_options_count')
            ->orderByDesc('fake_score')
            ->limit(self::LIMIT)
            ->get()
            ->map(fn (Home $item) => $this->formatSimilarHome($item));
    }

    private function locationMoreUrl(Home $home): string
    {
        $params = array_filter([
            'province' => $home->province_id,
            'city' => $home->city_id,
        ]);

        return route('main.homes.index', $params);
    }

    private function priceMoreUrl(Home $home): string
    {
        $base = max($home->minBaseNightlyPrice(), (int) $home->week_price);
        $min = (int) floor($base * (1 - self::PRICE_TOLERANCE));
        $max = (int) ceil($base * (1 + self::PRICE_TOLERANCE));

        return route('main.homes.index', [
            'min_price' => $min,
            'max_price' => $max,
        ]);
    }

    private function amenitiesMoreUrl(Home $home): string
    {
        $optionIds = $home->options->pluck('id')->filter()->values()->all();

        if ($optionIds === []) {
            return route('main.homes.index');
        }

        return route('main.homes.index', ['options' => $optionIds]);
    }

    /**
     * @return array<string, mixed>
     */
    public function formatSimilarHome(Home $home): array
    {
        $basePrice = max($home->minBaseNightlyPrice(), (int) $home->week_price);
        $maxGuests = (int) $home->main_guest + (int) $home->extra_guest;

        return [
            'id' => $home->id,
            'name' => $home->name,
            'cover' => $home->cover_path,
            'cover_path' => $home->cover_path,
            'city' => $home->city ? ['id' => $home->city->id, 'name' => $home->city->name] : null,
            'province' => $home->province ? ['id' => $home->province->id, 'name' => $home->province->name] : null,
            ...$home->guestRatingPayload(),
            'discount_percent' => 0,
            'off_price' => $basePrice,
            'original_price' => $basePrice,
            'bedroom_count' => (int) ($home->bedroom_count ?? 0),
            'infrastructure_meter' => $home->infrastructure_meter,
            'main_guest' => $home->main_guest,
            'max_guests' => $maxGuests > 0 ? $maxGuests : (int) $home->main_guest,
            'type_label' => $home->typeLabel(),
            'is_favorite' => $home->isFavorite(),
        ];
    }
}
