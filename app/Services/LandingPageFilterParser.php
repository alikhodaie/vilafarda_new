<?php

namespace App\Services;

use App\Models\Home;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LandingPageFilterParser
{
    /** @var string[] */
    private const ALLOWED_KEYS = [
        'province',
        'city',
        'type',
        'types',
        'features',
        'options',
        'atmospheres',
        'areas',
        'q',
        'name',
        'search',
        'min_price',
        'max_price',
        'price_range',
        'bed_count',
        'bedroom_count',
        'guest',
        'guest_count',
        'filter',
        'fast_reserve',
        'variables',
        'min_area',
        'max_area',
        'sort',
    ];

    /** @var string[] */
    private const EXCLUDED_KEYS = [
        'page',
        'start_at',
        'end_at',
        'status',
        'user',
        'id',
    ];

    /**
     * @return array{filters: array<string, mixed>, province_id: ?int, city_id: ?int, home_type: ?string}
     */
    public function parse(?string $input): array
    {
        $input = trim((string) $input);

        if ($input === '') {
            return [
                'filters' => [],
                'province_id' => null,
                'city_id' => null,
                'home_type' => null,
            ];
        }

        $queryString = $this->extractQueryString($input);
        parse_str($queryString, $raw);

        if (! is_array($raw) || $raw === []) {
            throw new InvalidArgumentException(__('text.landing_filter_url_invalid'));
        }

        $filters = $this->normalizeRawQuery($raw);

        if ($filters === []) {
            throw new InvalidArgumentException(__('text.landing_filter_url_empty'));
        }

        return [
            'filters' => $filters,
            'province_id' => $this->intOrNull($filters['province'] ?? null),
            'city_id' => $this->intOrNull($filters['city'] ?? null),
            'home_type' => $this->resolveHomeType($filters),
        ];
    }

    private function extractQueryString(string $input): string
    {
        if (! str_contains($input, '://') && str_starts_with($input, '?')) {
            return ltrim($input, '?');
        }

        if (! str_contains($input, '://') && ! str_starts_with($input, '/')) {
            return $input;
        }

        $path = parse_url($input, PHP_URL_PATH) ?? '';
        $path = urldecode($path);

        if ($path !== '' && ! Str::contains($path, '/homes')) {
            throw new InvalidArgumentException(__('text.landing_filter_url_not_homes'));
        }

        $query = parse_url($input, PHP_URL_QUERY);

        if (! is_string($query) || $query === '') {
            throw new InvalidArgumentException(__('text.landing_filter_url_no_query'));
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function normalizeRawQuery(array $raw): array
    {
        $filters = [];

        foreach ($raw as $key => $value) {
            $key = (string) $key;

            if (in_array($key, self::EXCLUDED_KEYS, true)) {
                continue;
            }

            if (! in_array($key, self::ALLOWED_KEYS, true)) {
                continue;
            }

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $filters[$key] = $this->normalizeValue($key, $value);
        }

        if (isset($filters['features']) && ! is_array($filters['features'])) {
            $filters['features'] = [$filters['features']];
        }

        if (isset($filters['options']) && ! is_array($filters['options'])) {
            $filters['options'] = [$filters['options']];
        }

        if (isset($filters['types']) && ! is_array($filters['types'])) {
            $filters['types'] = [$filters['types']];
        }

        if (isset($filters['atmospheres']) && ! is_array($filters['atmospheres'])) {
            $filters['atmospheres'] = [$filters['atmospheres']];
        }

        if (isset($filters['areas']) && ! is_array($filters['areas'])) {
            $filters['areas'] = [$filters['areas']];
        }

        if (isset($filters['q']) && ! is_array($filters['q'])) {
            $filters['q'] = array_values(array_filter([(string) $filters['q']]));
        }

        return $filters;
    }

    /**
     * @param  mixed  $value
     * @return mixed
     */
    private function normalizeValue(string $key, $value)
    {
        if ($key === 'variables' && is_array($value)) {
            $normalized = [];
            foreach ($value as $variableId => $option) {
                if ($option !== null && $option !== '') {
                    $normalized[(string) $variableId] = $option;
                }
            }

            return $normalized === [] ? null : $normalized;
        }

        if (is_array($value)) {
            return array_values(array_filter(array_map(
                fn ($v) => is_scalar($v) ? trim((string) $v) : $v,
                $value
            ), fn ($v) => $v !== null && $v !== ''));
        }

        return is_scalar($value) ? trim((string) $value) : $value;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function resolveHomeType(array $filters): ?string
    {
        if (! empty($filters['type'])) {
            $typeMap = [
                'villa' => Home::VILAIY,
                'apartment' => Home::APARTEMAN,
                'house' => Home::KHANE_ROOSTANIY,
            ];
            $type = (string) $filters['type'];

            return $typeMap[$type] ?? (array_key_exists($type, Home::TYPES) ? $type : null);
        }

        if (! empty($filters['types']) && is_array($filters['types']) && count($filters['types']) === 1) {
            $type = (string) $filters['types'][0];

            return array_key_exists($type, Home::TYPES) ? $type : null;
        }

        return null;
    }

    /**
     * @param  mixed  $value
     */
    private function intOrNull($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $int = (int) $value;

        return $int > 0 ? $int : null;
    }
}
