<?php

namespace App\Services;

class PwaManifestService
{
    public const THEME_COLOR = '#D39D1A';

    public const BACKGROUND_COLOR = '#ffffff';

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $name = siteName();
        $description = trim((string) setting('seo:default-description', ''));
        if ($description === '') {
            $description = 'رزرو آنلاین ویلا، سوئیت و اقامتگاه در سراسر ایران.';
        }

        $icons = $this->icons();

        $manifest = [
            'id' => 'rentnaab-pwa-v1',
            'name' => $name,
            'short_name' => $this->shortName($name),
            'description' => $description,
            'lang' => 'fa',
            'dir' => 'rtl',
            'start_url' => '/?utm_source=pwa&utm_medium=manifest',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'theme_color' => self::THEME_COLOR,
            'background_color' => self::BACKGROUND_COLOR,
            'categories' => ['travel'],
            'icons' => $icons,
        ];

        if ($icons !== []) {
            $manifest['shortcuts'] = $this->shortcuts($icons[0]['src']);
        }

        return $manifest;
    }

    /**
     * @return list<array{src: string, sizes: string, type: string, purpose: string}>
     */
    private function icons(): array
    {
        $icons = [];

        foreach ([192, 512] as $size) {
            $url = settingFaviconUrl($size);
            if (! $url) {
                continue;
            }

            $icons[] = [
                'src' => $url,
                'sizes' => $size.'x'.$size,
                'type' => 'image/png',
                'purpose' => 'any',
            ];
            $icons[] = [
                'src' => $url,
                'sizes' => $size.'x'.$size,
                'type' => 'image/png',
                'purpose' => 'maskable',
            ];
        }

        return $icons;
    }

    /**
     * @return list<array{name: string, short_name: string, url: string, icons: list<array{src: string, sizes: string}>}>
     */
    private function shortcuts(string $iconSrc): array
    {
        $icon = [['src' => $iconSrc, 'sizes' => '192x192']];

        return [
            [
                'name' => 'جستجوی اقامتگاه',
                'short_name' => 'جستجو',
                'url' => '/homes?utm_source=pwa&utm_medium=shortcut',
                'icons' => $icon,
            ],
            [
                'name' => 'ثبت اقامتگاه',
                'short_name' => 'ثبت',
                'url' => '/submit/home?utm_source=pwa&utm_medium=shortcut',
                'icons' => $icon,
            ],
            [
                'name' => 'علاقه‌مندی‌ها',
                'short_name' => 'علاقه‌مندی',
                'url' => '/dashboard/favorites?utm_source=pwa&utm_medium=shortcut',
                'icons' => $icon,
            ],
            [
                'name' => 'تماس با ما',
                'short_name' => 'تماس',
                'url' => '/contact-us?utm_source=pwa&utm_medium=shortcut',
                'icons' => $icon,
            ],
        ];
    }

    private function shortName(string $name): string
    {
        $trimmed = trim($name);

        if (mb_strlen($trimmed) <= 12) {
            return $trimmed;
        }

        return mb_substr($trimmed, 0, 12);
    }
}
