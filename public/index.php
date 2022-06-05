<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


use Core\Application;

// Autoloader
require_once '../vendor/autoload.php';

// Config
require_once '../config/config.php';

// Env
$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

// Show errors for development
//if (!$_ENV['SERVER_LIVE']) {

//}

// Start session
session_start();

// Run app
$app = new Application();
$app->run();
