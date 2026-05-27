<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LandingPage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'faq' => 'array',
        'filters' => 'array',
        'city_only' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query): Builder
    {
        if (request()->filled('id')) {
            $query->where('id', request('id'));
        }

        if (request()->filled('search')) {
            $term = '%'.request('search').'%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('title', 'LIKE', $term)
                    ->orWhere('slug', 'LIKE', $term);
            });
        }

        if (request()->filled('is_active')) {
            $query->where('is_active', (bool) request('is_active'));
        }

        return $query;
    }

    public function applyHomeFilters(Builder $query): Builder
    {
        $filters = is_array($this->filters) ? $this->filters : [];

        if ($filters !== []) {
            return app(\App\Services\LandingPageFilterApplier::class)->apply(
                $query,
                $filters,
                (bool) $this->city_only
            );
        }

        if ($this->home_type) {
            $query->where('type', $this->home_type);
        }

        if ($this->province_id && $this->city_id) {
            if ($this->city_only) {
                $query->where('city_id', $this->city_id);
            } else {
                $query->where('province_id', $this->province_id)
                    ->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [(int) $this->city_id]);
            }
        } elseif ($this->province_id) {
            $query->where('province_id', $this->province_id);
        } elseif ($this->city_id) {
            $query->where('city_id', $this->city_id);
        }

        return $query;
    }

    public function hasAdvancedFilters(): bool
    {
        return is_array($this->filters) && $this->filters !== [];
    }

    /**
     * @return array<int, string>
     */
    public function filterSummaryLabels(): array
    {
        $filters = is_array($this->filters) ? $this->filters : [];

        if ($filters === []) {
            return [];
        }

        $labels = [];
        $featureLabels = [
            'wifi' => 'وای‌فای',
            'parking' => 'پارکینگ',
            'pool' => 'استخر',
            'garden' => 'باغ',
        ];

        if (! empty($filters['features']) && is_array($filters['features'])) {
            foreach ($filters['features'] as $slug) {
                $labels[] = $featureLabels[$slug] ?? (string) $slug;
            }
        }

        if (! empty($filters['options']) && is_array($filters['options'])) {
            $options = \App\Models\Option::query()
                ->whereIn('id', array_map('intval', $filters['options']))
                ->pluck('title', 'id');

            foreach ($filters['options'] as $optionId) {
                $labels[] = $options[(int) $optionId] ?? 'امکان #'.$optionId;
            }
        }

        if (! empty($filters['types']) && is_array($filters['types'])) {
            foreach ($filters['types'] as $type) {
                $labels[] = Home::TYPES[$type]['fa_text'] ?? (string) $type;
            }
        } elseif (! empty($filters['type'])) {
            $type = (string) $filters['type'];
            $labels[] = Home::TYPES[$type]['fa_text'] ?? $type;
        }

        if (! empty($filters['min_price']) || ! empty($filters['max_price'])) {
            $labels[] = 'محدوده قیمت';
        }

        return array_values(array_unique($labels));
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    public function faqItems(): array
    {
        $items = is_array($this->faq) ? $this->faq : [];

        return array_values(array_filter(array_map(function ($item) {
            if (! is_array($item)) {
                return null;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                return null;
            }

            return compact('question', 'answer');
        }, $items)));
    }

    public function seoTitleSegment(): string
    {
        return trim((string) ($this->meta_title ?: $this->title));
    }

    public function seoDescription(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        return Str::limit(strip_tags((string) $this->intro), 150, '…');
    }

    public function homeTypeLabel(): ?string
    {
        if (! $this->home_type || ! array_key_exists($this->home_type, Home::TYPES)) {
            return null;
        }

        return Home::TYPES[$this->home_type]['fa_text'] ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getUrlAttribute(): string
    {
        return route('main.landing-pages.show', $this->slug);
    }

    public static function findActiveUrlFor(?int $provinceId, ?int $cityId, ?string $homeType = null): ?string
    {
        if (! $provinceId && ! $cityId) {
            return null;
        }

        $query = static::query()->active()->orderBy('sort');

        if ($provinceId) {
            $query->where('province_id', $provinceId);
        }

        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        if ($homeType) {
            $query->where(function (Builder $q) use ($homeType) {
                $q->where('home_type', $homeType)->orWhereNull('home_type');
            })->orderByRaw('CASE WHEN home_type = ? THEN 0 ELSE 1 END', [$homeType]);
        } else {
            $query->whereNull('home_type');
        }

        $page = $query->first();

        return $page?->url;
    }
}
