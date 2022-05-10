<?php

namespace Core\Routing;

class Route extends Router {
    public static function get($uri, $controller_method) {
        if ($uri === $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_METHOD'] === 'GET') {
            self::parseUrl($uri, $controller_method);
        }
    }

    public static function post($uri, $controller_method) {
        if ($uri === $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_METHOD'] === 'POST') {
            self::parseUrl($uri, $controller_method);
        }
    }

    public function broken() {

    }
}