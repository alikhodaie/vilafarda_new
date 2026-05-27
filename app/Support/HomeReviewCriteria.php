<?php

namespace App\Support;

class HomeReviewCriteria
{
    public const ACCURACY = 'accuracy';

    public const HOST_INTERACTION = 'host_interaction';

    public const CLEANLINESS = 'cleanliness';

    public const LOCATION = 'location';

    public const TIMELY_DELIVERY = 'timely_delivery';

    public const PRICE_QUALITY = 'price_quality';

    public const KEYS = [
        self::ACCURACY,
        self::HOST_INTERACTION,
        self::CLEANLINESS,
        self::LOCATION,
        self::TIMELY_DELIVERY,
        self::PRICE_QUALITY,
    ];

    public static function labels(): array
    {
        return [
            self::ACCURACY => 'صحت اطلاعات ثبت شده اقامتگاه در سایت',
            self::HOST_INTERACTION => 'نحوه برخورد و تعامل میزبان',
            self::CLEANLINESS => 'میزان پاکیزگی اقامتگاه',
            self::LOCATION => 'موقعیت مکانی اقامتگاه',
            self::TIMELY_DELIVERY => 'تحویل به‌موقع اقامتگاه',
            self::PRICE_QUALITY => 'تناسب قیمت به کیفیت',
        ];
    }

    public static function shortLabels(): array
    {
        return [
            self::ACCURACY => 'صحت مطالب',
            self::HOST_INTERACTION => 'رفتار میزبان',
            self::CLEANLINESS => 'نظافت اقامتگاه',
            self::LOCATION => 'موقعیت مکانی',
            self::TIMELY_DELIVERY => 'تحویل به‌موقع',
            self::PRICE_QUALITY => 'قیمت و کیفیت',
        ];
    }

    public static function shortLabelFor(string $key): string
    {
        return self::shortLabels()[$key] ?? self::labelFor($key);
    }

    public static function icons(): array
    {
        return [
            self::ACCURACY => 'bi-house-check',
            self::HOST_INTERACTION => 'bi-person-check',
            self::CLEANLINESS => 'bi-brush',
            self::LOCATION => 'bi-geo-alt',
            self::TIMELY_DELIVERY => 'bi-key',
            self::PRICE_QUALITY => 'bi-tag',
        ];
    }

    public static function iconFor(string $key): string
    {
        return self::icons()[$key] ?? 'bi-star';
    }

    public static function distributionMood(int $stars): string
    {
        return match ($stars) {
            5 => '😄',
            4 => '🙂',
            3 => '😐',
            2 => '🙁',
            default => '😞',
        };
    }

    public static function labelFor(string $key): string
    {
        return self::labels()[$key] ?? match ($key) {
            'facilities' => 'تحویل به‌موقع اقامتگاه',
            default => $key,
        };
    }

    public static function average(array $scores): int
    {
        $values = array_values(array_filter($scores, fn ($value) => is_numeric($value) && (int) $value >= 1));

        if ($values === []) {
            return 0;
        }

        return (int) round(array_sum($values) / count($values));
    }

    public static function averageFloat(array $scores): float
    {
        $values = array_values(array_filter($scores, fn ($value) => is_numeric($value) && (int) $value >= 1));

        if ($values === []) {
            return 0.0;
        }

        return round(array_sum($values) / count($values), 1);
    }
}
