<?php

// Include general bootstrap file
require_once __DIR__ . '/bootstrap_base.php';

use App\Utility as Utils;

// Create instances of util classes
$dateUtils = new Utils\DateUtils();
$xmlUtils = new Utils\XmlUtils();
