<?php

namespace Core;

class Auth
{
    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'authenticated' => true,
            'user_id' => $user['id']
        ];
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function userLoggedIn(): bool
    {
        return isset($_SESSION['user']['authenticated']) && $_SESSION['user']['authenticated'];
    }
}