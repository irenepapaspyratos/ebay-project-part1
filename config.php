<?php

// Used details of eBay items with type to store (fk_ = foreign key)
$ebayItemColumns = [
    'columns' => [
        'id' => 'integer',
        'item_id' => 'integer',
        'title' => 'string',
        'subtitle' => 'string',
        'current_price' => 'float',
        'fk_status' => 'integer',
        'start_time_utc' => 'DateTime',
        'end_time_utc' => 'DateTime',
        'quantity' => 'integer',
        'quantity_sold' => 'integer',
        'quantity_sold_pickup' => 'integer',
        'fk_condition' => 'integer',
        'fk_category_230577' => 'integer',
        'fk_category_201077' => 'integer',
        'fk_second_category_230577' => 'integer',
        'fk_second_category_201077' => 'integer',
        'store_category_id' => 'string',
        'store_category_2_id' => 'string',
        'view_item_url' => 'string',
        'pictures' => 'JSON',
        'ebay_site' => 'string',
        'fk_country' => 'integer',
        'fk_currency' => 'integer',
        'ship_to_locations' => 'JSON',
        'shipping_details' => 'JSON',
        'item_compatibilities' => 'JSON',
        'item_specifics' => 'JSON',
        'html_description' => 'string',
        'vat_percent' => 'float',
        'net_price' => 'float',
        'last_update_utc' => 'DateTime'
    ]
];

$ebayCategoryColumns = [
    'columns' => [
        'id' => 'integer',
        'category_id' => 'integer',
        'category_level' => 'integer',
        'category_name' => 'string',
        'leaf_category' => 'boolean',
        'parent_category_id' => 'integer',
        'fk_parent_id' => 'integer',
    ]
];

// Set global configurations
return [
    'ebay' => [
        'xml_api_production' => 'https://api.ebay.com/ws/api.dll',  // Sandbox: 'https://api.sandbox.ebay.com/ws/api.dll'
        'combatibility_level' => 1323,  // API version 2023-May-25,
        'site_id' => 77,  // USA: 0, Germany: 77
        'category_version' => [
            'actual' => '230577',
            'archive' => ['230577', '201077'],
        ],
    ],
    'path' => [
        'dir_ebay_actives' => __DIR__ . '/data/ebay/items_active',
        'dir_ebay_archive' => __DIR__ . '/data/ebay/items_archive',
        'file_ebay_failed_calls' => __DIR__ . '/var/temp/ebay_failed_calls.txt',
        'file_log_info' => __DIR__ . '/log/info.log',
        'file_log_error' => __DIR__ . '/log/error.log',
        'file_initial_actives' => __DIR__ . '/data/ebay/initial_active_ids.xml',
    ],
    'database' => [
        'table' => [
            'ebay_condition' => [
                'columns' => [
                    'id' => 'integer',
                    'condition_id' => 'integer',
                    'condition_display_name' => 'string',
                ],
            ],
            'ebay_country_detail' => [
                'columns' => [
                    'id' => 'integer',
                    'country_code' => 'string',
                    'country_description' => 'string',
                ],
            ],
            'ebay_currency_detail' => [
                'columns' => [
                    'id' => 'integer',
                    'currency_code' => 'string',
                    'currency_description' => 'string',
                ],
            ],
            'ebay_site_detail' => [
                'columns' => [
                    'id' => 'integer',
                    'site_id' => 'integer',
                    'site_name' => 'string',
                ],
            ],
            'ebay_listing_status' => [
                'columns' => [
                    'id' => 'integer',
                    'status_code' => 'string',
                    'status_description' => 'string',
                ],
            ],
            'ebay_category_230577' => $ebayCategoryColumns,
            'ebay_category_201077' => $ebayCategoryColumns,
            'ebay_item' => $ebayItemColumns,
            'ebay_item_active' => $ebayItemColumns,
            'ebay_item_archive' => $ebayItemColumns,
        ],
    ],
];
