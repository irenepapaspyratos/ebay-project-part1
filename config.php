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
    'database' => [
        'tables' => [
            'ebay_category' => [
                'columns' => ['id', 'category_id', 'category_name', 'parent_id']
            ],
            'ebay_listing_status' => [
                'columns' => ['id', 'status_code', 'status_description']
            ],
            'ebay_condition' => [
                'columns' => ['id', 'condition_id', 'condition_display_name']
            ],
            'ebay_site_code' => [
                'columns' => ['id', 'site_id', 'site_name', 'site_global_id']
            ],
            'ebay_active_items' => [
                'columns' => [
                    'id', 'item_id', 'title', 'current_price', 'listing_status', 'quantity', 'quantity_sold', 'condition',
                    'category', 'store_category_id', 'store_category_2_id', 'view_item_url', 'pictures',
                    'site', 'country', 'currency', 'ship_to_locations', 'shipping_options',
                    'item_compatibility', 'item_specifics', 'html_description', 'net_price', 'filetime'
                ]
            ],
        ]
    ],
];
