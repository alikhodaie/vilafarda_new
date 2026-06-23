<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\FaviconProcessor;
use Illuminate\Console\Command;

class ImportFavicon extends Command
{
    protected $signature = 'favicon:import
                            {path? : مسیر فایل PNG یا ICO (پیش‌فرض: storage/app/favicon-source-512.png)}
                            {--keep-old : نسخه‌های قبلی را حذف نکن}';

    protected $description = 'آپلود favicon از فایل روی دیسک (نسخه‌های ۴۸/۱۹۲/۵۱۲ + ثبت در دیتابیس)';

    public function handle(): int
    {
        $path = $this->argument('path') ?: storage_path('app/favicon-source-512.png');

        if (! is_file($path)) {
            $this->error('فایل پیدا نشد: '.$path);

            return self::FAILURE;
        }

        $stored = setting('app:favicon');

        try {
            $newFilename = FaviconProcessor::processFromPath($path);
        } catch (\Throwable $e) {
            $this->error('پردازش favicon شکست خورد: '.$e->getMessage());

            return self::FAILURE;
        }

        if ($stored && ! $this->option('keep-old') && $newFilename !== $stored) {
            Setting::deleteFaviconVariants($stored);
        }

        Setting::setValue('app:favicon', $newFilename);

        $this->info('Favicon ثبت شد: '.$newFilename);
        foreach (Setting::FAVICON_SIZES as $size) {
            $variant = Setting::faviconVariantFilename($newFilename, $size);
            $variantPath = public_path(Setting::FILE_PATH.ltrim((string) $variant, '/'));
            $this->line("  {$size}px: ".(is_file($variantPath) ? '✓' : '✗'));
        }
        $this->line('favicon.ico: '.url('/favicon.ico'));

        return self::SUCCESS;
    }
}
