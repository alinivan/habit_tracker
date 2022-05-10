<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Core\Application;

// Autoloader
require_once '../vendor/autoload.php';

// Config
require_once '../config/config.php';

// Start session
session_start();

// Run app
$app = new Application();
$app->run();

// Destroy session
//session_destroy();


/*$fields = ['title' => 'sal', 'name' => 'nume'];
$rules = ['title' => 'required', 'name' => 'required'];
$request = new \Core\Request();
pre($request->validate($rules));*/