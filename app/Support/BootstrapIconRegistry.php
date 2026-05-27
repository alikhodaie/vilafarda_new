<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class BootstrapIconRegistry
{
    private const CACHE_KEY = 'bootstrap_icons.all';

    private const CSS_PATH = 'vendor/bootstrap-icons/font/bootstrap-icons.min.css';

    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $path = public_path(self::CSS_PATH);

            if (! is_readable($path)) {
                return config('home_option_icons.allowed', []);
            }

            $css = file_get_contents($path);

            if (! preg_match_all('/\.(bi-[a-z0-9-]+)(?::|,|\{)/', $css, $matches)) {
                return config('home_option_icons.allowed', []);
            }

            return array_values(array_unique($matches[1]));
        });
    }

    public static function isAllowed(string $iconClass): bool
    {
        $normalized = self::normalize($iconClass);

        return in_array($normalized, self::all(), true);
    }

    public static function normalize(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/^bi\s+/', '', $value);

        if ($value !== '' && ! str_starts_with($value, 'bi-')) {
            $value = 'bi-'.$value;
        }

        return $value;
    }

    public static function searchAliases(): array
    {
        return config('home_option_icons.search_aliases', []);
    }

    public static function featured(): array
    {
        $featured = config('home_option_icons.featured', []);
        $allowed = self::all();

        return array_values(array_intersect($featured, $allowed));
    }
}
