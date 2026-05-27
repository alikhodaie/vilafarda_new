<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSeoSettings extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $defaults = [
            'seo:default-description' => 'رزرو آنلاین ویلا، سوئیت و اقامتگاه در سراسر ایران. مقایسه قیمت، تصاویر واقعی و رزرو امن.',
            'seo:index-meta-description' => '',
            'seo:homes-meta-description' => 'جستجو و رزرو ویلا، سوئیت و اقامتگاه با فیلتر شهر، تاریخ و قیمت.',
            'seo:articles-meta-description' => 'مقالات و راهنمای سفر، اجاره اقامتگاه و نکات رزرو.',
            'seo:about-meta-description' => '',
            'seo:contact-meta-description' => '',
            'seo:privacy-meta-description' => '',
            'seo:faq-meta-description' => '',
            'seo:submit-home-meta-description' => '',
            'seo:google-site-verification' => '',
            'seo:ga4-measurement-id' => '',
            'seo:default-og-image' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Setting::query()->whereIn('key', [
            'seo:default-description',
            'seo:index-meta-description',
            'seo:homes-meta-description',
            'seo:articles-meta-description',
            'seo:about-meta-description',
            'seo:contact-meta-description',
            'seo:privacy-meta-description',
            'seo:faq-meta-description',
            'seo:submit-home-meta-description',
            'seo:google-site-verification',
            'seo:ga4-measurement-id',
            'seo:default-og-image',
        ])->delete();
    }
}
