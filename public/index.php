<?php


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


// Autoloader
require_once '../vendor/autoload.php';

// Helper functions
require_once '../functions.php';

// Load Config
require_once '../config/config.php';

// Routes
require_once '../routes/web.php';

// Router
require_once '../core/Router.php';
