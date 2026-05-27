<?php

namespace App\Rules;

use App\Services\HeicToRasterConverter;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

/**
 * پذیرش JPEG/PNG/WebP/GIF/BMP و همچنین HEIC/HEIF با پسوند یا MIME شناخته‌شده
 * (MIME برای HEIC در بسیاری از مرورگرها خالی است.)
 */
class HomeRasterImageUpload implements Rule
{
    /** @var string */
    protected $invalidUploadDetail = '';

    /** @var array<int, string> */
    protected static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'heic', 'heif'];

    /** @var array<int, string> */
    protected static $allowedMimeExact = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
        'image/heic',
        'image/heif',
        'image/heif-sequence',
    ];

    /**
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $this->invalidUploadDetail = '';

        if (! $value instanceof UploadedFile) {
            return false;
        }

        if (! $value->isValid()) {
            try {
                $this->invalidUploadDetail = $value->getErrorMessage();
            } catch (\Throwable $e) {
                $this->invalidUploadDetail = '';
            }

            return false;
        }

        $ext = strtolower((string) $value->getClientOriginalExtension());
        if (in_array($ext, self::$allowedExtensions, true)) {
            return true;
        }

        $mime = strtolower((string) $value->getMimeType());
        if ($mime !== '' && in_array($mime, self::$allowedMimeExact, true)) {
            return true;
        }

        $path = $value->getRealPath();
        if ($path === false || $path === '') {
            $path = $value->getPathname();
        }

        return HeicToRasterConverter::looksLikeHeic($value, $path);
    }

    public function message(): string
    {
        if ($this->invalidUploadDetail !== '') {
            return 'آپلود فایل کامل نشد ('.$this->invalidUploadDetail.'). محدودیت PHP (upload_max_filesize، post_max_size) یا حجم/تعداد فایل‌ها را بررسی کنید؛ برای artisan serve از «composer run serve» استفاده کنید.';
        }

        return 'فقط فایل تصویری مجاز است (مثلاً JPG، PNG یا WebP). برای HEIC پس از آپلود سرور باید بتواند آن را بخواند؛ در غیر این صورت ابتدا به JPG تبدیل کنید.';
    }
}
