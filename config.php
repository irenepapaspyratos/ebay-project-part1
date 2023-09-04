<?php

// Set global configurations
return [
    'ebay' => [
        'xml_api_production' => 'https://api.ebay.com/ws/api.dll',  // Sandbox: 'https://api.sandbox.ebay.com/ws/api.dll'
        'combatibility_level' => 1323,  // API version 2023-May-25,
        'site_id' => 77,  // USA: 0, Germany: 77
    ],
    'path' => [
        'dir_ebay_actives' => __DIR__ . '/data/ebay/items_active',
        'dir_ebay_inactives' => __DIR__ . '/data/ebay/items_inactive',
        'file_ebay_failed_calls' => __DIR__ . '/var/temp/ebay_failed_calls.txt',
        'file_log_info' => __DIR__ . '/log/info.log',
        'file_log_error' => __DIR__ . '/log/error.log',

    ],
];
