<?php

namespace App\Services;

use App\Models\Home;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LandingPageFilterApplier
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function apply(Builder $query, array $filters, bool $cityOnly = true): Builder
    {
        if ($filters === []) {
            return $query;
        }

        $this->applySearch($query, $filters);
        $this->applyFeatures($query, $filters);
        $this->applyPricing($query, $filters);
        $this->applyTypeFilters($query, $filters);
        $this->applyMiscFilters($query, $filters);
        $this->applyOptions($query, $filters);
        $this->applyLocation($query, $filters, $cityOnly);
        $this->applyVariables($query, $filters);
        $this->applyArea($query, $filters);
        $this->applyAvailabilityFilter($query, $filters);
        $this->applySort($query, $filters);

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applySearch(Builder $query, array $filters): void
    {
        $searchTerms = $filters['q'] ?? [];
        if (! is_array($searchTerms)) {
            $searchTerms = $searchTerms !== '' ? [(string) $searchTerms] : [];
        }

        $searchTerms = array_values(array_filter(array_map(
            fn ($t) => trim((string) $t),
            $searchTerms
        ), fn ($t) => $t !== ''));

        $smartSearch = app(HomeSmartSearchService::class);

        if ($searchTerms === []) {
            $legacy = trim((string) ($filters['name'] ?? $filters['search'] ?? ''));

            if ($legacy !== '') {
                $smartSearch->applySearchTerm($query, $legacy);
            }
        } else {
            $smartSearch->applySearchTerms($query, $searchTerms);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyFeatures(Builder $query, array $filters): void
    {
        $features = $filters['features'] ?? [];

        if (is_array($features) && $features !== []) {
            app(HomeSmartSearchService::class)->applyFeatureSlugs($query, $features);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyPricing(Builder $query, array $filters): void
    {
        if (! empty($filters['min_price'])) {
            $query->where('week_price', '>=', (int) $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $query->where('week_price', '<=', (int) $filters['max_price']);
        }

        if (! empty($filters['price_range'])) {
            $range = explode(';', (string) $filters['price_range']);
            if (count($range) === 2) {
                $query->whereBetween('week_price', $range);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyTypeFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['type'])) {
            $typeMap = [
                'villa' => Home::VILAIY,
                'apartment' => Home::APARTEMAN,
                'house' => Home::KHANE_ROOSTANIY,
            ];
            $type = $typeMap[$filters['type']] ?? $filters['type'];
            $query->where('type', $type);
        }

        if (! empty($filters['types']) && is_array($filters['types'])) {
            $query->whereIn('type', $filters['types']);
        }

        if (! empty($filters['atmospheres']) && is_array($filters['atmospheres'])) {
            $query->whereIn('atmosphere', $filters['atmospheres']);
        }

        if (! empty($filters['areas']) && is_array($filters['areas'])) {
            $query->whereIn('area', $filters['areas']);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyMiscFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['fast_reserve'])) {
            $query->whereNotNull('fast_reserve_start_at')
                ->whereNotNull('fast_reserve_end_at');
        }

        if (! empty($filters['guest_count'])) {
            $query->whereRaw('(main_guest + COALESCE(extra_guest, 0)) >= ?', [(int) $filters['guest_count']]);
        }

        if (! empty($filters['guest'])) {
            $query->whereRaw('(main_guest + extra_guest) >= '.(int) $filters['guest']);
        }

        if (! empty($filters['bed_count'])) {
            $bedCount = (int) $filters['bed_count'];
            $query->whereHas('sleepPlaces', function ($q) use ($bedCount) {
                $q->select(DB::raw('SUM(single_bed + double_bed) as count_bed'))
                    ->havingRaw('count_bed >= '.$bedCount);
            });
        }

        if (! empty($filters['bedroom_count'])) {
            $query->whereHas('sleepPlaces', function ($q) {
                $q->where('is_share', false);
            }, '>=', (int) $filters['bedroom_count']);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyOptions(Builder $query, array $filters): void
    {
        $options = $filters['options'] ?? [];

        if (! is_array($options) || $options === []) {
            return;
        }

        $optionIds = array_values(array_filter(array_map('intval', $options)));

        if ($optionIds === []) {
            return;
        }

        $query->whereHas('options', function (Builder $optionsQuery) use ($optionIds) {
            $optionsQuery->whereIn('option_id', $optionIds);
        });
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyLocation(Builder $query, array $filters, bool $cityOnly): void
    {
        $province = $filters['province'] ?? null;
        $city = $filters['city'] ?? null;

        if ($province && $city) {
            if ($cityOnly) {
                $query->where('city_id', (int) $city);
            } else {
                $query->where('province_id', (int) $province);
                $query->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [(int) $city]);
            }
        } elseif ($province) {
            $query->where('province_id', (int) $province);
        } elseif ($city) {
            $query->where('city_id', (int) $city);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyVariables(Builder $query, array $filters): void
    {
        $variables = $filters['variables'] ?? [];

        if (! is_array($variables)) {
            return;
        }

        foreach ($variables as $variableId => $option) {
            if (! $option) {
                continue;
            }

            $query->whereHas('variables', function (Builder $q) use ($variableId, $option) {
                $q->where('variable_id', $variableId)
                    ->where(function (Builder $inner) use ($option) {
                        $inner->where('option_id', $option)
                            ->orWhere('value', $option);
                    });
            });
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyArea(Builder $query, array $filters): void
    {
        if (! empty($filters['min_area'])) {
            $query->where('infrastructure_meter', '>', $filters['min_area']);
        }

        if (! empty($filters['max_area'])) {
            $query->where('infrastructure_meter', '<', $filters['max_area']);
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyAvailabilityFilter(Builder $query, array $filters): void
    {
        $filter = $filters['filter'] ?? null;

        if ($filter === 'open_now') {
            $query->whereDoesntHave('orders', function (Builder $q) {
                $date = now()->startOfDay();
                $q->where('start_at', '<=', $date)->where('end_at', '>=', $date);
            });
        }

        if ($filter === 'open_tomorrow') {
            $query->whereDoesntHave('orders', function (Builder $q) {
                $date = now()->addDay()->startOfDay();
                $q->where('start_at', '<=', $date)->where('end_at', '>=', $date);
            });
        }

        if ($filter === 'off') {
            $query->lastMinuteOffAvailable();
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applySort(Builder $query, array $filters): void
    {
        $sort = $filters['sort'] ?? null;

        if ($sort === 'expensive') {
            $query->orderByDesc('week_price');
        } elseif ($sort === 'cheap') {
            $query->orderBy('week_price');
        } elseif ($sort === 'popular') {
            $query->orderByDesc('fake_score');
        }
    }
}
