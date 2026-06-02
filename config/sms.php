<?php

return [
    'api-key' => env('SMS_API_KEY'),
    'patterns' => [
        'order_created_renter' => env('SMS_PATTERN_ORDER_RENTER', '300047'),
        'order_created_owner' => env('SMS_PATTERN_ORDER_OWNER', '233577'),
        'order_created_admin' => env('SMS_PATTERN_ORDER_ADMIN', '431957'),
    ],
    'parameter_names' => [
        'order_created_admin' => [
            'id' => 'ID',
            'count' => 'COUNT',
            'start_date' => 'START-DATE',
            'end_date' => 'END-DATE',
            'amount' => 'AMOUNT',
            'guest_name' => 'GUEST-NAME',
            'guest_mobile' => 'GUEST-MOBILE',
            'owner_name' => 'OWNER-NAME',
            'owner_mobile' => 'OWNER-MOBILE',
            'calendar_link' => 'CALENDAR_LINK',
        ],
        'order_created_renter' => [
            'home_name' => 'HOME_NAME',
            'consultant_name' => 'consultant_name',
            'consultant_mobile' => 'consultant_mobile',
        ],
    ],
    'parameter_max_length' => (int) env('SMS_PARAMETER_MAX_LENGTH', 25),
];
