<?php

namespace Core\Routing;

class Router
{
    public static function parseUrl($controller_method, $id = null)
    {
        $controller = explode(':', $controller_method)[0];
        $method = explode(':', $controller_method)[1];

        $controller = "App\\Controllers\\$controller";

        if (is_null($id)) {
            return (new $controller())->$method();
        }

        return (new $controller())->$method($id);
    }
}