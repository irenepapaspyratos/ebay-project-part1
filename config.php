<?php

// Used details of eBay items with type to store (fk_ = foreign key)
$ebayItemColumns = [
    'columns' => [
        'id' => 'integer',
        'item_id' => 'integer',
        'title' => 'string',
        'current_price' => 'float',
        'fk_status' => 'integer',
        'quantity' => 'integer',
        'quantity_sold' => 'integer',
        'fk_condition' => 'integer',
        'fk_category' => 'integer',
        'store_category_id' => 'string',
        'store_category_2_id' => 'string',
        'view_item_url' => 'string',
        'pictures' => 'JSON',
        'fk_site' => 'integer',
        'fk_country' => 'integer',
        'fk_currency' => 'integer',
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
                    'id' => 'integer',
                    'category_id' => 'integer',
                    'category_name' => 'string',
                    'parent_id' => 'integer'
                ]
            ],
            'ebay_listing_status' => [
                'columns' => [
                    'id' => 'integer',
                    'status_code' => 'string',
                    'status_description' => 'string'
                ]
            ],
            'ebay_condition' => [
                'columns' => [
                    'id' => 'integer',
                    'condition_id' => 'integer',
                    'condition_display_name' => 'string'
                ]
            ],
            'ebay_site_code' => [
                'columns' => [
                    'id' => 'integer',
                    'site_id' => 'integer',
                    'site_name' => 'string',
                    'site_global_id' => 'string'
                ],
            ],
            'ebay_country_code' => [
                'columns' => [
                    'id' => 'integer',
                    'country_code' => 'string',
                    'country_description' => 'string',
                    'fk_default_currency' => 'integer'
                ],
            ],
            'ebay_currency_code' => [
                'columns' => [
                    'id' => 'integer',
                    'currency_code' => 'string',
                    'currency_description' => 'string',
                ],
            ],
            'ebay_item' => $ebayItemColumns,
            'ebay_item_active' => $ebayItemColumns,
            'ebay_item_archive' => $ebayItemColumns,
        ]
    ]
];
