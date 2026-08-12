<?php

namespace App\Services\ExternalCalendar;

use App\Support\ExternalCalendarPlatform;
use Illuminate\Support\Facades\Http;

class JabamaCalendarFetcher
{
    private const UNAVAILABLE_STATUSES = [
        'reserved',
        'disabled',
        'unavailable',
        'soldout',
        'blocked',
    ];

    public function fetchUnavailableDates(string $roomId): array
    {
        $placeId = $this->normalizePlaceId($roomId);

        if ($placeId === '') {
            throw new \RuntimeException('شناسه اقامتگاه جاباما نامعتبر است.');
        }

        $response = Http::timeout(20)
            ->withHeaders([
                'Accept' => 'application/json',
                'Origin' => 'https://www.jabama.com',
                'Referer' => "https://www.jabama.com/stay/villa-{$placeId}",
                'User-Agent' => 'Mozilla/5.0 (compatible; VilafardaCalendarSync/1.0)',
            ])
            ->get("https://gw.jabama.com/api/v1/accommodations/{$placeId}");

        if (! $response->successful()) {
            throw new \RuntimeException('دریافت تقویم از جاباما ناموفق بود (کد '.$response->status().').');
        }

        $calendar = $response->json('result.item.calendar', []);

        if (! is_array($calendar)) {
            throw new \RuntimeException('پاسخ تقویم جاباما نامعتبر است.');
        }

        $blocked = [];

        foreach ($calendar as $day) {
            if (! is_array($day) || empty($day['date'])) {
                continue;
            }

            $status = strtolower((string) ($day['status'] ?? ''));

            if (in_array($status, self::UNAVAILABLE_STATUSES, true)) {
                $blocked[] = $day['date'];
            }
        }

        sort($blocked);

        return array_values(array_unique($blocked));
    }

    public static function supports(?string $platform): bool
    {
        return $platform === ExternalCalendarPlatform::JABAMA;
    }

    private function normalizePlaceId(string $roomId): string
    {
        $roomId = trim($roomId);

        if (preg_match('/(\d+)$/', $roomId, $matches)) {
            return $matches[1];
        }

        return $roomId;
    }
}
