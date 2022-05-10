<?php

namespace Core;

class Auth {
    public static function login(array $user): void
    {
        $_SESSION['logged_in'] = true;
    }

    public static function logout(): void
    {
        $_SESSION['logged_in'] = false;
    }

    public static function userLoggedIn() {
        return $_SESSION['logged_in'];
    }
}