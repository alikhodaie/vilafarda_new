<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Home;
use App\Models\LandingPage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    private const CACHE_TTL_SECONDS = 3600;

    /** @var array<string, array{changefreq: string, priority: string}> */
    private const STATIC_PAGES = [
        'main.index' => ['changefreq' => 'daily', 'priority' => '1.0'],
        'main.homes.index' => ['changefreq' => 'daily', 'priority' => '0.9'],
        'main.articles.index' => ['changefreq' => 'weekly', 'priority' => '0.8'],
        'main.about-us' => ['changefreq' => 'monthly', 'priority' => '0.6'],
        'main.contact-us' => ['changefreq' => 'monthly', 'priority' => '0.6'],
        'main.faq' => ['changefreq' => 'monthly', 'priority' => '0.6'],
        'main.privacy' => ['changefreq' => 'yearly', 'priority' => '0.4'],
        'main.submit.home' => ['changefreq' => 'monthly', 'priority' => '0.7'],
    ];

    public function indexXml(): string
    {
        return Cache::remember('sitemap:index', self::CACHE_TTL_SECONDS, function () {
            $sitemaps = [
                ['loc' => route('sitemap.static'), 'lastmod' => $this->freshTimestamp()],
                ['loc' => route('sitemap.landings'), 'lastmod' => $this->landingsLastModified()],
                ['loc' => route('sitemap.homes'), 'lastmod' => $this->homesLastModified()],
                ['loc' => route('sitemap.articles'), 'lastmod' => $this->articlesLastModified()],
            ];

            return $this->buildSitemapIndex($sitemaps);
        });
    }

    public function staticXml(): string
    {
        return Cache::remember('sitemap:static', self::CACHE_TTL_SECONDS, function () {
            $urls = [];

            foreach (self::STATIC_PAGES as $routeName => $meta) {
                $urls[] = [
                    'loc' => route($routeName),
                    'lastmod' => $this->freshTimestamp(),
                    'changefreq' => $meta['changefreq'],
                    'priority' => $meta['priority'],
                ];
            }

            return $this->buildUrlSet($urls);
        });
    }

    public function homesXml(): string
    {
        return Cache::remember('sitemap:homes', self::CACHE_TTL_SECONDS, function () {
            $urls = [];

            Home::query()
                ->active()
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('id')
                ->chunkById(500, function ($homes) use (&$urls) {
                    foreach ($homes as $home) {
                        $urls[] = [
                            'loc' => route('main.homes.show', $home),
                            'lastmod' => $this->formatLastmod($home->updated_at),
                            'changefreq' => 'weekly',
                            'priority' => '0.8',
                        ];
                    }
                });

            return $this->buildUrlSet($urls);
        });
    }

    public function landingsXml(): string
    {
        return Cache::remember('sitemap:landings', self::CACHE_TTL_SECONDS, function () {
            $urls = [];

            LandingPage::query()
                ->active()
                ->select(['slug', 'updated_at'])
                ->orderBy('sort')
                ->orderBy('id')
                ->chunkById(200, function ($pages) use (&$urls) {
                    foreach ($pages as $page) {
                        $urls[] = [
                            'loc' => $page->url,
                            'lastmod' => $this->formatLastmod($page->updated_at),
                            'changefreq' => 'weekly',
                            'priority' => '0.85',
                        ];
                    }
                });

            return $this->buildUrlSet($urls);
        });
    }

    public function articlesXml(): string
    {
        return Cache::remember('sitemap:articles', self::CACHE_TTL_SECONDS, function () {
            $urls = [];

            Article::query()
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('id')
                ->chunkById(500, function ($articles) use (&$urls) {
                    foreach ($articles as $article) {
                        $urls[] = [
                            'loc' => $article->link,
                            'lastmod' => $this->formatLastmod($article->updated_at),
                            'changefreq' => 'monthly',
                            'priority' => '0.6',
                        ];
                    }
                });

            return $this->buildUrlSet($urls);
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('sitemap:index');
        Cache::forget('sitemap:static');
        Cache::forget('sitemap:landings');
        Cache::forget('sitemap:homes');
        Cache::forget('sitemap:articles');
    }

    private function landingsLastModified(): string
    {
        $updatedAt = LandingPage::query()->active()->max('updated_at');

        return $this->formatLastmod($updatedAt);
    }

    /**
     * @param  array<int, array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>  $urls
     */
    private function buildUrlSet(array $urls): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($urls as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>'.$this->escape($url['loc']).'</loc>';

            if (! empty($url['lastmod'])) {
                $lines[] = '    <lastmod>'.$this->escape($url['lastmod']).'</lastmod>';
            }

            if (! empty($url['changefreq'])) {
                $lines[] = '    <changefreq>'.$this->escape($url['changefreq']).'</changefreq>';
            }

            if (isset($url['priority'])) {
                $lines[] = '    <priority>'.$this->escape($url['priority']).'</priority>';
            }

            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines);
    }

    /**
     * @param  array<int, array{loc: string, lastmod?: string}>  $sitemaps
     */
    private function buildSitemapIndex(array $sitemaps): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($sitemaps as $sitemap) {
            $lines[] = '  <sitemap>';
            $lines[] = '    <loc>'.$this->escape($sitemap['loc']).'</loc>';

            if (! empty($sitemap['lastmod'])) {
                $lines[] = '    <lastmod>'.$this->escape($sitemap['lastmod']).'</lastmod>';
            }

            $lines[] = '  </sitemap>';
        }

        $lines[] = '</sitemapindex>';

        return implode("\n", $lines);
    }

    private function homesLastModified(): string
    {
        $updatedAt = Home::query()->active()->max('updated_at');

        return $this->formatLastmod($updatedAt);
    }

    private function articlesLastModified(): string
    {
        $updatedAt = Article::query()->max('updated_at');

        return $this->formatLastmod($updatedAt);
    }

    private function freshTimestamp(): string
    {
        return Carbon::now()->toAtomString();
    }

    /**
     * @param  mixed  $value
     */
    private function formatLastmod($value): string
    {
        if ($value === null || $value === '') {
            return $this->freshTimestamp();
        }

        return Carbon::parse($value)->toAtomString();
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
