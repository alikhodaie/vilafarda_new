<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\FaviconProcessor;
use Illuminate\Console\Command;

class ReprocessFavicon extends Command
{
    protected $signature = 'favicon:reprocess';

    protected $description = 'پردازش مجدد favicon فعلی (نسخه‌های ۴۸/۱۹۲/۵۱۲ + ماسک دایره‌ای)';

    public function handle(): int
    {
        $stored = setting('app:favicon');
        $sourcePath = FaviconProcessor::resolveSourcePath($stored);

        if ($sourcePath === null) {
            $this->error('فایل favicon پیدا نشد. ابتدا از پنل ادمین آپلود کنید.');

            return self::FAILURE;
        }

        Setting::deleteFaviconVariants($stored);
        $newFilename = FaviconProcessor::processFromPath($sourcePath);

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
