<?php

namespace App\Services;

use App\Support\MapConfig;
use Illuminate\Support\Facades\Http;
use Throwable;

class MapGeocodeService
{
    public function reverse(float $lat, float $lng): ?string
    {
        if (! MapConfig::geocoderEnabled()) {
            return null;
        }

        $provider = MapConfig::geocoderProvider();

        try {
            switch ($provider) {
                case 'neshan':
                    return $this->reverseNeshan($lat, $lng);
                case 'mapir':
                    return $this->reverseMapir($lat, $lng);
                case 'local':
                    return $this->reverseLocal($lat, $lng);
                case 'osm':
                default:
                    return $this->reverseOsm($lat, $lng);
            }
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    private function reverseNeshan(float $lat, float $lng): ?string
    {
        $apiKey = trim((string) config('map.neshan.api_key', ''));

        if ($apiKey === '') {
            return null;
        }

        $response = Http::timeout(10)
            ->withHeaders(['Api-Key' => $apiKey])
            ->get('https://api.neshan.org/v4/reverse', [
                'lat' => $lat,
                'lng' => $lng,
            ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return $data['formatted_address'] ?? $data['address'] ?? null;
    }

    private function reverseMapir(float $lat, float $lng): ?string
    {
        $apiKey = trim((string) config('map.mapir.api_key', ''));

        if ($apiKey === '') {
            return null;
        }

        $response = Http::timeout(10)
            ->withHeaders(['x-api-key' => $apiKey])
            ->get('https://map.ir/reverse', [
                'lat' => $lat,
                'lon' => $lng,
            ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return $data['address'] ?? $data['address_compact'] ?? null;
    }

    private function reverseLocal(float $lat, float $lng): ?string
    {
        $url = trim((string) config('map.local.geocoder_url', ''));

        if ($url === '') {
            return null;
        }

        $response = Http::timeout(10)->get($url, [
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lng,
            'lng' => $lng,
            'accept-language' => 'fa',
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return $data['display_name'] ?? $data['formatted_address'] ?? $data['address'] ?? null;
    }

    private function reverseOsm(float $lat, float $lng): ?string
    {
        $response = Http::timeout(10)
            ->withHeaders(['User-Agent' => config('app.name', 'Rentnaab').'/1.0'])
            ->get((string) config('map.osm.geocoder_url'), [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lng,
                'accept-language' => 'fa',
            ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return $data['display_name'] ?? null;
    }
}
