<?php

// Load classes
require __DIR__ . '/../vendor/autoload.php';

// Load configuration values
$config = require __DIR__ . '/../config.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$errors = $dotenv->load();

use App\Service\EbayApiService;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;
use App\Utility\DateUtils;

// Assign environment and configuration variables
$ebayApiUrl = $config['ebay']['xml_api_production'];
$ebayApiToken = $_ENV['EBAY_API_TOKEN'];
$ebayCompatLevel = $config['ebay']['combatibility_level'];
$ebaySiteId = $config['ebay']['site_id'];

// Create services
$customLogger = new CustomLogger();
$ebayCurl = new CustomCurl($ebayApiUrl);
$ebayDateUtils = new DateUtils();
$ebayApiService = new EbayApiService($customLogger, $ebayCurl, $ebayDateUtils, $ebayApiToken, $ebayCompatLevel, $ebaySiteId);

// Fetch eBay's timestamp
$ebayTimestamp = $ebayApiService->getTimestamp();
