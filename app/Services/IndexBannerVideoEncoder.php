<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * ویدئوی بنر صفحه اصلی: تبدیل به MP4 (H.264) با کیفیت مناسب وب.
 */
final class IndexBannerVideoEncoder
{
    public const OUTPUT_FILENAME = 'banners.mp4';

    /** حداکثر حجم فایل ورودی قبل از فشرده‌سازی (بایت) */
    public const MAX_UPLOAD_BYTES = 157286400; // 150 MB

    /** اگر از مرورگر MP4 با کیفیت مناسب آمده، دوباره فشرده نکن (جلوگیری از افت کیفیت دوباره) */
    private const PRESERVE_MP4_MAX_BYTES = 31457280; // 30 MB

    private const PRESERVE_MP4_MIN_BYTES = 512000; // 500 KB

    private const MAX_WIDTH = 1920;

    private const MAX_HEIGHT = 1080;

    /** CRF پایین‌تر = کیفیت بالاتر (۱۸–۲۳ برای بنر با کیفیت خوب) */
    private const CRF = 20;

    private const MAX_FPS = 30;

    private const PRESET = 'medium';

    private const PROCESS_TIMEOUT = 300;

    /**
     * @throws \RuntimeException
     */
    public function storeOptimizedMp4(UploadedFile $file): string
    {
        if ($this->shouldPreserveUploadedMp4($file)) {
            return $this->storePreservedMp4($file);
        }

        $ffmpeg = (new ExecutableFinder())->find('ffmpeg');
        if ($ffmpeg === null) {
            throw new \RuntimeException(
                'ابزار ffmpeg روی سرور نصب نیست؛ بدون آن فشرده‌سازی ویدئو بنر انجام نمی‌شود.'
            );
        }

        $sourcePath = $file->getRealPath();
        if ($sourcePath === false || $sourcePath === '') {
            $sourcePath = $file->getPathname();
        }

        if (! is_readable($sourcePath)) {
            throw new \RuntimeException('فایل ویدئوی آپلودشده خوانا نیست.');
        }

        $size = $file->getSize();
        if ($size > self::MAX_UPLOAD_BYTES) {
            throw new \RuntimeException(sprintf(
                'حجم ویدئو (%s مگابایت) از حد مجاز آپلود (%s مگابایت) بیشتر است.',
                number_format($size / 1048576, 1),
                number_format(self::MAX_UPLOAD_BYTES / 1048576, 0)
            ));
        }

        $relativeOutput = Setting::FILE_PATH.self::OUTPUT_FILENAME;
        $disk = Storage::disk('public-folder');
        $disk->makeDirectory(trim(Setting::FILE_PATH, '/'));

        $finalPath = $disk->path($relativeOutput);
        $tempPath = $finalPath.'.encoding.mp4';

        if (is_file($tempPath)) {
            @unlink($tempPath);
        }

        $scale = sprintf(
            "scale=w='min(%d,iw)':h='min(%d,ih)':force_original_aspect_ratio=decrease,scale=trunc(iw/2)*2:trunc(ih/2)*2",
            self::MAX_WIDTH,
            self::MAX_HEIGHT
        );

        $process = new Process([
            $ffmpeg,
            '-y',
            '-i', $sourcePath,
            '-an',
            '-vf', $scale,
            '-r', (string) self::MAX_FPS,
            '-c:v', 'libx264',
            '-preset', self::PRESET,
            '-crf', (string) self::CRF,
            '-pix_fmt', 'yuv420p',
            '-movflags', '+faststart',
            $tempPath,
        ]);
        $process->setTimeout(self::PROCESS_TIMEOUT);

        try {
            $process->mustRun();
        } catch (\Throwable $e) {
            @unlink($tempPath);

            throw new \RuntimeException(
                'فشرده‌سازی ویدئو انجام نشد. فرمت را بررسی کنید یا ویدئوی کوتاه‌تری آپلود کنید.',
                0,
                $e
            );
        }

        if (! is_file($tempPath) || filesize($tempPath) < 1024) {
            @unlink($tempPath);

            throw new \RuntimeException('خروجی فشرده‌سازی ویدئو معتبر نیست.');
        }

        if (! @rename($tempPath, $finalPath)) {
            @unlink($tempPath);

            throw new \RuntimeException('ذخیرهٔ ویدئوی فشرده‌شده روی سرور انجام نشد.');
        }

        @chmod($finalPath, 0664);

        return self::OUTPUT_FILENAME;
    }

    private function shouldPreserveUploadedMp4(UploadedFile $file): bool
    {
        if (strtolower((string) $file->getClientOriginalExtension()) !== 'mp4') {
            return false;
        }

        $mime = strtolower((string) ($file->getMimeType() ?? ''));
        if ($mime !== '' && $mime !== 'video/mp4' && strpos($mime, 'mp4') === false) {
            return false;
        }

        $size = (int) $file->getSize();

        return $size >= self::PRESERVE_MP4_MIN_BYTES && $size <= self::PRESERVE_MP4_MAX_BYTES;
    }

    /**
     * MP4 از پیش بهینه‌شده در مرورگر — بدون بازفشرده‌سازی.
     *
     * @throws \RuntimeException
     */
    private function storePreservedMp4(UploadedFile $file): string
    {
        $relativeOutput = Setting::FILE_PATH.self::OUTPUT_FILENAME;
        $disk = Storage::disk('public-folder');
        $disk->makeDirectory(trim(Setting::FILE_PATH, '/'));

        $finalPath = $disk->path($relativeOutput);

        $sourcePath = $file->getRealPath() ?: $file->getPathname();
        if (! is_readable($sourcePath)) {
            throw new \RuntimeException('فایل ویدئوی آپلودشده خوانا نیست.');
        }

        if (! @copy($sourcePath, $finalPath)) {
            throw new \RuntimeException('ذخیرهٔ ویدئو روی سرور انجام نشد.');
        }

        @chmod($finalPath, 0664);

        return self::OUTPUT_FILENAME;
    }
}
