<?php

// Load classes
require __DIR__ . '/../vendor/autoload.php';

// Load configuration values
$config = require __DIR__ . '/../config.php';

use App\Service\EbayApiService;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;

// Assign environment and configuration variables
$ebayApiUrl = $config['ebay']['xml_api_production'];
$ebayApiToken = getenv('EBAY_API_TOKEN');
$ebayCompatLevel = $config['ebay']['combatibility_level'];
$ebaySiteId = $config['ebay']['site_id'];

// Create services
$customLogger = new CustomLogger();
$ebayCurl = new CustomCurl($ebayApiUrl);
$ebayApiService = new EbayApiService($customLogger, $ebayCurl, $ebayApiToken, $ebayCompatLevel, $ebaySiteId);

// Fetch eBay's timestamp
$ebayTimestamp = $ebayApiService->getTimestamp();
