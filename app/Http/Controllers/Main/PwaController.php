<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\PwaManifestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PwaController extends Controller
{
    public function manifest(PwaManifestService $manifest): JsonResponse
    {
        return response()
            ->json($manifest->toArray(), 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Content-Type', 'application/manifest+json; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function serviceWorker(): Response
    {
        $path = resource_path('pwa/sw.js');
        if (! is_file($path)) {
            abort(404);
        }

        return response(file_get_contents($path), 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Service-Worker-Allowed' => '/',
        ]);
    }

    public function offline(): Response
    {
        return response()
            ->view('main.offline', [
                'appName' => siteName(),
                'themeColor' => PwaManifestService::THEME_COLOR,
            ])
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
