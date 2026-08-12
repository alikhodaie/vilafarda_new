<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class ExternalCalendarSyncCooldown
{
    public const SECONDS = 60;

    public static function cacheKey(?int $userId = null, ?string $ip = null): string
    {
        $userId = $userId ?? auth()->id();
        $ip = $ip ?? request()->ip();

        return 'calendar-sync:manual-cooldown:'.($userId ?: 'guest').':'.($ip ?: 'unknown');
    }

    public static function remainingSeconds(?int $userId = null, ?string $ip = null): int
    {
        $availableAt = Cache::get(self::cacheKey($userId, $ip));

        if (! $availableAt) {
            return 0;
        }

        return max(0, (int) $availableAt - time());
    }

    public static function isReady(?int $userId = null, ?string $ip = null): bool
    {
        return self::remainingSeconds($userId, $ip) === 0;
    }

    public static function mark(?int $userId = null, ?string $ip = null, ?int $seconds = null): void
    {
        $seconds = $seconds ?? self::SECONDS;
        $availableAt = time() + $seconds;

        Cache::put(self::cacheKey($userId, $ip), $availableAt, $seconds);
    }

    public static function availableAtTimestamp(?int $userId = null, ?string $ip = null): ?int
    {
        $remaining = self::remainingSeconds($userId, $ip);

        if ($remaining <= 0) {
            return null;
        }

        return time() + $remaining;
    }
}
