<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute باید پذیرفته باشد.',
    'accepted_if' => 'وقتی :other برابر :value باشد! :attribute باید پذیرفته باشد.',
    'active_url' => ':attribute یک URL معتبر نیست.',
    'after' => ':attribute باید بعد از تاریخ :date باشد.',
    'after_or_equal' => ':attribute باید برابر یا بعد از تاریخ :date باشد.',
    'alpha' => ':attribute فقط باید شامل حروف باشد.',
    'alpha_dash' => ':attribute فقط باید شامل حروف، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num' => ':attribute فقط باید شامل حروف و اعداد باشد.',
    'array' => ':attribute باید یک آرایه باشد.',
    'before' => ':attribute باید قبل از تاریخ :date باشد.',
    'before_or_equal' => ':attribute باید برابر یا قبل از تاریخ :date باشد',
    'between' => [
        'numeric' => ':attribute باید بین (:min - :max) باشد.',
        'file' => ':attribute باید بین (:min - :max) کیلوبایت باشد.',
        'string' => ':attribute باید بین (:min - :max) حرف داشته باشد.',
        'array' => ':attribute باید بین (:min - :max) تعداد آیتم باشد.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => ':attribute با تاییدش یکسان نیست.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute یک ایمیل معتبر نیست.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => ':attribute معتبر نیست.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => ':attribute باید یک عکس باشد.',
    'in' => ':attribute ارسال شده معتبر نیست.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute نباید بیشتر از :max باشد.',
        'file' => 'حجم :attribute نباید بیشتر از :max کیلوبایت باشد.',
        'string' => ':attribute نباید بیشتر از :max حرف داشته باشد.',
        'array' => 'تعداد :attribute نباید بیشتر از :max آیتم باشد.',
    ],
    'mimes' => ':attribute باید از نوع: :values باشد.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => ':attribute نباید کمتر از :min باشد.',
        'file' => 'حجم :attribute نباید کمتر از :min کیلوبایت باشد.',
        'string' => ':attribute نباید کمتر از :min حرف داشته باشد.',
        'array' => 'تعداد :attribute نباید کمتر از :min آیتم باشد.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute حتما باید عدد باشد.',
    'password' => ':attribute درست نیست.',
    'valid_password' => ':attribute باید حداقل شامل یک عدد، یک حرف بزرگ، یک حرف کوچیک و یک نماد باشد.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'فرمت :attribute درست نیست.',
    'required' => ':attribute اجباری است.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => ':attribute باید از نوع رشته ای باشد.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => ':attribute قبلا ثبت شده، لطفا :attribute دیگری وارد کنید.',
    'uploaded' => 'بارگذاری «:attribute» انجام نشد. محدودیت PHP (upload_max_filesize / post_max_size) اجازهٔ این حجم را نمی‌دهد؛ تصویر مدرک باید قبل از ارسال سبک شود یا سقف PHP را بالا ببرید. برای لوکال: composer run serve (نه php artisan serve).',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'bootstrap_icon' => 'آیکون انتخاب‌شده معتبر نیست.',

    'attributes' => [
        'email' => 'ایمیل',
        'password' => 'رمز',
        'password_confirmation' => 'تایید رمز',
        'first_name' => 'نام',
        'last_name' => 'نام خانوادگی',
        'name' => 'نام',
        'mobile' => 'موبایل',
        'verified_email' => 'ایمیل تایید شده',
        'blocked' => 'بلاک شده',
        'id' => 'شناسه',
        'permissions' => 'نقش',
        'permissions.*' => 'نقش ها',
        'title' => 'عنوان',
        'reply_id' => 'شناسه پاسخ',
        'comment' => 'نظر',
        'status' => 'وضعیت',
        'code' => 'کد',
        'slug' => 'اسلاگ',
        'summary' => 'خلاصه',
        'content' => 'محتوا',
        'metas' => 'متا ها',
        'metas.*' => 'متا',
        'tags' => 'تگ ها',
        'tags.*' => 'تگ',
        'image' => 'تصویر',
        'category' => 'دسته بندی',
        'ticket' => 'تیکت',
        'message' => 'پیام',
        'sort' => 'ردیف',
        'question' => 'سوال',
        'answer' => 'جواب',
        'email_mobile' => 'آدرس ایمیل یا شماره همراه',
        'attachments' => 'ضمیمه ها',
        'attachments.*' => 'ضمیمه',
        'avatar' => 'تصویر',
        'user' => 'کاربر',
        'subject' => 'موضوع',
        'description' => 'توضیحات',
        'address' => 'آدرس',
        'province' => 'استان',
        'city' => 'شهر',
        'yard' => 'محوطه',
        'infrastructure' => 'زیربنا',
        'main_guest' => 'میهمان اصلی',
        'extra_guest' => 'مهمان اضافه',
        'week_price' => 'قیمت وسط هفته',
        'thu_price' => 'قیمت پنج نشبه',
        'fri_price' => 'قیمت جمعه',
        'price_per_surplus' => 'قیمت به ازای هر نفر',
        'placeholder' => 'نگهدارنده',
        'type' => 'نوع',
        'input_type' => 'نوع اینپوت',
        'options' => 'قوانین',
        'options.*.id' => 'شناسه مقدار',
        'options.*.name' => 'نام مقدار',
        'file' => 'فایل',
        'off' => 'تخفیف',
        'daily_off' => 'تعداد روز',
        'document' => 'سند',
        'shaba' => 'شماره شبا',
        'payment_reference' => 'شناسه پرداخت',
        'cover' => 'تصویر کاور',
        'gallery' => 'گالری تصاویر',
        'gallery.*' => 'یکی از تصاویر گالری',
        'images' => 'تصاویر',
        'images.*' => 'یکی از تصاویر',
        'icon_type' => 'نوع آیکون',
        'icon_class' => 'آیکون',
    ],

];
