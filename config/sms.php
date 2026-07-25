<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IPPanel — شماره خط ارسال
    |--------------------------------------------------------------------------
    | فرمت: +981000xxxx
    */
    'sender' => env('SMS_SENDER', ''),

    'patterns' => [
        'order_created_renter' => env('SMS_PATTERN_ORDER_RENTER', ''),
        'order_created_owner' => env('SMS_PATTERN_ORDER_OWNER', ''),
        'order_created_admin' => env('SMS_PATTERN_ORDER_ADMIN', ''),
        'order_awaiting_payment' => env('SMS_PATTERN_AWAITING_PAYMENT', ''),
        'order_canceled' => env('SMS_PATTERN_CANCELED', ''),
        'order_rejected' => env('SMS_PATTERN_REJECTED', ''),
        'order_pending_timeout_owner' => env('SMS_PATTERN_PENDING_TIMEOUT_OWNER', ''),
        'order_waiting_for_renter' => env('SMS_PATTERN_WAITING_FOR_RENTER', ''),
        'before_residence' => env('SMS_PATTERN_BEFORE_RESIDENCE', ''),
        'after_residence' => env('SMS_PATTERN_AFTER_RESIDENCE', ''),
        'login_otp' => env('SMS_PATTERN_LOGIN_OTP', ''),
    ],
    'parameter_names' => [
        'order_created_admin' => [
            'id' => 'ID',
            'count' => 'COUNT',
            'start_date' => 'START_DATE',
            'end_date' => 'END_DATE',
            'amount' => 'AMOUNT',
            'guest_name' => 'GUEST_NAME',
            'guest_mobile' => 'GUEST_MOBILE',
            'owner_name' => 'OWNER_NAME',
            'owner_mobile' => 'OWNER_MOBILE',
            'calendar_link' => 'CALENDAR_LINK',
        ],
        'order_created_renter' => [
            'home_name' => 'HOME_NAME',
            'consultant_name' => 'CONSULTANT_NAME',
            'consultant_mobile' => 'CONSULTANT_MOBILE',
        ],
    ],
    'parameter_max_length' => (int) env('SMS_PARAMETER_MAX_LENGTH', 40),
    'calendar_url_prefix' => env('SMS_CALENDAR_URL_PREFIX', 'https://vilafarda.ir/'),
];
