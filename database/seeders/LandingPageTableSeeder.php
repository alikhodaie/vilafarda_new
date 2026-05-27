<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Home;
use App\Models\LandingPage;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LandingPageTableSeeder extends Seeder
{
    public function run(): void
    {
        $ramsar = City::query()->where('name', 'رامسر')->first();
        $tehranCity = City::query()->where('name', 'تهران')->first();
        $tehranProvince = Province::query()->where('name', 'تهران')->first();

        if ($ramsar) {
            LandingPage::query()->updateOrCreate(
                ['slug' => 'اجاره-ویلا-رامسر'],
                [
                    'title' => 'اجاره ویلا در رامسر',
                    'meta_title' => 'اجاره ویلا در رامسر | رزرو آنلاین',
                    'meta_description' => 'بهترین ویلاها و اقامتگاه‌های رامسر برای اجاره روزانه. مقایسه قیمت، امکانات و رزرو امن آنلاین.',
                    'intro' => '<p>رامسر یکی از محبوب‌ترین مقاصد ساحلی شمال ایران است. در این صفحه ویلاها و اقامتگاه‌های ویلایی رامسر را با امکان رزرو آنلاین مشاهده می‌کنید.</p>',
                    'province_id' => $ramsar->province_id,
                    'city_id' => $ramsar->id,
                    'home_type' => Home::VILAIY,
                    'city_only' => true,
                    'is_active' => true,
                    'sort' => 1,
                    'faq' => [
                        [
                            'question' => 'بهترین زمان اجاره ویلا در رامسر چه فصلی است؟',
                            'answer' => 'تابستان و تعطیلات پرتقاضاتر است؛ برای قیمت مناسب‌تر می‌توانید فصل‌های کم‌تقاضا را در نظر بگیرید.',
                        ],
                    ],
                ]
            );
        }

        if ($tehranCity && $tehranProvince) {
            LandingPage::query()->updateOrCreate(
                ['slug' => 'اجاره-سوئیت-تهران'],
                [
                    'title' => 'اجاره سوئیت در تهران',
                    'meta_title' => 'اجاره سوئیت در تهران | اقامت کوتاه‌مدت',
                    'meta_description' => 'اجاره سوئیت و اقامتگاه مبله در تهران برای سفر کاری و تفریح. لیست به‌روز با قیمت و امکانات.',
                    'intro' => '<p>سوئیت‌های مبله تهران گزینه‌ای مناسب برای اقامت کوتاه‌مدت هستند. در ادامه سوئیت‌های فعال تهران را ببینید و آنلاین رزرو کنید.</p>',
                    'province_id' => $tehranProvince->id,
                    'city_id' => $tehranCity->id,
                    'home_type' => Home::SWIIT,
                    'city_only' => true,
                    'is_active' => true,
                    'sort' => 2,
                ]
            );
        }
    }
}
