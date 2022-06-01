<?php

namespace Core\Routing;

class Route extends Router
{
    private array $routes;
    private int $id = 0;
    private string $not_found;

    public function get($route, $controller_method)
    {
        $this->add($route, 'GET', $controller_method);
    }

    public function post($route, $controller_method)
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

    public function dissolve() {

        $reqUri = $this->getNormalizedRoute();

        if (isset($this->routes[$_SERVER['REQUEST_METHOD']][$reqUri])) {
            if ($this->id == 0) {
                self::parseUrl($this->routes[$_SERVER['REQUEST_METHOD']][$reqUri]);
            } else {
                self::parseUrlWithId($this->routes[$_SERVER['REQUEST_METHOD']][$reqUri], $this->id);
            }
        } else {
            self::parseUrl($this->not_found);
        }
    }

    public function notFound(string $controller_method) {
        $this->not_found = $controller_method;
    }

    public function uriWithoutParameters() {
        $reqUri = $_SERVER['REQUEST_URI'];

        if (str_contains($reqUri, '?')) {
            $reqUri = substr($reqUri, 0, strpos($reqUri, '?'));
        }

        return $reqUri;
    }

    public function getNormalizedRoute() {
        $reqUri = $this->uriWithoutParameters();

        preg_match_all('([1-9][0-9]*)', $reqUri, $id);

        if (!empty($id[0])) {
            $this->id = $id[0][0];
        }

        return preg_replace('([1-9][0-9]*)', '{id}', $reqUri);
    }
}