<?php

namespace App\Support;

class PerformanceTier
{
    const EXCELLENT = 'excellent';
    const GOOD = 'good';
    const WEAK = 'weak';

    public static function fromPercent(?int $percent): ?string
    {
        if ($percent === null) {
            return null;
        }

        if ($percent >= 80) {
            return self::EXCELLENT;
        }

        if ($percent >= 50) {
            return self::GOOD;
        }

        return self::WEAK;
    }

    public static function fromRatingScore(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        if ($score >= 4) {
            return self::EXCELLENT;
        }

        if ($score >= 3) {
            return self::GOOD;
        }

        return self::WEAK;
    }

    public static function percentFromCounts(int $accepted, int $rejected): ?int
    {
        $total = $accepted + $rejected;

        if ($total === 0) {
            return null;
        }

        return (int) round($accepted / $total * 100);
    }

    public static function label(?string $tier): ?string
    {
        return match ($tier) {
            self::EXCELLENT => 'عالی',
            self::GOOD => 'خوب',
            self::WEAK => 'ضعیف',
            default => null,
        };
    }

    public static function color(?string $tier): string
    {
        return match ($tier) {
            self::EXCELLENT => 'success',
            self::GOOD => 'warning',
            self::WEAK => 'danger',
            default => 'secondary',
        };
    }

    public static function percentDisplay(?int $percent): ?string
    {
        return $percent !== null ? persianNumber($percent).'%' : null;
    }
}
