<?php

namespace Tests\Unit;

use App\Models\Setting;
use App\Services\PwaManifestService;
use Tests\TestCase;

class PwaManifestServiceTest extends TestCase
{
    public function test_manifest_contains_required_pwa_fields(): void
    {
        cache()->forever(Setting::CACHE_KEY, collect([
            'app:site-name' => 'رنت ناب',
            'seo:default-description' => 'توضیح تست PWA',
        ]));

        $manifest = (new PwaManifestService())->toArray();

        $this->assertSame('رنت ناب', $manifest['name']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['scope']);
        $this->assertSame(PwaManifestService::THEME_COLOR, $manifest['theme_color']);
        $this->assertSame('fa', $manifest['lang']);
        $this->assertSame('rtl', $manifest['dir']);
        $this->assertStringContainsString('utm_source=pwa', $manifest['start_url']);
        $this->assertSame([], $manifest['icons']);
        $this->assertArrayNotHasKey('shortcuts', $manifest);
    }

    public function test_short_name_is_truncated(): void
    {
        cache()->forever(Setting::CACHE_KEY, collect([
            'app:site-name' => 'نام بسیار طولانی برای سایت اجاره',
        ]));

        $manifest = (new PwaManifestService())->toArray();

        $this->assertLessThanOrEqual(12, mb_strlen($manifest['short_name']));
    }
}
