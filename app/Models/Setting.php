<?php

namespace App\Models;

use App\Jobs\CompressImageJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    # region Const
    const CACHE_KEY = 'setting';
    const FILE_PATH = 'files/setting/';
    const FILE_DISK = 'public-folder';
    const IMAGE_MAX_LONG_EDGE = 1200;
    const IMAGE_WEBP_QUALITY = 85;
    # endregion

    # region Methods
    public static function getFilePath(string $filename): string
    {
        return asset(self::FILE_PATH.$filename);
    }

    public static function rasterImageUrl(?string $value, string $subdir): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (str_starts_with($value, '/')) {
            return asset(ltrim($value, '/'));
        }

        return asset(self::FILE_PATH.$subdir.'/'.ltrim($value, '/'));
    }

    public static function rasterImageFilename(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return basename(parse_url($value, PHP_URL_PATH) ?: '') ?: null;
        }

        return basename($value);
    }

    public static function deleteFile(string $filename): bool
    {
        Storage::disk(self::FILE_DISK)->delete(self::FILE_PATH.$filename);

        return true;
    }

    /**
     * ذخیره لوگو بدون فشرده‌سازی — فقط PNG واقعی (با آلفا/شفافیت).
     */
    public static function saveLogoFile(UploadedFile $file, string $filename, string $field = 'logo'): string
    {
        $realType = @exif_imagetype($file->getRealPath());
        if ($realType !== IMAGETYPE_PNG) {
            throw ValidationException::withMessages([
                $field => 'لوگو باید فایل PNG باشد. در InShot گزینه Save as PNG را انتخاب کنید (نه JPG).',
            ]);
        }

        self::purgeLogoCompressJobs();

        Storage::disk(self::FILE_DISK)->putFileAs(self::FILE_PATH, $file, $filename);

        $savedPath = Storage::disk(self::FILE_DISK)->path(self::FILE_PATH.$filename);
        if (@exif_imagetype($savedPath) !== IMAGETYPE_PNG) {
            Storage::disk(self::FILE_DISK)->delete(self::FILE_PATH.$filename);
            throw ValidationException::withMessages([
                $field => 'فایل لوگو پس از ذخیره به JPEG تبدیل شد. روی سرور: git pull، php artisan queue:restart و php-fpm را restart کنید.',
            ]);
        }

        return $filename;
    }

    /**
     * حذف jobهای قدیمی فشرده‌سازی که لوگو را به JPEG تبدیل می‌کنند.
     */
    public static function purgeLogoCompressJobs(): void
    {
        if (config('queue.default') !== 'database') {
            return;
        }

        try {
            DB::table('jobs')
                ->where(function ($query) {
                    $query->where('payload', 'like', '%logo.png%')
                        ->orWhere('payload', 'like', '%logo-light.png%');
                })
                ->delete();
        } catch (\Throwable $e) {
            // queue table may not exist
        }
    }

    public static function saveFile(UploadedFile $file, string $filename = '', $extra_path = '', bool $compress = true): string
    {
        $path = self::FILE_PATH.$extra_path;

        $name = ($filename)
            ? basename($file->storeAs($path, $filename, self::FILE_DISK))
            : basename($file->store($path, self::FILE_DISK));

        if ($compress && in_array(pathinfo($name, PATHINFO_EXTENSION), [
            'png',
            'jpg',
            'jpeg',
            'jfif',
            'pjp',
            'pjpeg',
            'webp',
            'gif',
            'bmp',
        ], true)) {
            dispatch(new CompressImageJob($path.$name));
        }

        return $name;
    }

    /**
     * ذخیره تصویر رستری: تبدیل به WebP + resize روی سرور (بعد از آپلود).
     */
    public static function saveRasterImage(UploadedFile $file, string $extra_path = ''): string
    {
        $dir = trim(self::FILE_PATH.$extra_path, '/');

        $stored = app(\App\Services\HomePhotoWebpEncoder::class)->storeOptimizedWebp($file, $dir, [
            'max_edge' => self::IMAGE_MAX_LONG_EDGE,
            'quality' => self::IMAGE_WEBP_QUALITY,
        ]);

        return $stored['name'];
    }
    # endregion
}
