<?php

use App\Models\Home;

return [
    /*
    | نام‌های رایج جستجو → نوع اقامتگاه (مقدار ستون type)
    */
    'type_aliases' => [
        'ویلا' => Home::VILAIY,
        'ویلایی' => Home::VILAIY,
        'ویلات' => Home::VILAIY,
        'آپارتمان' => Home::APARTEMAN,
        'اپارتمان' => Home::APARTEMAN,
        'کلبه' => Home::KOLBEH,
        'کلبه‌ای' => Home::KOLBEH,
        'خانه روستایی' => Home::KHANE_ROOSTANIY,
        'بوم گردی' => Home::EGHAMATGAH_BOOM_GARDY,
        'بوم‌گردی' => Home::EGHAMATGAH_BOOM_GARDY,
        'سوییت' => Home::SWIIT,
        'سوئیت' => Home::SWIIT,
        'مهمانخانه' => Home::MEHMAN_KHANE,
        'مهمان خانه' => Home::MEHMAN_KHANE,
        'هتل آپارتمان' => Home::HOTEL_APARTEMAN,
        'پانسیون' => Home::PANSION,
        'هاستل' => Home::HASTEL,
        'کاروانسرا' => Home::KARVAN_SARA,
        'بوتیک هتل' => Home::BOTIK_HOTEL,
        'قایق' => Home::GHAIEGH,
        // slugهای فیلتر موبایل قدیمی
        'villa' => Home::VILAIY,
        'apartment' => Home::APARTEMAN,
        'house' => Home::KHANE_ROOSTANIY,
    ],

    /*
    | کلیدواژه امکانات → عبارت‌های جستجو در عنوان Option
    */
    'amenity_keywords' => [
        'استخر' => ['استخر', 'استخردار', 'pool'],
        'جکوزی' => ['جکوزی', 'جکuzzi', 'hot tub', 'وان جکوزی'],
        'بیلیارد' => ['بیلیارد', 'بیلارد', 'billiard'],
        'وای‌فای' => ['وای‌فای', 'وایفای', 'wifi', 'wi-fi', 'اینترنت'],
        'پارکینگ' => ['پارکینگ', 'parking', 'گاراژ'],
        'باغ' => ['باغ', 'حیاط', 'garden'],
        'باربیکیو' => ['باربیکیو', 'باربیکیو', 'barbecue', 'bbq'],
        'آسانسور' => ['آسانسور', 'elevator'],
        'سونا' => ['سونا', 'sauna'],
    ],

    /*
    | slug فیلتر features[] موبایل → کلید amenity_keywords
    */
    'feature_slugs' => [
        'wifi' => 'وای‌فای',
        'parking' => 'پارکینگ',
        'pool' => 'استخر',
        'garden' => 'باغ',
    ],
];
