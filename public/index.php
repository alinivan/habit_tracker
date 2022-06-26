<?php

define('APP_ROOT', dirname(__FILE__, 2));

use App\Models\Habit;
use Core\Application;
use Core\Database\TestDb;
use DI\ContainerBuilder;

// Autoloader
require_once '../vendor/autoload.php';

/*$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(APP_ROOT.'/config/php_di.php');
$container = $containerBuilder->build();




$container->get(Habit::class);*/




// Run app
$app = new Application();
//$app->init();
//$app->run();
$app->init()
    ->run();
