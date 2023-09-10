<?php

// Used details of eBay items with type to store
$ebayItemColumns = [
    'columns' => [
        'id' => 'int',
        'item_id' => 'int',
        'title' => 'string',
        'current_price' => 'float',
        'status' => 'int', // foreign Key
        'quantity' => 'int',
        'quantity_sold' => 'int',
        'condition' => 'int', // foreign Key
        'category' => 'int', // foreign Key
        'store_category_id' => 'string',
        'store_category_2_id' => 'string',
        'view_item_url' => 'string',
        'pictures' => 'JSON',
        'site' => 'string', // foreign Key
        'country' => 'string',
        'currency' => 'string',
        'ship_to_locations' => 'JSON',
        'shipping_options' => 'JSON',
        'item_compatibility' => 'JSON',
        'item_specifics' => 'JSON',
        'html_description' => 'string',
        'net_price' => 'float',
        'filetime' => 'string'
    ]
];

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
    'database' => [
        'tables' => [
            'ebay_category' => [
                'columns' => [
                    'id' => 'int',
                    'category_id' => 'int',
                    'category_name' => 'string',
                    'parent_id' => 'int'
                ]
            ],
            'ebay_listing_status' => [
                'columns' => [
                    'id' => 'int',
                    'listing_status' => 'string',
                    'status_description' => 'string'
                ]
            ],
            'ebay_condition' => [
                'columns' => [
                    'id' => 'int',
                    'condition_id' => 'int',
                    'condition_display_name' => 'string'
                ]
            ],
            'ebay_site_code' => [
                'columns' => [
                    'id' => 'int',
                    'site_id' => 'int',
                    'site_name' => 'string',
                    'site_global_id' => 'string'
                ]
            ],
            'ebay_item' => $ebayItemColumns,
            'ebay_item_active' => $ebayItemColumns,
            'ebay_item_archive' => $ebayItemColumns,
        ]
    ]
];
