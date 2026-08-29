<?php

namespace Tests\Feature;

use App\Models\Setting;
use Tests\TestCase;

class PwaRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        cache()->forever(Setting::CACHE_KEY, collect([
            'app:site-name' => 'رنت ناب',
            'seo:default-description' => 'رزرو آنلاین ویلا',
        ]));
    }

    public function test_manifest_route_returns_webmanifest(): void
    {
        $response = $this->get('/manifest.webmanifest');

        $response->assertOk();
        $this->assertStringContainsString('application/manifest+json', (string) $response->headers->get('Content-Type'));
        $response->assertJsonPath('display', 'standalone');
        $response->assertJsonPath('name', 'رنت ناب');
        $response->assertJsonPath('start_url', '/?utm_source=pwa&utm_medium=manifest');
    }

    public function test_service_worker_is_served_from_root(): void
    {
        $response = $this->get('/sw.js');

        $response->assertOk();
        $response->assertHeader('Service-Worker-Allowed', '/');
        $this->assertStringContainsString('rentnaab-static-v1', $response->getContent());
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_offline_page_is_available(): void
    {
        $this->get('/offline')
            ->assertOk()
            ->assertSee('اتصال اینترنت برقرار نیست')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
}
