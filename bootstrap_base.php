<?php

// Include autoloader for classes
require_once __DIR__ . '/vendor/autoload.php';

// Load configuration values
$config = require_once __DIR__ . '/config.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$errors = $dotenv->load();

use App\Utility as Utils;

// Create instance of CustomLogger
$customLogger = new Utils\CustomLogger($config['path']['file_log_info'], $config['path']['file_log_error']);

// Assign valid tables
$configTables = $config['database']['table'];
