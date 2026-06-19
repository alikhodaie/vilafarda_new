<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\FaviconProcessor;
use Illuminate\Console\Command;

class ReprocessFavicon extends Command
{
    protected $signature = 'favicon:reprocess';

    protected $description = 'پردازش مجدد favicon فعلی (نسخه‌های ۴۸/۱۹۲/۵۱۲ با پس‌زمینه یکدست برای گوگل)';

    public function handle(): int
    {
        $stored = setting('app:favicon');
        $sourcePath = FaviconProcessor::resolveSourcePath($stored);

        if ($sourcePath === null) {
            $this->error('فایل favicon پیدا نشد. ابتدا از پنل ادمین آپلود کنید.');

            return self::FAILURE;
        }

        $tempPath = sys_get_temp_dir().'/favicon-reprocess-'.basename($sourcePath);
        if (! copy($sourcePath, $tempPath)) {
            $this->error('کپی موقت فایل منبع ممکن نشد: '.$sourcePath);

            return self::FAILURE;
        }

        try {
            $newFilename = FaviconProcessor::processFromPath($tempPath);
        } catch (\Throwable $e) {
            $this->error('پردازش favicon شکست خورد: '.$e->getMessage());

            return self::FAILURE;
        } finally {
            @unlink($tempPath);
        }

        if ($newFilename !== $stored) {
            Setting::deleteFaviconVariants($stored);
        }

        Setting::query()->updateOrCreate(
            ['key' => 'app:favicon'],
            ['value' => $newFilename]
        );

        forgetSettingsCache();

        $this->info('Favicon با موفقیت پردازش شد: '.$newFilename);
        $this->line('کش مرورگر را پاک کنید (Ctrl+Shift+R) تا آیکون جدید در تب دیده شود.');

        return self::SUCCESS;
    }
}
