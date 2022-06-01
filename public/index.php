<?php

use Core\Application;

// Autoloader
require_once '../vendor/autoload.php';

// Config
require_once '../config/config.php';

// Show errors for development
if (!SERVER_LIVE) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Start session
session_start();

// Run app
$app = new Application();
$app->run();
