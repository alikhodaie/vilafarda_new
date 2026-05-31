<?php

return [
    "api-key" => env("SMS_API_KEY"),
    "patterns" => [
        "order_created_renter" => env("SMS_PATTERN_ORDER_RENTER", "300047"),
        "order_created_owner" => env("SMS_PATTERN_ORDER_OWNER", "233577"),
        "order_created_admin" => env("SMS_PATTERN_ORDER_ADMIN", "431957"),
    ],
];
