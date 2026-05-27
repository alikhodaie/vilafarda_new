<?php

namespace App\Support;

use App\Models\Home;

class HomeSlug
{
    public static function normalize(?string $slug): string
    {
        $slug = generateSlug($slug ?? '');
        $slug = preg_replace('/-+/u', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        return mb_substr($slug, 0, 200);
    }

    public static function suggestFor(Home $home): string
    {
        $home->loadMissing(['city', 'province']);

        $type = $home->typeLabel() ?: 'اقامتگاه';
        $city = trim((string) ($home->city->name ?? ''));

        $parts = ['اجاره', $type];
        if ($city !== '') {
            $parts[] = 'در';
            $parts[] = $city;
        }

        return self::normalize(implode(' ', $parts));
    }

    public static function routeSegment(Home $home): string
    {
        $slug = self::normalize($home->slug) ?: self::suggestFor($home);

        return $slug.'-'.$home->id;
    }

    /**
     * Scalar primary key for Eloquent find/whereKey (never pass a Model to findOrFail on a relation).
     */
    public static function resolveKeyForQuery(mixed $home): int
    {
        if ($home instanceof Home) {
            return (int) $home->getKey();
        }

        return (int) $home;
    }

    public static function resolveIdFromRouteValue(string $value): ?int
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/-(\d+)$/', $value, $matches)) {
            return (int) $matches[1];
        }

        if (ctype_digit($value)) {
            return (int) $value;
        }

        return null;
    }
}
