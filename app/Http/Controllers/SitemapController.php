<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /** @var SitemapService */
    private $sitemap;

    public function __construct(SitemapService $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    public function index(): Response
    {
        return $this->xmlResponse($this->sitemap->indexXml());
    }

    public function staticPages(): Response
    {
        return $this->xmlResponse($this->sitemap->staticXml());
    }

    public function landings(): Response
    {
        return $this->xmlResponse($this->sitemap->landingsXml());
    }

    public function homes(): Response
    {
        return $this->xmlResponse($this->sitemap->homesXml());
    }

    public function articles(): Response
    {
        return $this->xmlResponse($this->sitemap->articlesXml());
    }

    private function xmlResponse(string $content): Response
    {
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
