<?php

namespace App\Support;

class ExternalCalendarPlatform
{
    public const JAJIGA = 'jajiga';

    public const JABAMA = 'jabama';

    public const OTAGHAK = 'otaghak';

    public const OTHER = 'other';

    public static function labels(): array
    {
        return [
            self::JAJIGA => 'جاجیگا',
            self::JABAMA => 'جاباما',
            self::OTAGHAK => 'اتاقک',
            self::OTHER => 'سایر',
        ];
    }

    public static function label(?string $platform): string
    {
        if (! $platform) {
            return '—';
        }

        return self::labels()[$platform] ?? $platform;
    }

    public static function detectFromUrl(?string $url): ?string
    {
        $url = strtolower(trim((string) $url));

        if ($url === '') {
            return null;
        }

        if (str_contains($url, 'jajiga.com')) {
            return self::JAJIGA;
        }

        if (str_contains($url, 'jabama.com')) {
            return self::JABAMA;
        }

        if (str_contains($url, 'otaghak.com')) {
            return self::OTAGHAK;
        }

        return self::OTHER;
    }

    public static function extractRoomId(?string $platform, ?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '' || ! $platform) {
            return null;
        }

        return match ($platform) {
            self::JAJIGA => self::firstMatch($url, [
                '#jajiga\.com/room/(\d+)#i',
            ]),
            self::JABAMA => self::firstMatch($url, [
                '#jabama\.com/stay/([a-z0-9-]+)#i',
                '#jabama\.com/(?:room|place)/(\d+)#i',
            ]),
            self::OTAGHAK => self::firstMatch($url, [
                '#otaghak\.com/room/(\d+)#i',
            ]),
            default => null,
        };
    }

    private static function firstMatch(string $url, array $patterns): ?string
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
