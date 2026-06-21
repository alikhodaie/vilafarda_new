<?php

namespace App\Support;

class MapConfig
{
    public static function provider(): string
    {
        return (string) config('map.provider', 'osm');
    }

    public static function usesNeshanSdk(): bool
    {
        return self::provider() === 'neshan'
            && trim((string) config('map.neshan.api_key', '')) !== '';
    }

    public static function neshanMapType(): string
    {
        $style = trim((string) config('map.neshan.style', 'neshan'));

        $aliases = [
            'standard-day' => 'neshan',
            'standard_day' => 'neshan',
            'day' => 'neshan',
        ];

        return $aliases[$style] ?? $style;
    }

    public static function tileUrl(): string
    {
        if (self::usesNeshanSdk()) {
            return '';
        }

        $override = trim((string) config('map.tile_url', ''));

        if ($override !== '') {
            return $override;
        }

        switch (self::provider()) {
            case 'mapir':
                $key = trim((string) config('map.mapir.api_key', ''));

                return "https://map.ir/raster/{z}/{x}/{y}.png?x-api-key={$key}";

            case 'local':
                return (string) config('map.local.tile_url', '/tiles/{z}/{x}/{y}.png');

            case 'osm':
            default:
                return (string) config('map.osm.tile_url');
        }
    }

    public static function attribution(): string
    {
        $custom = trim((string) config('map.attribution', ''));

        if ($custom !== '') {
            return $custom;
        }

        switch (self::provider()) {
            case 'neshan':
                return '© نشان';
            case 'mapir':
                return '© map.ir';
            case 'local':
                return '© نقشه داخلی';
            default:
                return '© OpenStreetMap';
        }
    }

    public static function geocoderEnabled(): bool
    {
        return (bool) config('map.geocoder.enabled', true);
    }

    public static function geocoderProvider(): string
    {
        $configured = trim((string) config('map.geocoder.provider', ''));

        return $configured !== '' ? $configured : self::provider();
    }

    public static function reverseGeocodeUrl(): string
    {
        return url('/api/map/reverse');
    }

    public static function neshanSdkCssUrl(): string
    {
        return asset('vendor/neshan/index.css');
    }

    public static function neshanSdkJsUrl(): string
    {
        return asset('vendor/neshan/index.js');
    }

    public static function toJsConfig(): array
    {
        $config = [
            'provider' => self::provider(),
            'usesNeshanSdk' => self::usesNeshanSdk(),
            'tileUrl' => self::tileUrl(),
            'attribution' => self::attribution(),
            'maxZoom' => (int) config('map.max_zoom', 19),
            'geocoderEnabled' => self::geocoderEnabled(),
            'reverseGeocodeUrl' => self::reverseGeocodeUrl(),
        ];

        if (self::usesNeshanSdk()) {
            $config['neshanApiKey'] = trim((string) config('map.neshan.api_key', ''));
            $config['neshanMapType'] = self::neshanMapType();
        }

        return $config;
    }
}
