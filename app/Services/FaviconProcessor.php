<?php

namespace App\Services;

use App\Models\Setting;
use Intervention\Image\Laravel\Facades\Image;

class FaviconProcessor
{
    /** @var list<int> */
    public const SIZES = [48, 192, 512];

    public static function processFromPath(string $sourcePath): string
    {
        $baseName = 'favicon-'.time();

        foreach (self::SIZES as $size) {
            $variant = Image::read($sourcePath);
            if ($variant->isAnimated()) {
                $variant->removeAnimation(0);
            }
            $variant->cover($size, $size);

            $variantFilename = $baseName.'-'.$size.'.png';
            $absolute = self::absolutePath($variantFilename);
            $variant->toPng()->save($absolute);
            self::applyCircularMask($absolute, $size);
        }

        return $baseName.'-512.png';
    }

    public static function absolutePath(string $filename): string
    {
        return \Illuminate\Support\Facades\Storage::disk(Setting::FILE_DISK)
            ->path(Setting::FILE_PATH.$filename);
    }

    public static function resolveSourcePath(?string $storedFilename): ?string
    {
        if ($storedFilename === null || $storedFilename === '') {
            return null;
        }

        $candidates = [$storedFilename];

        if (preg_match('/^favicon-\d+-\d+\.png$/', $storedFilename)) {
            $candidates[] = preg_replace('/-\d+\.png$/', '-512.png', $storedFilename);
        }

        foreach (array_unique($candidates) as $filename) {
            $path = self::absolutePath($filename);
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    private static function applyCircularMask(string $pngPath, int $size): void
    {
        if (! function_exists('imagecreatefrompng')) {
            return;
        }

        $image = @imagecreatefrompng($pngPath);
        if ($image === false) {
            return;
        }

        imagesavealpha($image, true);
        imagealphablending($image, false);

        $center = $size / 2;
        $radiusSq = $center * $center;
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);

        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $dx = $x - $center + 0.5;
                $dy = $y - $center + 0.5;
                if (($dx * $dx + $dy * $dy) > $radiusSq) {
                    imagesetpixel($image, $x, $y, $transparent);
                }
            }
        }

        imagepng($image, $pngPath);
        imagedestroy($image);
    }
}
