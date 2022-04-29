<?php

namespace Core;

class Route extends Router {
    public static function get($uri, $controller_method) {
        if ($uri === $_SERVER['REQUEST_URI']) {
            self::parseUrl($uri, $controller_method);
        }
    }

    public static function post($uri, $controller_method) {

    }




}