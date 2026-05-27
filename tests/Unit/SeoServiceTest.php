<?php

namespace Tests\Unit;

use App\Services\SeoService;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    public function test_document_title_appends_brand(): void
    {
        config(['app.name' => 'رنت ناب']);

        $title = SeoService::documentTitle('اجاره ویلا در رامسر');

        $this->assertStringEndsWith('| رنت ناب', $title);
        $this->assertStringContainsString('اجاره ویلا در رامسر', $title);
    }

    public function test_document_title_truncates_long_segment(): void
    {
        config(['app.name' => 'رنت ناب']);

        $long = str_repeat('ا', 80);
        $title = SeoService::documentTitle($long);

        $this->assertLessThanOrEqual(SeoService::TITLE_LIMIT + 5, mb_strlen($title));
    }

    public function test_document_title_returns_brand_when_segment_empty(): void
    {
        config(['app.name' => 'رنت ناب']);

        $this->assertSame('رنت ناب', SeoService::documentTitle(''));
    }
}
