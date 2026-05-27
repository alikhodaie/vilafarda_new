<?php

namespace App\Http\View\Composers;

use App\Services\JsonLdService;
use App\Services\SeoService;
use Illuminate\View\View;

class SeoComposer
{
    public function compose(View $view): void
    {
        $data = $view->getData();

        if (! array_key_exists('seo', $data)) {
            $seo = SeoService::resolve(request(), $data);
            $view->with('seo', $seo);

            if (! array_key_exists('documentTitle', $data)) {
                $view->with('documentTitle', $seo['document_title'] ?? config('app.name'));
            }
        }

        if (! array_key_exists('jsonLd', $data)) {
            $view->with('jsonLd', JsonLdService::resolve(request(), $data));
        }
    }
}
