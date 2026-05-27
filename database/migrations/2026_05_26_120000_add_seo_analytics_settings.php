<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSeoAnalyticsSettings extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Setting::query()->firstOrCreate(
            ['key' => 'seo:ga4-measurement-id'],
            ['value' => '']
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Setting::query()->where('key', 'seo:ga4-measurement-id')->delete();
    }
}
