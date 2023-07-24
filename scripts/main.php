<?php

require __DIR__ . '/../vendor/autoload.php';

// Load configuration values
$config = require __DIR__ . '/../config.php';

use App\Service\EbayApiService;

// Load environment and configuration variables
$apiUrl = $config['ebay']['xml_api_production'];
$apiToken = getenv('EBAY_API_TOKEN');
$compatLevel = $config['ebay']['combatibility_level'];
$siteId = $config['ebay']['site_id'];

// Create services
$ebayApiService = new EbayApiService($apiUrl, $apiToken, $compatLevel, $siteId);

// Fetch timestamp from eBay
$ebayTimestamp = $ebayApiService->getEbayTimestamp();
