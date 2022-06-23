<?php

namespace Core\Routing;

use Core\Helpers\Url;

class Route extends Router
{
    private array $routes;
    private string $not_found;

    public function get(string $route, string $controller_method)
    {
        $this->add($route, 'GET', $controller_method);
    }

    public function post(string $route, string $controller_method)
    {
        $this->add($route, 'POST', $controller_method);
    }

    public function add(string $route, string $method, string $controller_method)
    {
        $this->routes[$method][$route] = $controller_method;
    }

    public function all(): array
    {
        return $this->routes;
    }

    public function dissolve()
    {
        $reqUri = Url::getNormalizedRoute($_SERVER['REQUEST_URI']);

        if (isset($this->routes[$_SERVER['REQUEST_METHOD']][$reqUri['uri']])) {
            self::parseUrl($this->routes[$_SERVER['REQUEST_METHOD']][$reqUri['uri']], $reqUri['param']);
        } else {
            self::parseUrl($this->not_found);
        }
    }

    public function notFound(string $controller_method)
    {
        $this->not_found = $controller_method;
    }
}