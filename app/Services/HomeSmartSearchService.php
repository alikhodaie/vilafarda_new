<?php

namespace App\Services;

use App\Models\City;
use App\Models\Home;
use App\Models\Option;
use App\Models\Province;
use Illuminate\Database\Eloquent\Builder;

class HomeSmartSearchService
{
    /** @var array<string, string> */
    private array $typeAliases;

    /** @var array<string, array<int, string>> */
    private array $amenityKeywords;

    /** @var array<string, string> */
    private array $featureSlugs;

    public function __construct()
    {
        $this->typeAliases = config('home_smart_search.type_aliases', []);
        $this->amenityKeywords = config('home_smart_search.amenity_keywords', []);
        $this->featureSlugs = config('home_smart_search.feature_slugs', []);
    }

    /**
     * @param  array<int, string>  $terms
     */
    public function applySearchTerms(Builder $query, array $terms): void
    {
        $terms = array_values(array_filter(array_map(
            fn ($t) => trim((string) $t),
            $terms
        ), fn ($t) => $t !== ''));

        if ($terms === []) {
            return;
        }

        if (count($terms) === 1) {
            $this->applySearchTerm($query, $terms[0]);

            return;
        }

        $query->where(function (Builder $outer) use ($terms) {
            foreach ($terms as $term) {
                $outer->orWhere(function (Builder $q) use ($term) {
                    $this->applySingleTokenSearch($q, $term);
                });
            }
        });
    }

    public function applySearchTerm(Builder $query, string $term): void
    {
        $term = trim($term);
        if ($term === '') {
            return;
        }

        $tokens = $this->tokenize($term);

        if ($tokens === []) {
            $this->applySingleTokenSearch($query, $term);

            return;
        }

        if (count($tokens) === 1) {
            $this->applySingleTokenSearch($query, $tokens[0]);

            return;
        }

        // چند کلمه (مثلاً کردان چهارباغ) → OR: اقامتگاه‌های هر یک از شهرها/فیلترها
        $query->where(function (Builder $outer) use ($tokens, $term) {
            foreach ($tokens as $token) {
                $outer->orWhere(function (Builder $q) use ($token) {
                    $this->applySingleTokenSearch($q, $token);
                });
            }

            $outer->orWhere(function (Builder $q) use ($term) {
                $this->applyBroadTextSearch($q, $term);
            });
        });
    }

    private function applySingleTokenSearch(Builder $query, string $token): void
    {
        $parsed = new ParsedHomeSearch();
        $this->classifyToken($token, $parsed);

        if ($parsed->hasStructuredFilters()) {
            $this->applyStructuredFilters($query, $parsed);

            return;
        }

        $this->applyBroadTextSearch($query, $token);
    }

    /**
     * @param  array<int, string>  $featureSlugs
     */
    public function applyFeatureSlugs(Builder $query, array $featureSlugs): void
    {
        $optionIds = [];

        foreach ($featureSlugs as $slug) {
            $key = $this->featureSlugs[$slug] ?? null;
            if ($key && isset($this->amenityKeywords[$key])) {
                $optionIds = array_merge($optionIds, $this->findOptionIdsBySynonyms($this->amenityKeywords[$key]));
            }
        }

        $optionIds = array_values(array_unique($optionIds));

        if ($optionIds !== []) {
            $query->whereHas('options', function (Builder $options) use ($optionIds) {
                $options->whereIn('option_id', $optionIds);
            });
        }
    }

    /**
     * @return array<int, string>
     */
    private function tokenize(string $term): array
    {
        $normalized = $this->normalize($term);
        $parts = preg_split('/[\s,،]+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_filter($parts, fn (string $t) => mb_strlen($t) >= 2));
    }

    private function classifyToken(string $token, ParsedHomeSearch $parsed): void
    {
        $normalized = $this->normalize($token);

        if ($normalized === '') {
            return;
        }

        $type = $this->resolveType($normalized);
        if ($type !== null) {
            $parsed->types[] = $type;

            return;
        }

        $optionIds = $this->resolveOptionIds($normalized);
        if ($optionIds !== []) {
            $parsed->optionIds = array_values(array_unique(array_merge($parsed->optionIds, $optionIds)));

            return;
        }

        $cityIds = $this->resolveCityIds($normalized);
        if ($cityIds !== []) {
            $parsed->cityIds = array_values(array_unique(array_merge($parsed->cityIds, $cityIds)));

            return;
        }

        $provinceIds = $this->resolveProvinceIds($normalized);
        if ($provinceIds !== []) {
            $parsed->provinceIds = array_values(array_unique(array_merge($parsed->provinceIds, $provinceIds)));
        }
    }

    private function resolveType(string $normalized): ?string
    {
        if (isset($this->typeAliases[$normalized])) {
            return $this->typeAliases[$normalized];
        }

        foreach (Home::TYPES as $meta) {
            $label = $this->normalize($meta['fa_text'] ?? '');
            if ($label === $normalized) {
                return $meta['value'];
            }
        }

        foreach (Home::TYPES as $meta) {
            $label = $this->normalize($meta['fa_text'] ?? '');
            if ($label !== '' && $this->containsMatch($normalized, $label)) {
                return $meta['value'];
            }
        }

        foreach ($this->typeAliases as $alias => $typeValue) {
            $aliasNorm = $this->normalize($alias);
            if ($aliasNorm !== '' && $this->containsMatch($normalized, $aliasNorm)) {
                return $typeValue;
            }
        }

        return null;
    }

    /**
     * @return array<int, int>
     */
    private function resolveOptionIds(string $normalized): array
    {
        $ids = [];

        foreach ($this->amenityKeywords as $keyword => $synonyms) {
            $keyNorm = $this->normalize($keyword);
            if ($this->termMatchesAmenityKey($normalized, $keyNorm, $synonyms)) {
                $ids = array_merge($ids, $this->findOptionIdsBySynonyms($synonyms));
            }
        }

        foreach (Option::getFromCache() as $option) {
            $title = $this->normalize((string) $option->title);
            if ($title !== '' && $this->containsMatch($normalized, $title)) {
                $ids[] = (int) $option->id;
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * @param  array<int, string>  $synonyms
     */
    private function termMatchesAmenityKey(string $term, string $keyNorm, array $synonyms): bool
    {
        if ($term === $keyNorm || $this->containsMatch($term, $keyNorm)) {
            return true;
        }

        foreach ($synonyms as $synonym) {
            $synNorm = $this->normalize($synonym);
            if ($synNorm !== '' && ($term === $synNorm || $this->containsMatch($term, $synNorm))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, string>  $synonyms
     * @return array<int, int>
     */
    private function findOptionIdsBySynonyms(array $synonyms): array
    {
        $ids = [];

        foreach (Option::getFromCache() as $option) {
            $title = $this->normalize((string) $option->title);
            foreach ($synonyms as $synonym) {
                $synNorm = $this->normalize($synonym);
                if ($synNorm !== '' && $title !== '' && $this->containsMatch($title, $synNorm)) {
                    $ids[] = (int) $option->id;
                    break;
                }
            }
        }

        return $ids;
    }

    /**
     * @return array<int, int>
     */
    private function resolveCityIds(string $normalized): array
    {
        $exact = City::query()
            ->whereRaw($this->normalizedNameSql('name').' = ?', [$normalized])
            ->pluck('id')
            ->all();

        if ($exact !== []) {
            return array_map('intval', $exact);
        }

        if (mb_strlen($normalized) < 2) {
            return [];
        }

        return City::query()
            ->where('name', 'LIKE', '%'.$normalized.'%')
            ->orWhereRaw($this->normalizedNameSql('name').' LIKE ?', ['%'.$normalized.'%'])
            ->limit(12)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return array<int, int>
     */
    private function resolveProvinceIds(string $normalized): array
    {
        $exact = Province::query()
            ->whereRaw($this->normalizedNameSql('name').' = ?', [$normalized])
            ->pluck('id')
            ->all();

        if ($exact !== []) {
            return array_map('intval', $exact);
        }

        if (mb_strlen($normalized) < 2) {
            return [];
        }

        return Province::query()
            ->where('name', 'LIKE', '%'.$normalized.'%')
            ->orWhereRaw($this->normalizedNameSql('name').' LIKE ?', ['%'.$normalized.'%'])
            ->limit(8)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function applyStructuredFilters(Builder $query, ParsedHomeSearch $parsed): void
    {
        if ($parsed->types !== []) {
            $query->whereIn('type', $parsed->types);
        }

        if ($parsed->cityIds !== []) {
            $query->whereIn('city_id', $parsed->cityIds);
        }

        if ($parsed->provinceIds !== []) {
            $query->whereIn('province_id', $parsed->provinceIds);
        }

        if ($parsed->optionIds !== []) {
            $query->whereHas('options', function (Builder $options) use ($parsed) {
                $options->whereIn('option_id', $parsed->optionIds);
            });
        }
    }

    private function applyBroadTextSearch(Builder $query, string $term): void
    {
        $like = '%'.$term.'%';

        $query->where(function (Builder $q) use ($like) {
            $q->where('name', 'LIKE', $like)
                ->orWhere('code', 'LIKE', $like)
                ->orWhereHas('city', fn (Builder $city) => $city->where('name', 'LIKE', $like))
                ->orWhereHas('province', fn (Builder $province) => $province->where('name', 'LIKE', $like));
        });
    }

    private function containsMatch(string $needle, string $haystack): bool
    {
        if ($needle === '' || $haystack === '') {
            return false;
        }

        if ($needle === $haystack) {
            return true;
        }

        $min = min(mb_strlen($needle), mb_strlen($haystack));

        if ($min < 2) {
            return false;
        }

        return str_contains($haystack, $needle) || str_contains($needle, $haystack);
    }

    private function normalize(string $text): string
    {
        $text = trim($text);
        $text = str_replace(["\u{200c}", '‌', 'ـ'], '', $text);
        $text = str_replace(['ي', 'ك', 'ى'], ['ی', 'ک', 'ی'], $text);
        $text = mb_strtolower($text, 'UTF-8');

        return preg_replace('/\s+/u', ' ', $text) ?? $text;
    }

    /** SQL expression for normalized Persian comparison (MySQL). */
    private function normalizedNameSql(string $column): string
    {
        return "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE({$column}, '‌', ''), 'ي', 'ی'), 'ك', 'ک'), 'ى', 'ی'), 'ـ', ''))";
    }
}

/**
 * @internal
 */
final class ParsedHomeSearch
{
    /** @var array<int, string> */
    public array $types = [];

    /** @var array<int, int> */
    public array $cityIds = [];

    /** @var array<int, int> */
    public array $provinceIds = [];

    /** @var array<int, int> */
    public array $optionIds = [];

    public function hasStructuredFilters(): bool
    {
        return $this->types !== []
            || $this->cityIds !== []
            || $this->provinceIds !== []
            || $this->optionIds !== [];
    }
}
