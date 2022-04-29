<?php

namespace Core;

class Router {
    public static function parseUrl($uri, $controller_method) {
        $controller = explode(':', $controller_method)[0];
        $method = explode(':', $controller_method)[1];

        $controller = "App\\Controllers\\$controller";
        return (new $controller())->$method();
    }
}