<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [
                'province_id' => 1,
                'name' => 'تبریز',
                'capital' => 1,
            ],
            [
                'province_id' => 1,
                'name' => 'مراغه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مرند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'میانه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شهر جدید سهند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بناب',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سراب',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'آبش احمد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'آچاچی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'آذرشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'آقکند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ابرغان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اتش بیگ',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اچاچی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اذغان (ازغان )',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اربط',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اردها',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ارسگنای سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ارموداق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اروق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اسبفروشان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اسکلو (اسگلو)',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اسکو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اغ زیارت',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اغچه ریش',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اغمیون',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'افیل',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اق براز',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اق منار',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'الانق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'القو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'امند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'اوشندل',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ایلخچی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'باسمنج',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بایقوت',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بخشایش',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بستان آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بناب جدید',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بناب مرند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'بیلوردی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'پورسخلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ترک',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ترکمانچای',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'تسوج',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'تیکمه داش',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'تیل',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'تیمورلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'جلفا',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'جوان قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'چول قشلاقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خاتون اباد-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خاروانا',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خاص اباد (خاصبان )',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خامنه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خانه برق قدیم (شورخانه ب',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خانیان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خداجو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خراجو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خسرو شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خسروشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خضرلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خلجان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خمارلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خواجه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'خوشه مهر (خواجه امیر)',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'داران-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'داش اتان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'داش بلاغ بازار',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'دانالو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'دوزدوزان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'دولت اباد-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ذاکرکندی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ذوالبین',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'رازلیق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'رحمانلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'روشت بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'زاوشت',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'زرنق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'زنوز',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'زوارق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سرای (سرای ده )',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سردرود',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سرند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سعیداباد-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سلطان اباد (س انمکزار',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سلوک',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سیس',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سیه رود',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'سیه کلان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شادبادمشایخ (پینه شلوا',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شبستر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شجاع',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شربیان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شرفخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شند آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شهرک صنعتی کاغذکنان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شیخدراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شیراز-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'شیرامین',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'صوفیان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'صومعه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'طوراغای (طوراغایی )',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'عاشقلو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'عجب شیر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'علویان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'علی ابادعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قدمگاه (بادام یار)',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قره آغاج',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قره بابا',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قره بلاغ-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قره چای حاج علی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قره چمن',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قلعه حسین اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قوچ احمد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'قویوجاق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کجوار',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کردکندی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کشکسرای',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کلوانق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کلیبر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کندرود',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کندوان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کنگاور-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کوزه کنان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'کهنمو',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'گل تپه-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'گلین قیه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'گوگان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'گوندوغدی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'لاریجان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'لاهیجان-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'لکلر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'لیلان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مایان سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مبارک شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ملکان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ممقان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مولان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مهربان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مهماندار',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'مینق',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'نصیرابادسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'نظرکهریزی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'نوجه مهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'وایقان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ورجوی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ورزقان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ورگهان',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هادیشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هرزندجدید (چای هرزند)',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هریس',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هشترود',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هفت چشمه-آذربایجان شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'هوراند',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'یامچی',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'یکان کهریز',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ینگجه',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'ینگی اسپران (سفیدان جد',
                'capital' => 0,
            ],
            [
                'province_id' => 1,
                'name' => 'یوزبند',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ارومیه',
                'capital' => 1,
            ],
            [
                'province_id' => 2,
                'name' => 'خوی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'میاندوآب',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'مهاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بوکان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سلماس',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'نقده',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ماکو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'آواجیق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ابگرم-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'احمدابادسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'احمدغریب',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اختتر',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'استران',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اسلام اباد-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اشنویه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اغ برزه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اقابیگ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اقبال',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اگریقاش',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'الی چین',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'امام کندی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اوزون دره علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'اوغول بیگ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ایبلو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ایواوغلی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'باراندوز',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'باروق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بازرگان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'باغچه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بدلان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بسطام-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بگتاش',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بلسورسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بهله',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بیکوس',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بیگم قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بیله وار',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'بیوران سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'پسوه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'پلدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'پیرانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'تازه شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'تک اغاج',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'تکاب',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'تمر',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'تویی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'جلدیان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'جوانمرد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'چورس',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'چهار برج',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'چهریق علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'چیانه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حاجی حسن',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حاجی کند',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حسن کندی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حسنلو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حمزه قاسم',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'حیدرباغی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'خاتون باغ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'خلیفان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'خورخوره-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'داراب-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دستجرد-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دلزی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دورباش',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ده شمس بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دیزج دول',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دیزج دیز',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'دیزج-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'راژان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'راهدانه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ربط',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'رحیم خان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ریحانلوی علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ریگ اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'زاویه سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'زرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'زمزیران',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'زیوه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سرنق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سرو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سنجی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سوگلی تپه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیاقول علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیاوان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیلوانه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیلوه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیمینه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیه باز',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'سیه چشمه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شاهوانه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شاهین دژ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شلماش',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شوط',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شیخ احمد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شیرین بلاغ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'شین اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'طلاتپه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'فیرورق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قرنقو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قره باغ',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قره تپه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قره ضیاء الدین',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قره قشلاق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قطور',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قم قشلاق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قوروق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قورول علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قوزلوی افشار',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'قوشچی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'کانسپی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'کاولان علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'کشاورز',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'کله کین',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'کهریزعجم',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گردکشانه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گل تپه قورمیش',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گلاز',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گلیجه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گوگ تپه خالصه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'گوگ تپه-آذربایجان غربی',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'للکلو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'لولکان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'محمد یار',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'محمود آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'مراکان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'مرگنلر',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ملاشهاب الدین',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ممکان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'موانا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'میاوق',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'میرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'نازک علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'نازلو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'نالوس',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'نوشین',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'وردان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'ولدیان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'هاچاسو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'هاشم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'هندوان',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'هنگ اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'هولاسو',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'یکشوه',
                'capital' => 0,
            ],
            [
                'province_id' => 2,
                'name' => 'یولاگلدی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اردبیل',
                'capital' => 1,
            ],
            [
                'province_id' => 3,
                'name' => 'پارس آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'خلخال',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'مشگین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گرمی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'نمین',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'بیله سوار',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گیوی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'آبی بیگلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اراللوی بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اردیموسی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اسلام آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اسلام اباد-اردبیل',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اصلاندوز',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'اق قباق علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'انجیرلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'انی علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'بران علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'برندق',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'بقراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'بودالالو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'پریخان',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'تازه کند',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'تازه کند انگوت',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'تازه کندجدید',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'ثمرین',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'جعفر آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'حمزه خانلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'خلفلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'خورخورسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'دیزج-اردبیل',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'رضی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'زهرا',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'سرعین',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'شورگل',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'شهرک غفاری',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'عنبران',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'فخرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'فیروزاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'قاسم کندی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'قره اغاج پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'قشلاق اغداش کلام',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'قصابه',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'قوشه سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'کلور',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'کورائیم',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گرده',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گنجوبه',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گوشلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'گوگ تپه-اردبیل',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'لاهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'لنبر',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'مرادلو',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'مهماندوست علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'نظرعلی بلاغی',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'ننه کران',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'نیر',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'هشتجین',
                'capital' => 0,
            ],
            [
                'province_id' => 3,
                'name' => 'هیر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اصفهان',
                'capital' => 1,
            ],
            [
                'province_id' => 4,
                'name' => 'شاهین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کاشان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نجف آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بهارستان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خمینی شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فولادشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شهرضا',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'آران و بیدگل',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اب شیرین',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ابریشم',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ابوزید آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ابیانه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اذان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اریسمان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اژیه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اسحق اباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اسفرجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اسکندری',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اسلام ابادموگویی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اشکستان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اشن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اشیان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اصغرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اغداش',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'افوس',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'امین اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'انارک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اورگان-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'اوره',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ایمانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بادرود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'باغ بهادران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'باغ ملک-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'باغشاد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بافران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'برزک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'برف انبار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بلان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بلطاق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بوئین میاندشت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بوئین و میاندشت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بهاران شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'بیاضه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پادگان اموزشی امام ص',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پالایشگاه اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پرزان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پلی اکریل',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پوده',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'پیر بکران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تاسیسات سدنکواباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تلک اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تودشک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تورزن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تیدجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تیران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تیرانچی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'تیکن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جندق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جوجیل',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جوزدان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جوشقان استرک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جوشقان قالی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'جوشقان و کامو',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چادگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چاه غلامرضارحیمی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چرمهین',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چشمه رحمان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چم نور',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چمگردان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چوپانان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'چهارراه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حاجی اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حبیب آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حسن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حسن اباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حسین اباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'حنا',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خالد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خسرواباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خشکرود-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خم پیچ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خوانسار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خوانسارک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خور',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خوراسگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خورزوق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خونداب',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'خیراباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'داران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دامنه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'درچه پیاز',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'درقه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دستجا',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دستگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دولت آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ده زیره',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ده نسا سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دهاقان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دهسرخ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دهق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'دیزیچه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'رباطاقاکمال',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'رحق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'رحمت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'رزوه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'رضوانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زازران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زاینده رود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زرنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زرنه-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زرین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زواره',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زیار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'زیباشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سازمان عمران زاینده رود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سده لنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سعادت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سعیداباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سفید شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سگزی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سمیرم',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سن سن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سنگ سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سولار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سهروفیروزان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'سین',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شاپورآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شرودان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شهر ابریشم',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شهراب-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'شهرک صنایع شیمیایی ر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'طالخونچه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'طرق رود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ظفرقند',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'عزیزاباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'عسگران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'علویجه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'علویچه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'غرغن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فتح اباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فرخی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فریدونشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فسخود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فلاورجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فوداز',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'فولادمبارکه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قرغن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قره بلطاق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قصرچم',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قلعه امیریه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قلعه سرخ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قمشلو',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قمصر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قهجاورستان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قهدریجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'قهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کاغذی',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کامو و چوگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کچومثقال',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کچوییه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کرچ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کرچگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کرسگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کرکوند',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کریم اباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کلوچان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کلهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کلیشاد و سودرجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کمشجه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کمشچه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کمه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کوچری',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کوشک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کوهپایه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کهرویه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کهریزسنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'کهنگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گرگاب',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گرموک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گز برخوار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گلپایگان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گلدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گلشن',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گلشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گورت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'گوگد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'لای بید',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مبارکه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'محمد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مرغ',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مزرعه صدر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مزیک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مشکات',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مشهدکاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مصیر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مقصودبیک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ملازجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'منطقه صنعتی محموداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'منظریه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'منوچهراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'موته',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مورچه خورت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مورک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'موغار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مهاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مهراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مهرجان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مهرگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'مهیار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'میراباد-اصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'میمه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نائین',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نشلج',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نصرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نطنز',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نوش آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نوگوران',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نهرخلج',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نهضت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نهوج',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نیاسر',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نیستانک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نیسیان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'نیک آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'وادقان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'وانشان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ورپشت',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ورزنه',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ورق',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ورنامخواست',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'وزوان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ومکان',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ونک',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'ویست',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'هرند',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'هست',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'همسار',
                'capital' => 0,
            ],
            [
                'province_id' => 4,
                'name' => 'همگین',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'کرج',
                'capital' => 1,
            ],
            [
                'province_id' => 5,
                'name' => 'فردیس',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'هشتگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'نظر آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'محمد شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'کمال شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'شهر جدید هشتگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'مشکین دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'آسارا',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'ادران',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'اشتهارد',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'تنکمان',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'چهارباغ',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'سولقان',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'طالقان',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'قزل حصار',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'کوهسار',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'کیانمهر',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'گرمدره',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'گلسار',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'مارلیک',
                'capital' => 0,
            ],
            [
                'province_id' => 5,
                'name' => 'ماهدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ایلام',
                'capital' => 1,
            ],
            [
                'province_id' => 6,
                'name' => 'مهران',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'دهلران',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ایوان',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'آبدانان',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'دره شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'سرابله',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ارکواز',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'آسمان آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'اب انار',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ارمو',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'بانویزه',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'بدره',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'بلاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'بلاوه تره سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'بیشه دراز',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'پاریاب',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'پهله',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'توحید',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چشمه شیرین',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چشمه کبود',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چم هندی',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چمن سیدمحمد',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چنارباشی',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'چوار',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'دشت عباس',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'دلگشا',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'دول کبودخوشادول',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'زرنه',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ژیور',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'سرآبله',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'سراب باغ',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'سراب کارزان',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'سیاه گل',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'شباب',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'شورابه ملک',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'شهرک اسلامیه',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'شهرک سرتنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'شهرک ولیعصر',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'صالح آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'علی اباد-ایلام',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'عین خوش',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'کلان',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'گنداب',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'گولاب',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'لومار',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'ماژین',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'مورموری',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'موسیان',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'مهر',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'میمه',
                'capital' => 0,
            ],
            [
                'province_id' => 6,
                'name' => 'هفت چشمه-ایلام',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بوشهر',
                'capital' => 1,
            ],
            [
                'province_id' => 7,
                'name' => 'برازجان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'جم',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بندر کنگان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بندر گناوه',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'خورموج',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'عسلویه',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بندر دیر',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'آبپخش',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'آبدان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'ابگرمک',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'امام حسن',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'انارستان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'اهرم',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بادوله',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بردخون',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بندر دیلم',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بندر ریگ',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بنک',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بنه گز',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'بوشکان',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'تنگ ارم',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'چغادک',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'چهارروستایی',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'خارک',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'دالکی',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'دلوار',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'دوراهک',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'ریز',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'سعد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'سیراف',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'شبانکاره',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'شنبه',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'شول',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'عالیشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'کاکی',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'کلمه',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'نخل تقی',
                'capital' => 0,
            ],
            [
                'province_id' => 7,
                'name' => 'وحدتیه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'تهران',
                'capital' => 1,
            ],
            [
                'province_id' => 8,
                'name' => 'اسلامشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهریار',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'اندیشه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ملارد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قدس',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'پردیس',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'پاکدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'آبعلی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ابباریک',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ابراهیم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'احمدآبادجانسپار',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'احمدابادمستوفی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ارجمند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'اسلام اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'اسماعیل آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'امیریه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ایجدان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'باغخواص',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'باغستان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'باقرشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'بومهن',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'پارچین',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'پرند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'پیشوا',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'جابان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'جاجرود(خسروآباد)',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'جعفرابادباقراف',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'جلیل اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'جواد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'چرمشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'چهاردانگه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حسن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حسن آباد فشافویه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حصارامیر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حصاربن',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حصارک بالا',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'حصارک پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'خاتون اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'خاورشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'خاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'خلازیر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'داوداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'درده',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'دماوند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'دهماسین',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'رباط کریم',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'رودهن',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'سبزدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'سربندان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'سعیدآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'سلطان اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'سه راه سنگی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شاطره',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شاهدشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شریف آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شمس اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شمشک',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهر جدید پرند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهر صنعتی پرند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهر قدس',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرصنعتی خرمدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک صنعتی چهاردانگه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک صنعتی خاوران',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک صنعتی گلگون',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک صنعتی نصیرشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک عباس آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'شهرک قلعه میر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'صالح آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'صالحیه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'صبا شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'صفادشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'طورقوزاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'عباس آباد علاقبند',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'عسگرابادعباسی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فردوسیه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فرودگاه امام خمینی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فرون آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فرون اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فشم',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فیروزبهرام',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'فیروزکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قاسم ابادشوراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قرچک',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قلعه خواجه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قلعه سین',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قلعه محمدعلی خان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قلعه نوخالصه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قمصر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قو,چ حصار',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قیام دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'قیامدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'کریم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'کلمه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'کهریزک',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'کیلان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'گردنه تنباکویی',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'گرمدره',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'گل تپه کبیر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'گلدسته',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'گلستان',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'لپه زنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'لم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'لواسان بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'لواسان کوچک',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'محمودابادپیرزاده',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'مرا',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'مرقدامام ره',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'مشا',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'مهرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'نسیم شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'نصیرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'نصیرشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'وحیدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'ورامین',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'وهن اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 8,
                'name' => 'هرانده',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'شهرکرد',
                'capital' => 1,
            ],
            [
                'province_id' => 9,
                'name' => 'بروجن',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'لردگان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'فرخ شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'فارسان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سامان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'آلونی',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'هفشجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'اردل',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'امام قیس',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'اورگان-چهارمحال و بختیاری',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'باباحیدر',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'بازفت',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'بلداجی',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'بن',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'پردنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'جونقان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'چلگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'چلیچه',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'چمن بید',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'چوله دان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'خراجی',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'دزک',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'دستناء',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'دشتک',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سرخون',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سردشت لردگان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سردشت-چهارمحال و بختیاری',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سفید دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سودجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'سورشجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'شلمزار',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'شهریاری',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'صمصامی',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'طاقانک',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'فرادنبه',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'کاج',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'کیان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'گل سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'گندمان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'گوجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'گهرو',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'مال خلیفه',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'مرغملک',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'منج',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'ناغان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'نافچ',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'نقنه',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'وردنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 9,
                'name' => 'هارونی',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'بیرجند',
                'capital' => 1,
            ],
            [
                'province_id' => 10,
                'name' => 'طبس',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'قائن',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'فردوس',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'نهبندان',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'بشرویه',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'سرایان',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'سربیشه',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'آرین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'آیسک',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'ارسک',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'اسدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'اسفدن',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'اسلامیه',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'بیهود',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'حاجی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'خضری دشت بیاض',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'خوسف',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'دیهوک',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'زهان',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'سه قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'شوسف',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'طبس مسینا',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'عشق آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'قاین',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'قهستان',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'گزو',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'گزیک',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'محمدشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'مود',
                'capital' => 0,
            ],
            [
                'province_id' => 10,
                'name' => 'نیمبلوک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مشهد',
                'capital' => 1,
            ],
            [
                'province_id' => 11,
                'name' => 'نیشابور',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سبزوار',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'تربت حیدریه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قوچان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کاشمر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گناباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'تربت جام',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ابدال اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'احمدآباد صولت',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ارداک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ازادوار',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ازاده',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'اسحق اباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'اسداباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'امامقلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'انابد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'انداده',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'اوندر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'باجگیران',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'باخرز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بار',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'باسفر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بایک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بجستان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بردسکن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'برزنون',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'برغمد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بزنگان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بقمج',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بلاشی اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بندقرا',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بنی تاک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'بیدخت',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'پس کمر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'تایباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'تقی اباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'تندک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'جزین',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'جغتای',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'جنت اباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'جنگل',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'جوزان-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چاپشلو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چاهک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چخماق',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چشمه گل',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چکنه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چمن اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'چناران',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'حسن اباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'حسن ابادلایین نو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'حکم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'خرو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'خلیل آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'خواف',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'خوجان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'داورزن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'درزاب',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'درگز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'درود',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'درونه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دستوران',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دوغایی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دوقارون',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دولت آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دهنو-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'دیزادیز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رادکان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'راه چمن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رباط سنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رباطجز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رباطسرپوشی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رشتخوار',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رضویه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رکن اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'روداب',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رودخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'رویینی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ریوش',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'زرغری',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'زرکک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'زیبد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سرخس',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سفید سنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سلامی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سلطان آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سلوگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سنگ بست',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سنگان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سیداباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'سیوکی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شادمهر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شامکان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شاندیز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ششتمد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شفیع',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شفیع اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شهر زو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شهرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شهرک زیندانلو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شهرکهنه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'شیرتپه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'صالح آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'طرقبه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'طوس سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'عبدل اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'عشق آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فدافن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فدیشه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فرخک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فرهاد گرد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فریمان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فیروزه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'فیض آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قاسم آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قدمگاه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قرقی سفلی (شهیدکاوه )',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قریه شرف',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قلعه اقاحسن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قلعه نو-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'قلندر آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کاخک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کاریز',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کاریزنو',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کاسف',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کامه سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کبودان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کپکان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کته شمشیرسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کچولی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کدکن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کرات',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کلات',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کندر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کندک لی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کنه بیست',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کورده',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'کوه سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گلبوی پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گلبهار',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گلمکان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گنبدلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گوش',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'گیسوربالا',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'لطف آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مبارکه-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'محمدتقی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'محموداباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مزدآوند',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مزینان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مژن اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مشکان-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مشهدریزه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'ملک آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'موسی اباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مهر-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'مهنه',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'میامی-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'میراباد-خراسان رضوی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نامن',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نریمانی سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نشتیفان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نصر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نصرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نقاب',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نوخندان',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نوده انقلاب',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'نیل شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'همت آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'یاقوتین جدید',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'یدک',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'یک لنگی علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 11,
                'name' => 'یونسی',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'بجنورد',
                'capital' => 1,
            ],
            [
                'province_id' => 12,
                'name' => 'شیروان',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'اسفراین',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'جاجرم',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'آشخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'گرمه',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'فاروج',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'راز',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'آوا',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'ایور',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'پیش قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'تیتکانلو',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'چناران شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'حصارگرمخان',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'خرق',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'درق',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'دوین',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'رباط-خراسان شمالی',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'رزق اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'زیارت',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'سنخواست',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'شوقان',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'صفی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'قاضی',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'قوشخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 12,
                'name' => 'لوجلی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'اهواز',
                'capital' => 1,
            ],
            [
                'province_id' => 13,
                'name' => 'دزفول',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بندر ماهشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'آبادان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بهبهان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'اندیمشک',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'خرمشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شوشتر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'آبژدان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'آزادی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'آغاجاری',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ابوحمیظه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'اروند کنار',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'اسیاب',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'الوان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'الهائی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'الهایی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ام الطمیر (سیدیوسف )',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'امام',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'امیدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ایذه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'باغ ملک',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بروایه یوسف',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بستان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بندر امام خمینی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بوزی سیف',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'بیدروبه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'پشت پیان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ترکالکی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'تشان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'تله زنگ پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'تنگ یک',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'جایزان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'جنت مکان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چغامیش',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چلون',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چم کلگه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چم گلک',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چمران',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چنارستان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'چوئبده',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'حر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'حسینیه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'حفاری شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'حمزه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'حمیدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'خسرجی راضی حمد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'خنافره',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'خواجوی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'دارخوین',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'درویشی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'دره بوری',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'دره تونم نمی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'دهدز',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'رامشیر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'رامهرمز',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'رفیع',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'رودزرد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'روستای عنبر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'زهره',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سالند',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سرداران',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سماله',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سوسنگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سیاه منصور',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'سیدعباس',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شادگان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شاوور',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شاه غالب ده ابراهیم',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شرافت',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شمس آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شوش',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شهر امام',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شهرک انصار',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شهرک بهرام',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شهرک نورمحمدی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'شیبان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'صالح شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'صفی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'صیدون',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'عبودی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'عرب حسن',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'علمه تیمورابوذرغفاری',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'عین دو',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'غیزانیه بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'فتح المبین',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'فیاضی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'قلعه تل',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'قلعه چنعان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'قلعه خواجه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کردستان بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کریت برومی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کلگه دره دو',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کوت سیدنعیم',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کوت عبدالله',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'کوشکک-خوزستان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'گاومیش اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'گتوند',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'گروه پدافندهوایی بهبها',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'گلگیر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'گوریه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'لالی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'مزرعه یک',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'مسجد سلیمان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'مشراگه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'مقاومت',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ملاثانی',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'منصوریه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'میانرود',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'میانکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'میداود',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'مینوشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'نفت سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'نهرابطر',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'نهرسلیم',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'ویس',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'هفتگل',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'هندیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'هویزه',
                'capital' => 0,
            ],
            [
                'province_id' => 13,
                'name' => 'یزدنو',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'زنجان',
                'capital' => 1,
            ],
            [
                'province_id' => 14,
                'name' => 'ابهر',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'خرمدره',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'قیدار',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'آب بر',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سلطانیه',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'ماه نشان',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'هیدج',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'ارمخانخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'ارمغانخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'اژدهاتو',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'اسفجین',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'اقبلاغ سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'اندابادعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'باش قشلاق',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'بوغداکندی',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'پری',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'چورزق',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'حلب',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'درام',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'درسجین',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'دستجرده',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'دندی',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'دولت اباد-زنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'زرین آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'زرین رود',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سجاس',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سعیداباد-زنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سنبل اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سونتو',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'سهرورد',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'صائین قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'قبله بلاغی',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'قره گل',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'قلتوق',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'کرسف',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'کهلا',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'کینه ورس',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'گرماب',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'گوزلدره',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'گیلوان',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'محموداباد-زنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'نوربهار',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'نیک پی',
                'capital' => 0,
            ],
            [
                'province_id' => 14,
                'name' => 'همایون',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سمنان',
                'capital' => 1,
            ],
            [
                'province_id' => 15,
                'name' => 'شاهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دامغان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'گرمسار',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مهدی شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ایوانکی',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سرخه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'شهمیرزاد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'آرادان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ابخوری',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ابراهیم اباد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ابرسیج',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ابگرم-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'احمداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'استانه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'اسداباد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'افتر',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'امیریه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'اهوان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ایستگاه میان دره',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'برم',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بسطام',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بکران',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بن کوه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بیابانک',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'بیارجمند',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'جام',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'جزن',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'جودانه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'چاشم',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'چهلدخترپادگان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'حسین ابادکوروس',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'خیراباد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'داوراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دربند',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'درجزین',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دروار',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دستجرد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دوزهیر',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'ده صوفیان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دهملا',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'دیباج',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'رودیان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'رویان-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'زمان اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سطوه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سلمرود',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سوداغلان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'سیداباد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'طرزه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'طرود',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'عبدالله ابادپایین',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'علا',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'علیان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'عمروان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'فرات',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'فرومد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'فولادمحله',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'قدرت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'قلعه نوخرقان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'قوشه',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کرداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کردوان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کرک',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کلاته',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کلاته خیج',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کلاته ملا',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'کهن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'گل رودبار',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'گلستانک',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'گیور',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'لاسجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'لجران',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مجن',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'محمداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مسیح اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'معدن نمک',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'معصوم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مغان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مندولک',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مومن اباد-سمنان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'مهماندوست',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'میامی',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'میغان',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'نردین',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'نظامی',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'نگارمن',
                'capital' => 0,
            ],
            [
                'province_id' => 15,
                'name' => 'هیکو',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'زاهدان',
                'capital' => 1,
            ],
            [
                'province_id' => 16,
                'name' => 'زابل',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ایرانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سراوان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'چاه بهار',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'خاش',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نیک شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کنارک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ادیمی',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'اسپکه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'اسفندک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'اسماعیل کلگ',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'افضل اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'انده قدیم',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ایرافشان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بالاقلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'باهوکلات',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'برجمیرگل',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بزمان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بمپور',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بنت',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بنجار',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'بیت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پارود',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پسابندر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پسکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پلان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پیپ',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پیرسهراب',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'پیشین',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'تخت عدالت',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'تلنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'تیموراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'جالق',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'جزینک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'جهان ابادعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'چابهار',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'چانف',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'چاهان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'حرمک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'خمک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'خواجه احمد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'دستگرد-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'دوست محمد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'دولت اباد-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ده پابید',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'راسک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'زرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'زراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'زهک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'زیرکدان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ژاله ای',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ساربوک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سرباز',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سرداب',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سردک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سکوهه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سنگان-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سوران',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سیادک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'سیرکان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'شگیم بالا',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'شهدادکهیر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'شهرک محمدشاه کرم',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'طیس',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'علی اکبر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'فنوج',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'قصر قند',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'قلعه نو-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کارواندر',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کتیج',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کرباسک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کشیک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کوشکوک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کوشه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'کوهک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'گشت',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'گلمورتی',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'گلوگاه-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'گمن',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'گوهرکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'لادیزعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'لوتک',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'محمد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'محمدان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'محمدی',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'محنت',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'مسکوتان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'مهرستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'میرجاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نازیل',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'ناصراباد-سیستان و بلوچستان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نصرت آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نگور',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نوراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نوک آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'نوک اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'هودیان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'هیچان',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'هیدوچ',
                'capital' => 0,
            ],
            [
                'province_id' => 16,
                'name' => 'هیرمند',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'شیراز',
                'capital' => 1,
            ],
            [
                'province_id' => 17,
                'name' => 'شهر جدید صدرا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مرودشت',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'جهرم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کازرون',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فسا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'داراب',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'لار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'آباده',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'آباده طشک',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اردکان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'ارسنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اسپاس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'استهبان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اسیر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اشکنان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'افزر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اقلید',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اکبراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'امام شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'امامزاده اسماعیل',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'انارستان-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اوز',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اهل',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'اهنگری',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'ایج',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'ایزد خواست',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'باب انار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بابامنیر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بالاده',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بانش',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بایگان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بنارویه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بندامیر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بندبست',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بنوان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بوانات',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'به جان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بهرغان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بهمن',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بیرم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'بیضا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'پاسگاه چنارراهدار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'پرین',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'تفیهان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'جره',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'جنت شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'جوکان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'جویم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'چمن مروارید',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'حاجی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'حسامی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'حسن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'حسین ابادرستم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'حکیم باشی نصف میان (بالا)',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خانه زنیان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خانیمن',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خاوران',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خرامه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خشت',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خنج',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خنجشت',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خور',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خوزی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خومه زار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'خیرابادتوللی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'داریان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دبیران',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'درب قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دژکرد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دنیان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دوبرجی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دوزه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دهرم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'دهفیش',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'راشک علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'رامجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'رستاق',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'رونیز',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'زاهد شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'زرقان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سده',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سروستان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سروو',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سعادت شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سلطان آباد-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سلطان شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سورمق',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'سیدان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'ششده',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'شوریجه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'شهر پیر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'شهرمیان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'صحرارود',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'صغاد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'صفا شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'طسوج',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'علامرودشت',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'عماد ده',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فتح اباد-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فدامی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فراشبند',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فیروزآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'فیشور',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قائمیه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قادرآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قاسم ابادسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قره بلاغ',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قطاربنه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قطب آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قطرویه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'قیر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کارزین',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کامفیروز',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کره ای',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کلاتون',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کلانی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کم جان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کمارج مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کمهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کنار تخته',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوپن',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوشک بیدک',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوشک قاضی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوشک-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوشکک-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کوهنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'کهنه',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'گراش',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'گله دار',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'گویم',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'لامرد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'لای حنا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'لپوئی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'لطیفی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مادرسلیمان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مادوان-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مانیان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'ماه سالاری',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مبارک آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مزایجان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مشکان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مصیری',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مظفری',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'موردراز',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مهارلو',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مهبودی علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'مهرنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'میانده-فارس',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'میانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'میشان سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'میمند',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'نوبندگان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'نوجین',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'نودان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'نورآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'نی ریز',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'وراوی',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'هرایجان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'هرگان',
                'capital' => 0,
            ],
            [
                'province_id' => 17,
                'name' => 'هماشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'قزوین',
                'capital' => 1,
            ],
            [
                'province_id' => 18,
                'name' => 'محمدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'الوند',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'تاکستان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'آبیک',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'بوئین زهرا',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'مهرگان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'اقبالیه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'آبگرم',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'آوج',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'ارداق',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'استبلخ',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'اسفرورین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'اقابابا',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'الولک',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'بیدستان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'حسین اباد-قزوین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'حصارولیعصر',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'خاکعلی',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'خرم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'خرمدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'دانسفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'رازمیان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'رحیم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'رشتقون',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'زوارک',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'سگز آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'سیاهپوش',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'سیردان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'شال',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'شریفیه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'شهرک صنعتی لیا (قدیم )',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'صمغ اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'ضیاء آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'عصمت اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'فلار',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'قشلاق',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'کاکوهستان',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'کلنجین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'کوهین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'ماهین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'محمود آباد نمونه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'معلم کلایه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'مینودشت',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'ناصراباد-قزوین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'نرجه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'نصرت آباد-قزوین',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'نیارج',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'نیارک',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'نیکویه',
                'capital' => 0,
            ],
            [
                'province_id' => 18,
                'name' => 'یحیی اباد-قزوین',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'قم',
                'capital' => 1,
            ],
            [
                'province_id' => 19,
                'name' => 'قنوات',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'جعفریه',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'کهک',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'سلفچگان',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'دستجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'قاهان',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'قمرود',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'امیرابادگنجی',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'جنداب',
                'capital' => 0,
            ],
            [
                'province_id' => 19,
                'name' => 'قلعه چم',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'سنندج',
                'capital' => 1,
            ],
            [
                'province_id' => 20,
                'name' => 'قروه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'سقز',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'مریوان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بیجار',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بانه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کامیاران',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'دهگلان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'آرمرده',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'اق بلاغ طغامین',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'اورامان تخت',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بابارشانی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'برده رشه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بلبان آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بوالحسن',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بوئین سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'بیساران',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'پیرتاج',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'پیرخضران',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'توپ آغاج',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'توپ اغاج',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'تیلکو',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'جعفراباد-کردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'چناره',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'خامسان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'خرکه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'خسرواباد-کردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'خورخوره-کردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'دزج',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'دلبران',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'دیواندره',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'زرینه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'سرو آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'سریش آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'شاهینی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'شریف اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'شوی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'شویشه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'شیروانه',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'صاحب',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'طای',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'قوریچای',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کانی دینار',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کانی سور',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کانی گنجی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کسنزان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کوخان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'کوله',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'گازرخانی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'گاوشله',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'گل تپه-کردستان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'گورباباعلی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'موچش',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'میرده',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'نشورسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'ننور',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'نی',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'هزارکانیان',
                'capital' => 0,
            ],
            [
                'province_id' => 20,
                'name' => 'یاسوکند',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کرمان',
                'capital' => 1,
            ],
            [
                'province_id' => 21,
                'name' => 'سیرجان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'رفسنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جیرفت',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بم',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'زرند',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'شهر بابک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کهنوج',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ابارق',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'اختیار آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ارزوئیه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'امیراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'امین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'انار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'اندوهجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'باغین',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بافت',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'برج معاز',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بردسیر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بروات',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بزنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بلورد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بلوک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'بهرمان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'پاریز',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'پتکان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'تهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جبالبارز',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جرجافک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جوادیه الهیه نوق',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جوپار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جور',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جوزم',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'جوشان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'چترود',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'چناربرین',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'حتکن',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'حرجند',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'حسین ابادجدید',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خاتون آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خانوک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خانه خاتون',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خبر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خنامان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خواجوشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خورسند',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'خیراباد-کرمان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'داوران',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'درب بهشت',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'دریجان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'دشت خاک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'دشتکار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'دوساری',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ده بالا',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'دهج',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'رابر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'راور',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'راین',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'رضی ابادبالا',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'رودبار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ریحان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'زنگی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'زهکلوت',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'زید آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'سرچشمه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'سرخ قلعه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'سیریز',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'شعبجره',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'شهداد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'صفائیه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'عماداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'عنبر آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'فاریاب',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'فهرج',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'فیض اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'قلعه عسکر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'قلعه گنج',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کاظم آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کبوترخان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کشکوئیه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کشیت',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کمال اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کمسرخ',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کوهبنان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'کیانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گروه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گزک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گلباف',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گلزار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گلشن-کرمان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'گنبکی',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'لاله زار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ماهان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'محمد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'محمدابادبرفه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'محی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'مردهک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'مس سرچشمه',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'ملک اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'منوجان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'مومن اباد-کرمان',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'میجان علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'میرابادارجمند',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'نجف شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'نرماشیر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'نظام شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'نگار',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'نودژ',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'هجدک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'هرمزاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'هماشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'هنزا',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'هوتک',
                'capital' => 0,
            ],
            [
                'province_id' => 21,
                'name' => 'یزدان شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کرمانشاه',
                'capital' => 1,
            ],
            [
                'province_id' => 22,
                'name' => 'اسلام آباد غرب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سر پل ذهاب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سنقر',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کنگاور',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'گیلانغرب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'صحنه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'جوانرود',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'ازگله',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'اگاه علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'بانوره',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'باوله',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'باینگان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'بیستون',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'پاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'تازه آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'تپه رش',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'ترک ویس',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'جعفراباد-کرمانشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'حسن اباد-کرمانشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'حمیل',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'خسروی',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'درکه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'دوردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'دولت اباد-کرمانشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'دهلقین',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'رباط',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'روانسر',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'ریجاب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'زاوله علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سراب ذهاب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سرمست',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سطر',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سلطان اباد-کرمانشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سنقراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'سومار',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'شاهو',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'فرامان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'فش',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'قزوینه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'قصر شیرین',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'قلعه شیان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'قیلان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کرکسار',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کرند غرب',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کندوله',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کوزران',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'کیوه نان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'گردکانه علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'گودین',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'گهواره',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'مرزبانی',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'میان راهان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'میراباد-کرمانشاه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'نساردیره',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'نودشه',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'نوسود',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'هرسین',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'هفت اشیان',
                'capital' => 0,
            ],
            [
                'province_id' => 22,
                'name' => 'هلشی',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'یاسوج',
                'capital' => 1,
            ],
            [
                'province_id' => 23,
                'name' => 'دوگنبدان',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'دهدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'لنده',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'چرام',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'سی سخت',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'لیکک',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'باشت',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'باباکلان',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'پاتاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'چاه تلخاب علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'چیتاب',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'دیشموک',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'دیل',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'سربیشه-کهگیلویه و بویراحمد',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'سرفاریاب',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'سوق',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'شاه بهرام',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'قلعه دختر',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'قلعه رئیسی',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'قلعه رییسی',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'گراب سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'مادوان',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'مارگون',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'مظفراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 23,
                'name' => 'میمند-کهگیلویه و بویراحمد',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'گرگان',
                'capital' => 1,
            ],
            [
                'province_id' => 24,
                'name' => 'گنبدکاووس',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'علی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'آزاد شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'کرد کوی',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'بندر ترکمن',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'کلاله',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'مینودشت',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'آق قلا',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'انبار آلوم',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'اینچه برون',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'بندر گز',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'تاتار علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'تقی اباد-گلستان',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'جلین',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'حاجیکلاته',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'خان ببین',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'دلند',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'رامیان',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'سرخنکلاته',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'سنگدوین',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'سیمین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'عزیزاباد-گلستان',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'فاضل آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'فراغی',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'قرق',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'کرند',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'گالیکش',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'گمیش تپه',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'مراوه تپه',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'مزرعه',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'نگین شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'نوده خاندوز',
                'capital' => 0,
            ],
            [
                'province_id' => 24,
                'name' => 'نوکنده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت',
                'capital' => 1,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه اشرفیه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رودسر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستارا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه پینچاه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه تجن گوکه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه تمچال',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه چورکوچان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه چهارده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه دهسر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه سالک ده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه سوخته کوه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه صفرا بسته',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه کشل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'آستانه کیسم',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ابکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'احمد سرگوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'اسالم',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'اسکولک',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'املش',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'انزلی زیباکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'انزلی طالب آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بارکوسرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بازار جمعه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بازارخطبه سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بره سر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بلترک',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بلسبنه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی _ منطقه آزاد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_آبکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_تربه گوده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_جفرود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_حسن رود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_سنگاچین',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_شهر صنعتی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_علی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندر انزلی_کپورچال',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بندرانزلی_شانگهای پرده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'بیورزین',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پایین محله پاشاکی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پرگاپشت مهدی خانی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پروش پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پره سر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پلاسی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پلنگ پاره',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'پونل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'توتکابن',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'جنگ سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'جوکندان بزرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'جیرکویه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'جیرنده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'جیرهنده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'چابکسر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'چاپارخانه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'چوبر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'حسن سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'حویق',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'حیران',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خرارود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خشکبیجار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام چوکام',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام خشکبیجار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام خواچکین',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام دافچاه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام شیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام فشتکه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام لله کاه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'خمام مرزدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'دستک',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'دهشال',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'دیلمان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'دیوشل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رانکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رحیم آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رستم آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت امام زاده هاشم',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت پیر بازار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت پیرده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت پیله ملا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت تخته پل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت جیرده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت رجب آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت سراوان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت سنگر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت شهر صنعتی لاکان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت فخب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت فلکده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت کفترود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت کماکل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت لاکان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رشت مبارک آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رضوانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'رودبار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'سلوش',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'سیاهکل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'سیبلی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شفت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شلمان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شوییل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شهر صنعتی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شیرکوه چهارده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شیرین نسا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'شیله وشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا  طاهر گوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا  فشخام',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا چوبه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا خراط محله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا دوگور',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا شیخ محله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا صوفیانده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا ضیابر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا قصاب سرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا گوراب زرمیخ',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا لیفشاگرد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا لیموده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا نفوت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا واقع دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'صومعه سرا هند خاله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'طول لات',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'علی اباد-گیلان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن الیان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن خشکنودهیان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن دهستان گشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن زیده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن سیاه پیران',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن شالتوک',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن شالکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن فوشه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن قلعه رودخان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن کمادول',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن گوراب پس',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن لولمان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن ماکلوان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'فومن نوگوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کجید',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کلاچای',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کلشتر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کلیشم',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوته کومه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان جوربیجارکل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان حسن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان سده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان سنگر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوچصفهان لاله دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کوکنه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'کیاشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'گرمابدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'گرماور',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'گشت-گیلان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان آهندان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان بازکیا گوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان بیجار بنه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان پاشاکی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان جواهر پشته',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان چفل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان رودبنه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان سیاهگوراب',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان شهر صنعتی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان کوچکده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان کوشال',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان لفمجان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان لیالستان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان نخجیر کلایه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لاهیجان نوبیجار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لسکوکلایه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لشت نشاء',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود اطاقور',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود پرشکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود پیر پشته',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود چاف و چمخاله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود خراط محله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود دریاسر',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود دهستان گل سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود سالکویه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود شلمان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود فتیده',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود کومله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود لوکلایه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لنگرود یعقوبیه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لوشان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لولمان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لوندویل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لیچارکی حسن رود',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لیسار',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'لیش',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ماسال',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ماسوله',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'مرجقل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'مرکیه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'مشند',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ملاسرا',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'منجیل',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'نوخاله اکبری',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'واجارگاه',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ویرمونی',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'ویشان',
                'capital' => 0,
            ],
            [
                'province_id' => 25,
                'name' => 'هشتپر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'خرم آباد',
                'capital' => 1,
            ],
            [
                'province_id' => 26,
                'name' => 'بروجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'دورود',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کوهدشت',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'نورآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'الیگودرز',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'پلدختر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ازنا',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'اشتره گل گل',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'اشترینان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'افرینه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'الشتر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ایستگاه تنگ هفت',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'برخوردار',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'بندیزه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'بیرانشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'بیرانوند',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'پاعلم (پل تنگ )',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'پل شوراب پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'تقی اباد-لرستان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چالانچولان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چاه ذوالفقار',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چشمه کیزاب علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چقابل',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چم پلک',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چم سنگر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چمشک زیرتنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'چمن سلطان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'حیه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'خوشناموند',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'درب گنبد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'دره گرگ',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'دم باغ',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ده رحم',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'رازان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'زاغه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ژان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'سپید دشت',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'سراب دوره',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'سراب سیاهپوش',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'سوری',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'سیاه گوشی (پل هرو)',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'شاهپوراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'شول آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'فرهاداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'فیروزآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'قلعه بزنوید',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کاغه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کونانی',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کوهنانی',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کهریزوروشت',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'کیزاندره',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'گراب',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ماسور',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'مرگ سر',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'معمولان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'مکینه حکومتی',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'مومن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'میان تاگان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'واشیان نصیرتپه',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'ویسیان',
                'capital' => 0,
            ],
            [
                'province_id' => 26,
                'name' => 'هفت چشمه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ساری',
                'capital' => 1,
            ],
            [
                'province_id' => 27,
                'name' => 'بابل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'آمل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'قائم شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'نوشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'تنکابن',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بابلسر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'چالوس',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'آلاشت',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اتو',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ارطه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اروست',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اسبوکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اسلام اباد-مازندران',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اغوزکتی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'امافت',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'امامزاده عبدالله',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'امیرکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اوز-مازندران',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اهنگرکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ایزد شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'اینج دان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بادابسر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بالاجنیدلاک پل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بالادسته رکن کنار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بالادواب',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بالاهولار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'باییجان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بلده',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بندپی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بنفشه ده',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بهشهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بهنمیر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بیزکی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بیشه بنه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'بیشه سر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'پالند',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'پایین زرندین',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'پایین هولار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'پل سفید',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'پول',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'تاکام',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'تاکر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'تمل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'تیرتاش',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'جنت رودبار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'جواهرده',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'جویبار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'چرات',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'چلمردی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'چلندر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'چمستان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'حاجی کلاصنم',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خرم آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خشک دره',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خطیرکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خلیل شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خورشید (امامیه )',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'خوش رودپی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'دابودشت',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'دارابکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'دامداری حاج عزیزمجریان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'دامداری حسن ابوطالبی',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'درازکش',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'دلیر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ده میان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رامسر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رستمکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رکابدارکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رویان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ریکنده',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رینه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'رییس کلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'زاغمرز',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'زرگر محله',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'زیرآب',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سادات محله',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سرخرود',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سرلنگا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سفیدچاه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سلمان شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سلیمان اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سنگتاب',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سنگده',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سوا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سورک',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'سیاه بیشه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شورکش',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شهرک صنعتی گهرباران',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شهیداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شیرکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شیرگاه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'شیرود',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'صلاح الدین کلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'عباس آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'عرب خیل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'فرح اباد (خزراباد)',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'فریدونکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'فریم',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'قادیکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'قلعه گردن',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کاسگرمحله',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کتالم و سادات شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کترا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کتی لته',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کجور',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کردیچال',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کشکو',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کلارآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کلاردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کلنو',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کله بست',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کوهی خیل',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کیاسر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'کیاکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گاوانکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گتاب',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گردرودبار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گزنک',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گلعلی اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گلندرود',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گلوگاه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'گلوگاه',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'لاک تراشان',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'لشکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'لفور (لفورک )',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ماچک پشت',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'محمودآباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'مران سه هزار',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'مرزن آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'مرزیکلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'معلم کلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'میان دره',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'میان کوه سادات',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'نارنج بن',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'نشتارود',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'نکا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'نور',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'واسکس',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'ورسک',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'وسطی کلا',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'هادی شهر',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'هچیرود',
                'capital' => 0,
            ],
            [
                'province_id' => 27,
                'name' => 'هیچرود',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'اراک',
                'capital' => 1,
            ],
            [
                'province_id' => 28,
                'name' => 'ساوه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خمین',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'محلات',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'دلیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'مامونیه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'شهر جدید مهاجران',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'شازند',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'آستانه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'آشتیان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'آوه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'ادشته',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'استوه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'اصفهانک',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'الویر',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'امامزاده ورچه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'اناج',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'اهنگران',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'اهو',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'باقراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'بالقلو',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'بزیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'پرندک',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'تفرش',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'تواندشت علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'توره',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'جاورسیان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'جزنق',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'چمران-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'چهارچریک',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'چهارچشمه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'حسین اباد-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'حکیم اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خسروبیگ',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خشکرود',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خنجین',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خنداب',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خوراوند',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'خورهه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'داود آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'دخان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'دوزج',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'دهنو-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'رازقان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'رباطکفسان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'رباطمراد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'ریحان علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'زاغر',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'زاویه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'ساروق',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سامان-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سرسختی بالا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سلطان اباد-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سمقاور',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سنجان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'سیاوشان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'شهباز',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'شهراب-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'صالح اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'صدراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'علیشار',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'عیسی اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'غرق آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'فرفهان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'فرمهین',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'فشک',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'قاقان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'قدمگاه-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'قورچی باشی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کارچان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کتیران بالا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کرهرود',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کزاز',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کمیجان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'کهک-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'گلدشت-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'لکان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'لنجرود',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'لوزدرعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'مالمیر',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'مراغه-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'مزرعه نو',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'میشیجان علیا',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'میلاجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'نخجیروان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'نراق',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'نوبران',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'نهرمیان',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'نیمور',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'ورچه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'وفس',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'هزاوه',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'هفته',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'هندودر',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'یحیی اباد-مرکزی',
                'capital' => 0,
            ],
            [
                'province_id' => 28,
                'name' => 'یل اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندرعباس',
                'capital' => 1,
            ],
            [
                'province_id' => 29,
                'name' => 'کیش',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'میناب',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'قشم',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بستک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندر لنگه',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندر جاسک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'دهبارز',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'ابگرم خورگو',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'ابوموسی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'باغات',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندر',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندرمغویه',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بندزرک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'بیکاه',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'پارسیان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'پدل',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'پشته ایسین',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'پل شرقی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'تازیان پائین',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'تخت',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'تیاب',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'تیرور',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'جزیره سیری',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'جزیره لارک شهری',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'جزیره لاوان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'جغین',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'جناح',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'چارک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'حاجی آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'حسن لنگی پایین',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'خمیر',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'درپهن',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'درگهان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'دژگان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'دشتی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'دهنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'رویدر',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'زیارتعلی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سرگز',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سندرک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سوزا',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سیاهو',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'سیریک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'فارغان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'فین',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'قلعه قاضی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کلورجکدان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کمشک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کوخردهرنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کوشکنار',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'کوهستک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گروک',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گزیر',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گوربند',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گونمردی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گوهران',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'گوهرت',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'لمزان',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'ماشنگی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'هرمز',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'هشتبندی',
                'capital' => 0,
            ],
            [
                'province_id' => 29,
                'name' => 'هنگام جدید',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'همدان',
                'capital' => 1,
            ],
            [
                'province_id' => 30,
                'name' => 'ملایر',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'نهاوند',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'تویسرکان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'اسد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'کبودر آهنگ',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'بهار',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'رزن',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'آجین',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'ازناو',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'ازندریان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'اسلام اباد-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'اشتران',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'اکنلو',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'اورزمان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'باباپیر',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'بابارستم',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'باباقاسم',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'برزول',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'پادگان قهرمان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'پایگاه نوژه',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'پرلوک',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'تجرک',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جعفریه (قلعه جعفربیک )',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جنت اباد-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جورقان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جوزان-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جوکار',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'جهان اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'چانگرین',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'چنارسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'چنارعلیا',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'حسین ابادبهارعاشوری',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'داق داق اباد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'دمق',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'دهفول',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'دیناراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'زنگنه',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'سامن',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'سرکان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'شهرک صنعتی بوعلی',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'شیرین سو',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'صالح آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'طویلان سفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'علیصدر',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'فامنین',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'فرسفج',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'فیروزان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'قروه در جزین',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'قهاوند',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'قهوردسفلی',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'کوریجان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'کوزره',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'کوهین-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'گل تپه',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'گنبد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'گیان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'لالجین',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'مریانج',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'موسی اباد-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'مهاجران',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'میانده-همدان',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'ولاشجرد',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'ویرایی',
                'capital' => 0,
            ],
            [
                'province_id' => 30,
                'name' => 'همه کسی',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'یزد',
                'capital' => 1,
            ],
            [
                'province_id' => 31,
                'name' => 'میبد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'اردکان',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'بافق',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'مهریز',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'ابرکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'تفت',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'اشکذر',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'احمد آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'ارنان',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'اسفنداباد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'اسفیج',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'انارستان-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'بفروئیه',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'بنستان',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'بهاباد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'بهادران',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'تنگ چنار (چنار)',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'حمیدیا',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'خضر آباد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'دهشیر',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'زارچ',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'زرین',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'شاهدیه',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'عقدا',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'علی اباد-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'فتح اباد-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'فراغه',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'فهرج-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'کوشک-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'مبارکه-یزد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'مروست',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'مهردشت',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'ناحیه صنعتی پیشکوه',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'ناحیه صنعتی گاریزات',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'ندوشن',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'نصراباد',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'نیر',
                'capital' => 0,
            ],
            [
                'province_id' => 31,
                'name' => 'هرات',
                'capital' => 0,
            ],
        ];

        DB::table('cities')->insert($cities);

    }
}
