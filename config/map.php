<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Map provider
    |--------------------------------------------------------------------------
    |
    | osm     — OpenStreetMap (پیش‌فرض، نیاز به اینترنت بین‌الملل)
    | neshan  — نشان (مناسب اینترنت ملی ایران)
    | mapir   — map.ir
    | local   — tile server داخلی خودتان
    |
    */
    'provider' => env('MAP_PROVIDER', 'osm'),

    /*
    | اگر پر شود، provider نادیده گرفته می‌شود و مستقیم از این URL استفاده می‌شود.
    | مثال: https://map.domin-shoma.ir/tiles/{z}/{x}/{y}.png
    */
    'tile_url' => env('MAP_TILE_URL'),

    'attribution' => env('MAP_ATTRIBUTION'),

    'max_zoom' => (int) env('MAP_MAX_ZOOM', 19),

    'geocoder' => [
        'enabled' => filter_var(env('MAP_GEOCODER_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
        'provider' => env('MAP_GEOCODER_PROVIDER'),
    ],

    'neshan' => [
        'api_key' => env('NESHAN_API_KEY'),
        'style' => env('NESHAN_MAP_STYLE', 'standard-day'),
    ],

    'mapir' => [
        'api_key' => env('MAPIR_API_KEY'),
    ],

    'local' => [
        'tile_url' => env('MAP_LOCAL_TILE_URL', '/tiles/{z}/{x}/{y}.png'),
        'geocoder_url' => env('MAP_LOCAL_GEOCODER_URL'),
    ],

    'osm' => [
        'tile_url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'geocoder_url' => 'https://nominatim.openstreetmap.org/reverse',
    ],

];
