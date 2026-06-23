<?php

use App\Models\Setting;
use App\Services\FaviconProcessor;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $source = storage_path('app/favicon-source-512.png');
        if (! is_file($source)) {
            return;
        }

        $stored = Setting::rawValue('app:favicon');
        if ($stored) {
            Setting::deleteFaviconVariants($stored);
        }

        $newFilename = FaviconProcessor::processFromPath($source);
        Setting::setValue('app:favicon', $newFilename);
    }

    public function down(): void
    {
        // بدون بازگشت خودکار — favicon قبلی را از پنل یا favicon:import تنظیم کنید.
    }
};
