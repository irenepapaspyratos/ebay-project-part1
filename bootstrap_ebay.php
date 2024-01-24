<?php

// Include general bootstrap file
require_once __DIR__ . '/bootstrap_base.php';

use App\Utility as Utils;

// Assign values
$ebaySiteId = $config['ebay']['site_id'];
$ebayCompatLevel = $config['ebay']['combatibility_level'];
$ebayApiToken = $_ENV['EBAY_API_TOKEN'];

// Create instance of CustomCurl
$ebayCurl = new Utils\CustomCurl($config['ebay']['xml_api_production']);
