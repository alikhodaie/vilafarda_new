<?php

namespace App\Support;

use App\Models\Home;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

final class UploadValidation
{
    /**
     * خروجی $request->file('gallery') یا 'images' گاهی یک UploadedFile تکی است و گاهی آرایه.
     *
     * @param  mixed  $input
     * @return array<int, UploadedFile>
     */
    public static function normalizeFileList($input): array
    {
        if ($input === null) {
            return [];
        }

        if ($input instanceof SymfonyUploadedFile) {
            return [UploadedFile::createFromBase($input)];
        }

        if (! is_array($input)) {
            return [];
        }

        $files = [];
        foreach ($input as $item) {
            foreach (self::normalizeFileList($item) as $file) {
                $files[] = $file;
            }
        }

        return array_values($files);
    }

    /**
     * @throws ValidationException
     */
    public static function validateUploadedOrFail(?UploadedFile $file, string $attributeKey, string $persianLabel): void
    {
        if ($file === null || ! $file instanceof UploadedFile) {
            return;
        }

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                $attributeKey => self::failedUploadMessage($persianLabel, $file),
            ]);
        }
    }

    /**
     * خطاهای چند فایل گالری را یک‌جا برمی‌گرداند.
     *
     * @param  array<int, UploadedFile>  $files
     * @return array<string, string>
     */
    public static function collectInvalidGalleryMessages(array $files, string $attributePrefix = 'gallery'): array
    {
        $messages = [];

        foreach ($files as $index => $file) {
            if (! $file instanceof UploadedFile || $file->isValid()) {
                continue;
            }
            $messages[$attributePrefix.'.'.$index] = self::failedUploadMessage(
                'تصویر گالری (شماره '.($index + 1).')',
                $file
            );
        }

        return $messages;
    }

    public static function failedUploadMessage(string $persianLabel, UploadedFile $file): string
    {
        $detail = '';
        try {
            $detail = $file->getErrorMessage();
        } catch (\Throwable $e) {
            //
        }

        $uploadMax = ini_get('upload_max_filesize') ?: '?';
        $postMax = ini_get('post_max_size') ?: '?';

        return sprintf(
            'بارگذاری «%s» انجام نشد. محدودیت فعلی PHP: upload_max_filesize=%s، post_max_size=%s. اگر چند فایل با هم می‌فرستید، مجموع حجم درخواست نباید از post_max_size بیشتر شود. برای لوکال: composer run serve (نه php artisan serve)؛ یا در php.ini لامپ همین مقادیر را بالا ببرید.%s',
            $persianLabel,
            $uploadMax,
            $postMax,
            $detail !== '' ? ' ('.$detail.')' : ''
        );
    }

    /**
     * هر فایل گالری را جداگانه پردازش می‌کند؛ خطای یک فایل مانع ذخیرهٔ بقیه نمی‌شود.
     *
     * @param  mixed  $rawFiles  خروجی file('gallery') یا file('images')
     * @return array<int, string>
     */
    public static function appendGalleryImagesBestEffort(Home $home, $rawFiles, string $itemLabel = 'تصویر گالری'): array
    {
        $files = self::normalizeFileList($rawFiles);
        if ($files === []) {
            return [];
        }

        $warnings = [];
        $rule = new HomeRasterImageUpload();
        $maxKb = (int) Home::MAX_IMAGE_SIZE;

        foreach ($files as $index => $file) {
            $num = $index + 1;
            $label = $itemLabel.' (شماره '.$num.')';

            if (! $file instanceof UploadedFile) {
                continue;
            }

            if (! $file->isValid()) {
                $warnings[] = self::failedUploadMessage($label, $file);

                continue;
            }

            if ($file->getSize() > $maxKb * 1024) {
                $warnings[] = sprintf(
                    '%s: حجم فایل از حد مجاز (%s کیلوبایت) بیشتر است.',
                    $label,
                    number_format($maxKb)
                );

                continue;
            }

            if (! $rule->passes('gallery.'.$index, $file)) {
                $warnings[] = $label.': '.$rule->message();

                continue;
            }

            try {
                $home->addImage($file);
            } catch (\Throwable $e) {
                $warnings[] = $label.': '.$e->getMessage();

                continue;
            }
        }

        return $warnings;
    }
}
