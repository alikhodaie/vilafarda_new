<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * GD معمولاً HEIC نمی‌خواند؛ این کلاس با Imagick یا ابزارهای سیستم به JPEG موقت تبدیل می‌کند.
 */
final class HeicToRasterConverter
{
    /**
     * آیا فایل احتمالاً HEIC/HEIF است (پسوند، MIME یا امضای باینری ftyp).
     */
    public static function looksLikeHeic(UploadedFile $file, string $sourcePath): bool
    {
        $ext = strtolower((string) $file->getClientOriginalExtension());
        if (in_array($ext, ['heic', 'heif'], true)) {
            return true;
        }

        $mime = strtolower((string) $file->getMimeType());
        if ($mime !== '' && (strpos($mime, 'heic') !== false || strpos($mime, 'heif') !== false)) {
            return true;
        }

        return self::looksLikeHeicPath($sourcePath);
    }

    /**
     * تشخیص HEIC/HEIF از روی مسیر فایل (بدون UploadedFile).
     */
    public static function looksLikeHeicPath(string $sourcePath): bool
    {
        if (! is_readable($sourcePath)) {
            return false;
        }

        $fh = @fopen($sourcePath, 'rb');
        if ($fh === false) {
            return false;
        }
        $head = fread($fh, 64);
        fclose($fh);
        if ($head === false || strlen($head) < 12) {
            return false;
        }

        if (strpos($head, 'ftyp') === false) {
            return false;
        }

        return self::headerLooksLikeHeic($head);
    }

    private static function headerLooksLikeHeic(string $head): bool
    {
        return strpos($head, 'heic') !== false
            || strpos($head, 'heix') !== false
            || strpos($head, 'hevc') !== false
            || strpos($head, 'heim') !== false
            || strpos($head, 'heis') !== false
            || strpos($head, 'mif1') !== false
            || strpos($head, 'msf1') !== false;
    }

    /**
     * HEIC/HEIF را به JPEG موقت تبدیل می‌کند.
     *
     * @return array{path: string, cleanup: array<int, string>}
     */
    public static function convertToTempJpeg(string $sourcePath): array
    {
        if (! is_readable($sourcePath)) {
            throw new \RuntimeException('فایل آپلودشده خوانا نیست.');
        }

        $jpegPath = tempnam(sys_get_temp_dir(), 'rn_heic_').'.jpg';
        $cleanup = [$jpegPath];

        if (self::convertWithImagickExtension($sourcePath, $jpegPath)) {
            return ['path' => $jpegPath, 'cleanup' => $cleanup];
        }

        @unlink($jpegPath);

        $jpegPath = tempnam(sys_get_temp_dir(), 'rn_heic_').'.jpg';
        $cleanup = [$jpegPath];

        if (self::convertWithImageMagickCli($sourcePath, $jpegPath)) {
            return ['path' => $jpegPath, 'cleanup' => $cleanup];
        }

        @unlink($jpegPath);

        $jpegPath = tempnam(sys_get_temp_dir(), 'rn_heic_').'.jpg';
        $cleanup = [$jpegPath];

        if (self::convertWithHeifConvertCli($sourcePath, $jpegPath)) {
            return ['path' => $jpegPath, 'cleanup' => $cleanup];
        }

        @unlink($jpegPath);

        throw new \RuntimeException(
            'تبدیل HEIC انجام نشد. روی سرور یکی از این‌ها را نصب کنید: '
            .'«php-imagick» با پشتیبانی HEIF، یا ImageMagick ۷ (دستور magick)، یا libheif (دستور heif-convert). '
            .'در اوبونتو نمونه: sudo apt install imagemagick libheif1 php-imagick — یا قبل از آپلود به JPG تبدیل کنید.'
        );
    }

    private static function convertWithImagickExtension(string $src, string $dest): bool
    {
        if (! extension_loaded('imagick')) {
            return false;
        }

        try {
            $im = new \Imagick();
            $im->readImage($src);
            $im->setImageFormat('jpeg');
            $im->setImageCompressionQuality(92);
            $im->stripImage();
            $im->writeImage($dest);
            $im->clear();
            $im->destroy();

            return is_file($dest) && filesize($dest) > 0;
        } catch (\Throwable $e) {
            @unlink($dest);

            return false;
        }
    }

    private static function convertWithImageMagickCli(string $src, string $dest): bool
    {
        $finder = new ExecutableFinder();

        foreach (['magick', 'convert'] as $name) {
            $bin = $finder->find($name);
            if ($bin === null) {
                continue;
            }

            $process = new Process([$bin, $src, $dest]);
            $process->setTimeout(180);

            try {
                $process->run();
                if ($process->isSuccessful() && is_file($dest) && filesize($dest) > 0) {
                    return true;
                }
            } catch (\Throwable $e) {
                //
            }

            @unlink($dest);
        }

        return false;
    }

    private static function convertWithHeifConvertCli(string $src, string $dest): bool
    {
        $finder = new ExecutableFinder();
        $bin = $finder->find('heif-convert');
        if ($bin === null) {
            return false;
        }

        $process = new Process([$bin, '-q', '92', $src, $dest]);
        $process->setTimeout(180);

        try {
            $process->run();

            return $process->isSuccessful() && is_file($dest) && filesize($dest) > 0;
        } catch (\Throwable $e) {
            @unlink($dest);

            return false;
        }
    }
}
