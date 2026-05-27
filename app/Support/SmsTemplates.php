<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SmsTemplates
{
    public const CATEGORY_ORDERS = 'orders';

    public const CATEGORY_SCHEDULED = 'scheduled';

    public const CATEGORY_AUTH = 'auth';

    public static function all(): Collection
    {
        return collect(config('sms_templates', []))
            ->map(fn (array $template, string $key) => array_merge($template, ['key' => $key]))
            ->values();
    }

    public static function categoryLabel(string $category): string
    {
        return match ($category) {
            self::CATEGORY_ORDERS => __('title.sms_category_orders'),
            self::CATEGORY_SCHEDULED => __('title.sms_category_scheduled'),
            self::CATEGORY_AUTH => __('title.sms_category_auth'),
            default => $category,
        };
    }

    public static function grouped(): Collection
    {
        return self::all()->groupBy('category');
    }
}
