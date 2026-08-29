<?php

return [
    'android_package' => env('PWA_ANDROID_PACKAGE', 'com.vilafarda.app'),
    'apk_filename' => env('PWA_APK_FILENAME', 'vilafarda.apk'),
    'sha256_fingerprints' => array_values(array_filter(array_map('trim', explode(',', (string) env(
        'PWA_ANDROID_SHA256',
        '65:6C:0C:E8:FA:98:D4:DA:61:F9:4E:87:A5:A5:97:46:1B:A6:5F:04:6F:48:B9:0F:FA:EF:24:B2:1F:BF:89:33'
    ))))),
];
