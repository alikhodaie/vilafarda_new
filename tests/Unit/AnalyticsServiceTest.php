<?php

namespace Tests\Unit;

use App\Services\AnalyticsService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use PHPUnit\Framework\TestCase as BaseTestCase;

class AnalyticsServiceTest extends BaseTestCase
{
    public function test_sanitize_rejects_invalid_measurement_id(): void
    {
        $this->assertSame('', AnalyticsService::sanitizeMeasurementIdInput('UA-123456-1'));
        $this->assertSame('', AnalyticsService::sanitizeMeasurementIdInput('not-an-id'));
    }

    public function test_sanitize_accepts_valid_measurement_id(): void
    {
        $this->assertSame('G-ABC123XYZ', AnalyticsService::sanitizeMeasurementIdInput('g-abc123xyz'));
    }

    public function test_should_not_track_admin_routes(): void
    {
        $request = $this->requestNamed('admin.dashboard', '/admin');

        $this->assertFalse(AnalyticsService::shouldTrackWithId('G-TEST12345', $request));
        $this->assertTrue(SeoService::shouldNoindexRoute('admin.dashboard'));
    }

    public function test_should_track_public_routes_when_id_configured(): void
    {
        $request = $this->requestNamed('main.index', '/');

        $this->assertTrue(AnalyticsService::shouldTrackWithId('G-TEST12345', $request));
    }

    public function test_should_track_payment_callback_route(): void
    {
        $request = $this->requestNamed('main.call-back', '/call-back');

        $this->assertTrue(AnalyticsService::shouldTrackWithId('G-TEST12345', $request));
    }

    public function test_should_not_track_dashboard_routes(): void
    {
        $request = $this->requestNamed('dashboard.rents.index', '/dashboard/rents');

        $this->assertFalse(AnalyticsService::shouldTrackWithId('G-TEST12345', $request));
    }

    public function test_should_not_track_when_no_measurement_id(): void
    {
        $request = $this->requestNamed('main.index', '/');

        $this->assertFalse(AnalyticsService::shouldTrackWithId(null, $request));
    }

    private function requestNamed(string $name, string $uri): Request
    {
        $request = Request::create($uri, 'GET');
        $request->setRouteResolver(function () use ($request, $name, $uri) {
            return (new Route('GET', $uri, []))->name($name)->bind($request);
        });

        return $request;
    }
}
