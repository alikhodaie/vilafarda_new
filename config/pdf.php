<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Rentnaab',
	'display_mode'          => 'fullpage',
	'tempDir'               => storage_path('app/temp'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    'font_path' => base_path('resources/fonts/'),
    'font_data' => [
        'sansnew' => [
            'R'  => 'Sans.ttf',    // regular font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ],
        'yekan' => [
            'R'  => 'BYekan.ttf',    // regular font
            'B'  => 'BYekanBold.ttf',       // optional: bold font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
    ]
];
