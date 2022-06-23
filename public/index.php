<?php

define('APP_ROOT', dirname(__FILE__, 2));

use Core\Application;

// Autoloader
require_once '../vendor/autoload.php';

// Run app
$app = new Application();
$app->init()
    ->run();
