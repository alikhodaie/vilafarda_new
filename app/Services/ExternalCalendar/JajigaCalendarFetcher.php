<?php

namespace App\Services\ExternalCalendar;

use App\Support\ExternalCalendarPlatform;
use Illuminate\Support\Facades\Http;

class JajigaCalendarFetcher
{
    public function fetchUnavailableDates(string $roomId): array
    {
        $response = Http::timeout(20)
            ->withHeaders([
                'Accept' => 'application/json',
                'Origin' => 'https://www.jajiga.com',
                'Referer' => "https://www.jajiga.com/room/{$roomId}",
                'User-Agent' => 'Mozilla/5.0 (compatible; VilafardaCalendarSync/1.0)',
            ])
            ->get('https://api.jajiga.com/api/nights', [
                'room_id' => $roomId,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('دریافت تقویم از جاجیگا ناموفق بود (کد '.$response->status().').');
        }

        $nights = $response->json('nights', []);

        if (! is_array($nights)) {
            throw new \RuntimeException('پاسخ تقویم جاجیگا نامعتبر است.');
        }

        $blocked = [];

        foreach ($nights as $night) {
            if (! empty($night['is_unavailable']) && ! empty($night['date'])) {
                $blocked[] = $night['date'];
            }
        }

        sort($blocked);

        return array_values(array_unique($blocked));
    }

    public static function supports(?string $platform): bool
    {
        return $platform === ExternalCalendarPlatform::JAJIGA;
    }
}
