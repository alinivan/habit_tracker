<?php

namespace Core\Routing;

use Core\Auth\Auth;

class Router
{
    public static function parseUrl($controller_method, $id = null)
    {
        $controller = explode(':', $controller_method)[0];
        $method = explode(':', $controller_method)[1];

        if (in_array($controller, static::authMiddleware()) && !Auth::userIsLoggedIn()) {
            redirect('/login');
            exit;
        }

        $controller = "App\\Controllers\\$controller";

        if (is_null($id)) {
            return (new $controller())->$method();
        }

        return (new $controller())->$method($id);
    }

    public static function authMiddleware(): array
    {
        return [
            'DashboardController',
            'HabitController',
            'TrackerController',
            'CategoryController',
            'ProgressController'
        ];
    }
}