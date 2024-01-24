<?php

// Include the necessary `bootstraps`to set up the application's environment
require_once __DIR__ . '/../bootstrap_utils.php';
require_once __DIR__ . '/../bootstrap_ebay.php';

use App\Service\EbayApiService;

// Create instance of EbayApiService
$ebayApiService = new EbayApiService($xmlUtils, $customLogger, $ebayCurl, $dateUtils, $ebayApiToken, $ebayCompatLevel, $ebaySiteId);

// Create only once the initial file with all ids of a seller's active item listings
if (!glob(str_replace('.xml', '*.xml', $initialActiveIdsFile)))
    print_r($ebayApiService->getSellerList());
