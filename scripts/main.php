<?php

// Include the `bootstrap.php`to set up the application's environment
require __DIR__ . '/../bootstrap.php';

use App\Service\EbayApiService;
use App\Utility as Utils;

// Assign values
$logfileInfo = $config['path']['file_log_info'];
$logfileError = $config['path']['file_log_error'];
$ebayApiUrl = $config['ebay']['xml_api_production'];
$ebaySiteId = $config['ebay']['site_id'];
$ebayCompatLevel = $config['ebay']['combatibility_level'];
$ebayApiToken = $_ENV['EBAY_API_TOKEN'];

// Create instance of EbayApiService and necessary classes
$customLogger = new Utils\CustomLogger($logfileInfo, $logfileError);
$ebayCurl = new Utils\CustomCurl($ebayApiUrl);
$ebayDateUtils = new Utils\DateUtils();
$xmlUtils = new Utils\XmlUtils();
$ebayApiService = new EbayApiService($xmlUtils, $customLogger, $ebayCurl, $ebayDateUtils, $ebayApiToken, $ebayCompatLevel, $ebaySiteId);

// Create only once the initial file with all ids of a seller's active item listings
if (!glob(str_replace('.xml', '*.xml', $initialActiveIdsFile)))
    print_r($ebayApiService->getSellerList());
