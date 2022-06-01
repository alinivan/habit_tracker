<?php

namespace Core\Routing;

class Router {
    public static function parseUrl($controller_method) {
        $controller = explode(':', $controller_method)[0];
        $method = explode(':', $controller_method)[1];

        $controller = "App\\Controllers\\$controller";
        return (new $controller())->$method();

    }

    public static function parseUrlWithId($controller_method, $id) {
        $controller = explode(':', $controller_method)[0];
        $method = explode(':', $controller_method)[1];

        $controller = "App\\Controllers\\$controller";
        return (new $controller())->$method($id);
    }
}