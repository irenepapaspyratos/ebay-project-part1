<?php

// Load classes
require __DIR__ . '/../vendor/autoload.php';

// Load configuration values
$config = require __DIR__ . '/../config.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$errors = $dotenv->load();

use App\Enum\Ebay\EbayGranularityLevel;
use App\Service\EbayApiService;
use App\Utility as Utils;

// Assign environment and configuration variables
$ebayApiUrl = $config['ebay']['xml_api_production'];
$ebayApiToken = $_ENV['EBAY_API_TOKEN'];
$ebayCompatLevel = $config['ebay']['combatibility_level'];
$ebaySiteId = $config['ebay']['site_id'];

// Create services
$xmlUtils = new Utils\XmlUtils();
$customLogger = new Utils\CustomLogger();
$ebayCurl = new Utils\CustomCurl($ebayApiUrl);
$ebayDateUtils = new Utils\DateUtils();
$ebayApiService = new EbayApiService($xmlUtils, $customLogger, $ebayCurl, $ebayDateUtils, $ebayApiToken, $ebayCompatLevel, $ebaySiteId);

if (!glob(__DIR__ . '/../data/ebay/initial_active_ids' . '*.xml'))
    $ebayApiService->storeSellerList();
