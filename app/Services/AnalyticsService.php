<?php

namespace App\Services;

use Illuminate\Http\Request;

class AnalyticsService
{
    /** صفحات noindex که همچنان برای تبدیل (مثلاً پرداخت) باید GA4 داشته باشند */
    private const TRACKABLE_NOINDEX_ROUTES = [
        'main.call-back',
    ];

    public static function sanitizeMeasurementIdInput(?string $input): string
    {
        $id = strtoupper(trim((string) $input));

        if ($id === '' || ! preg_match('/^G-[A-Z0-9]+$/', $id)) {
            return '';
        }

        return $id;
    }

    public static function measurementId(): ?string
    {
        $fromSetting = self::sanitizeMeasurementIdInput(setting('seo:ga4-measurement-id', ''));
        if ($fromSetting !== '') {
            return $fromSetting;
        }

        $fromEnv = self::sanitizeMeasurementIdInput(config('services.google.ga4_measurement_id'));

        return $fromEnv !== '' ? $fromEnv : null;
    }

    public static function shouldTrack(?Request $request = null): bool
    {
        return self::shouldTrackWithId(self::measurementId(), $request);
    }

    public static function shouldTrackWithId(?string $measurementId, ?Request $request = null): bool
    {
        if ($measurementId === null || $measurementId === '') {
            return false;
        }

        $request ??= request();
        $routeName = $request->route()?->getName();

        if ($routeName !== null && in_array($routeName, self::TRACKABLE_NOINDEX_ROUTES, true)) {
            return true;
        }

        return ! SeoService::shouldNoindexRoute($routeName);
    }
}
