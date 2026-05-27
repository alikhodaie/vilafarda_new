<?php

namespace App\Services;

use App\Models\Home;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Throwable;

class HomePhotoWebpEncoder
{
    /**
     * تصویر آپلود شده را می‌خواند، در صورت نیاز کوچک می‌کند و به WebP ذخیره می‌کند.
     *
     * @param  array{max_edge?: int, quality?: int}  $encodeOptions  خالی = تنظیمات کاور (ابعاد/کیفیت پیش‌فرض Home)
     * @return array{name: string, size: int, type: string}
     *
     * @throws \RuntimeException
     */
    public function storeOptimizedWebp(UploadedFile $file, string $relativeDirectory, array $encodeOptions = []): array
    {
        $dir = trim($relativeDirectory, '/');

        $sourcePath = $file->getRealPath();
        if ($sourcePath === false || $sourcePath === '') {
            $sourcePath = $file->getPathname();
        }

        $maxEdge = (int) ($encodeOptions['max_edge'] ?? Home::IMAGE_MAX_LONG_EDGE);
        $quality = (int) ($encodeOptions['quality'] ?? Home::IMAGE_WEBP_QUALITY);

        $cleanupRaster = [];

        try {
            try {
                $image = Image::read($sourcePath);
            } catch (Throwable $e) {
                if (! HeicToRasterConverter::looksLikeHeic($file, $sourcePath)) {
                    throw new \RuntimeException(
                        'این فایل به‌عنوان تصویر خوانده نشد؛ در صورت نیاز آن را به JPG یا PNG تبدیل کنید.',
                        0,
                        $e
                    );
                }

                $converted = HeicToRasterConverter::convertToTempJpeg($sourcePath);
                $cleanupRaster = $converted['cleanup'];
                $image = Image::read($converted['path']);
            }

            if ($image->isAnimated()) {
                $image->removeAnimation(0);
            }

            $image->scaleDown($maxEdge, $maxEdge);

            $filename = Str::random(32).'.webp';
            $relativePath = $dir.'/'.$filename;

            Storage::makeDirectory($dir);

            $absolute = Storage::path($relativePath);

            $image->toWebp($quality)->save($absolute);

            if (! is_file($absolute) || filesize($absolute) < 1) {
                throw new \RuntimeException(
                    'فایل WebP روی دیسک ذخیره نشد؛ دسترسی نوشتن در پوشهٔ public (دیسک public-folder) یا افزونهٔ GD با پشتیبانی WebP را بررسی کنید.'
                );
            }

            $size = (int) filesize($absolute);

            return [
                'name' => $filename,
                'size' => $size,
                'type' => 'image/webp',
            ];
        } finally {
            foreach ($cleanupRaster as $tmpPath) {
                @unlink($tmpPath);
            }
        }
    }

    /**
     * @param  array{max_edge?: int, quality?: int}  $encodeOptions
     * @return array{name: string, size: int, type: string}
     */
    public function storeOptimizedJpeg(UploadedFile $file, string $relativeDirectory, array $encodeOptions = []): array
    {
        $dir = trim($relativeDirectory, '/');

        $sourcePath = $file->getRealPath();
        if ($sourcePath === false || $sourcePath === '') {
            $sourcePath = $file->getPathname();
        }

        $maxEdge = (int) ($encodeOptions['max_edge'] ?? 1200);
        $quality = (int) ($encodeOptions['quality'] ?? 80);

        $cleanupRaster = [];

        try {
            try {
                $image = Image::read($sourcePath);
            } catch (Throwable $e) {
                if (! HeicToRasterConverter::looksLikeHeic($file, $sourcePath)) {
                    throw new \RuntimeException(
                        'این فایل به‌عنوان تصویر خوانده نشد؛ در صورت نیاز آن را به JPG یا PNG تبدیل کنید.',
                        0,
                        $e
                    );
                }

                $converted = HeicToRasterConverter::convertToTempJpeg($sourcePath);
                $cleanupRaster = $converted['cleanup'];
                $image = Image::read($converted['path']);
            }

            if ($image->isAnimated()) {
                $image->removeAnimation(0);
            }

            $image->scaleDown($maxEdge, $maxEdge);

            $filename = Str::random(32).'.jpg';
            $relativePath = $dir.'/'.$filename;

            Storage::disk('public-folder')->makeDirectory($dir);

            $absolute = Storage::disk('public-folder')->path($relativePath);

            $image->toJpeg($quality)->save($absolute);

            if (! is_file($absolute) || filesize($absolute) < 1) {
                throw new \RuntimeException('فایل JPG مدرک روی دیسک ذخیره نشد.');
            }

            return [
                'name' => $filename,
                'size' => (int) filesize($absolute),
                'type' => 'image/jpeg',
            ];
        } finally {
            foreach ($cleanupRaster as $tmpPath) {
                @unlink($tmpPath);
            }
        }
    }
}
