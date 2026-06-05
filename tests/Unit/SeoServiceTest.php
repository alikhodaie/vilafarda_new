<?php

namespace Tests\Unit;

use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function test_document_title_skips_brand_when_already_in_segment(): void
    {
        config(['app.name' => 'vilafarda']);

        $title = SeoService::documentTitle('اجاره ویلا استخردار | رزرو آنلاین ویلافردا');

        $this->assertSame('اجاره ویلا استخردار | رزرو آنلاین ویلافردا', $title);
        $this->assertStringNotContainsString('vilafarda', $title);
    }

    public function test_homes_index_detects_filtered_query(): void
    {
        $request = Request::create('/homes', 'GET', ['q' => ['مازندران']]);

        $this->assertTrue(SeoService::homesIndexIsFiltered($request));
    }

    public function test_homes_index_marks_pagination_as_filtered(): void
    {
        $request = Request::create('/homes', 'GET', ['page' => 2]);

        $this->assertTrue(SeoService::homesIndexIsFiltered($request));
    }

    public function test_homes_index_context_uses_search_terms_in_title(): void
    {
        $request = Request::create('/homes', 'GET', [
            'q' => ['مازندران'],
            'start_at' => '1405/03/17',
            'end_at' => '1405/03/22',
        ]);

        $homes = new LengthAwarePaginator([], 0, 6, 1);
        $context = SeoService::homesIndexContext($request, ['homes' => $homes]);

        $this->assertTrue($context['filtered']);
        $this->assertSame('جستجوی مازندران', $context['title_segment']);
        $this->assertStringContainsString('مازندران', $context['description']);
        $this->assertStringContainsString('1405/03/17', $context['description']);
    }
}
