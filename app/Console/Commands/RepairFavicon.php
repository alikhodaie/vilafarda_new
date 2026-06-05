<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class RepairFavicon extends Command
{
    protected $signature = 'favicon:repair {--dry-run : فقط نمایش بدون ذخیره}';

    protected $description = 'ثبت آخرین فایل favicon روی دیسک در دیتابیس (وقتی فایل هست ولی DB خالی است)';

    public function handle(): int
    {
        $dir = public_path(Setting::FILE_PATH);
        $pattern = $dir.'favicon-*-512.png';
        $files = glob($pattern) ?: [];

        if ($files === []) {
            $this->error('هیچ فایل favicon-*-512.png در public/files/setting پیدا نشد.');

            return self::FAILURE;
        }

        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));
        $latest = basename($files[0]);
        $currentDb = Setting::rawValue('app:favicon');
        $currentCache = setting('app:favicon');

        $this->line('آخرین فایل روی دیسک: '.$latest);
        $this->line('DB (مستقیم): '.($currentDb ?: '(خالی)'));
        $this->line('کش setting(): '.($currentCache ?: '(خالی)'));

        if ($currentDb === $latest) {
            $this->info('DB از قبل درست است.');
            if ($currentCache !== $latest) {
                forgetSettingsCache();
                $this->warn('کش پاک شد — دوباره ادمین را رفرش کنید.');
            }

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn('حالت dry-run — مقدار پیشنهادی: '.$latest);

            return self::SUCCESS;
        }

        Setting::setValue('app:favicon', $latest);
        $this->info('app:favicon در دیتابیس به '.$latest.' تنظیم شد.');

        return self::SUCCESS;
    }
}
