<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SmsTemplates
{
    public const CATEGORY_ORDERS = 'orders';

    public const CATEGORY_SCHEDULED = 'scheduled';

    public const CATEGORY_AUTH = 'auth';

    public const CATEGORY_HOMES = 'homes';

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
            self::CATEGORY_HOMES => __('title.sms_category_homes'),
            default => $category,
        };
    }

    public static function grouped(): Collection
    {
        return self::all()->groupBy('category');
    }

    public static function titleForPatternId(string $patternId): ?string
    {
        $template = self::findByPatternId($patternId);

        return $template['title'] ?? null;
    }

    public static function findByPatternId(string $patternId): ?array
    {
        if ($patternId === '') {
            return null;
        }

        return self::all()->first(
            fn (array $template) => (string) ($template['pattern_id'] ?? '') === (string) $patternId
        );
    }

    public static function findForInbox(?string $patternId, ?string $patternTitle = null): ?array
    {
        if ($patternId) {
            $byId = self::findByPatternId($patternId);
            if ($byId) {
                return $byId;
            }
        }

        $title = trim((string) $patternTitle);
        if ($title === '') {
            return null;
        }

        return self::all()->first(
            fn (array $template) => (string) ($template['title'] ?? '') === $title
        );
    }

    public static function isInboxVisible(string $patternId): bool
    {
        if ($patternId === 'bulk') {
            return true;
        }

        $template = self::findByPatternId($patternId);

        if ($template === null) {
            return true;
        }

        if (($template['category'] ?? '') === self::CATEGORY_AUTH) {
            return false;
        }

        return ! self::isAdminRecipient($template);
    }

    /**
     * @return array<int, string>
     */
    public static function hiddenInboxPatternIds(): array
    {
        return self::hiddenInboxTemplates()
            ->pluck('pattern_id')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function hiddenInboxTitles(): array
    {
        return self::hiddenInboxTemplates()
            ->pluck('title')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function isAdminRecipient(array $template): bool
    {
        return mb_strpos((string) ($template['recipient'] ?? ''), 'ادمین') !== false;
    }

    private static function hiddenInboxTemplates(): Collection
    {
        return self::all()->filter(function (array $template) {
            return ($template['category'] ?? '') === self::CATEGORY_AUTH
                || self::isAdminRecipient($template);
        });
    }
}
