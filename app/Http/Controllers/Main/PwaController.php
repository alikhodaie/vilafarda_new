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

    public function assetLinks(): JsonResponse
    {
        $package = (string) config('pwa.android_package', 'com.vilafarda.app');
        $fingerprints = config('pwa.sha256_fingerprints', []);
        if (! is_array($fingerprints)) {
            $fingerprints = [];
        }

        $payload = [[
            'relation' => ['delegate_permission/common.handle_all_urls'],
            'target' => [
                'namespace' => 'android_app',
                'package_name' => $package,
                'sha256_cert_fingerprints' => array_values($fingerprints),
            ],
        ]];

        return response()
            ->json($payload, 200, [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            ->header('Content-Type', 'application/json; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function apk()
    {
        $filename = (string) config('pwa.apk_filename', 'vilafarda.apk');
        $path = public_path('app/'.$filename);
        if (! is_file($path)) {
            abort(404);
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
