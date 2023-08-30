<?php

// Include autoloader for classes
require __DIR__ . '/vendor/autoload.php';

// Load configuration values
$config = require __DIR__ . '/config.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$errors = $dotenv->load();
