<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\FaviconProcessor;
use Illuminate\Console\Command;

class FaviconStatus extends Command
{
    protected $signature = 'favicon:status';

    protected $description = 'بررسی وضعیت favicon (دیتابیس، فایل روی دیسک، deploy)';

    public function handle(): int
    {
        $storedCache = setting('app:favicon');
        $storedDb = Setting::rawValue('app:favicon');
        $stored = $storedDb ?: $storedCache;
        $gitHead = $this->gitHead();

        $this->info('=== وضعیت deploy ===');
        $this->line('Git HEAD: '.($gitHead ?: 'نامشخص'));
        $this->line('FaviconProcessor: '.(class_exists(FaviconProcessor::class) ? 'موجود ✓' : 'وجود ندارد ✗ (pull ناقص)'));
        $this->line('GD (imagecreatefrompng): '.(function_exists('imagecreatefrompng') ? 'فعال ✓' : 'غیرفعال ✗'));

        $this->newLine();
        $this->info('=== تنظیمات دیتابیس ===');
        $this->line('DB مستقیم: '.($storedDb ?: '(خالی)'));
        $this->line('کش setting(): '.($storedCache ?: '(خالی)'));

        if ($storedDb && $storedCache !== $storedDb) {
            $this->warn('کش با DB هم‌خوان نیست — php artisan cache:clear یا favicon:repair');
        }

        if (! $stored) {
            $latest = $this->latestFaviconOnDisk();
            if ($latest) {
                $this->warn('فایل روی دیسک هست ('.$latest.') ولی DB خالی — اجرا کنید: php artisan favicon:repair');
            } else {
                $this->warn('در دیتابیس favicon ثبت نشده.');
            }

            return self::SUCCESS;
        }

        $mainPath = public_path(Setting::FILE_PATH.ltrim($stored, '/'));
        $this->newLine();
        $this->info('=== فایل روی دیسک ===');
        $this->line('مسیر: '.$mainPath);
        $this->line('وجود فایل اصلی: '.(is_file($mainPath) ? 'بله ✓' : 'خیر ✗'));

        foreach (Setting::FAVICON_SIZES as $size) {
            $variant = Setting::faviconVariantFilename($stored, $size);
            $variantPath = $variant
                ? public_path(Setting::FILE_PATH.ltrim($variant, '/'))
                : null;
            $exists = $variantPath && is_file($variantPath);
            $this->line("  {$size}px ({$variant}): ".($exists ? 'موجود ✓' : 'نیست ✗'));
        }

        $this->newLine();
        $this->line('URL: '.(settingFilePath('app:favicon') ?: '(ندارد)'));
        $this->line('favicon.ico: '.url('/favicon.ico'));

        if (! is_file($mainPath)) {
            $this->warn('نام فایل در DB هست ولی روی دیسک نیست — احتمالاً خطای دسترسی نوشتن هنگام آپلود.');
        }

        if (! is_writable(public_path(Setting::FILE_PATH))) {
            $this->error('پوشه public/files/setting قابل نوشتن نیست — آپلود favicon شکست می‌خورد.');
        }

        return self::SUCCESS;
    }

    private function latestFaviconOnDisk(): ?string
    {
        $files = glob(public_path(Setting::FILE_PATH).'favicon-*-512.png') ?: [];
        if ($files === []) {
            return null;
        }

        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        return basename($files[0]);
    }

    private function gitHead(): ?string
    {
        $headFile = base_path('.git/HEAD');
        if (! is_file($headFile)) {
            return null;
        }

        $head = trim((string) file_get_contents($headFile));
        if (str_starts_with($head, 'ref: ')) {
            $ref = base_path('.git/'.substr($head, 5));
            if (is_file($ref)) {
                return trim((string) file_get_contents($ref));
            }
        }

        return strlen($head) >= 7 ? substr($head, 0, 7) : $head;
    }
}
