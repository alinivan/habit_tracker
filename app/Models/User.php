<?php

namespace App\Models;

use Core\Database\Db;

class User
{

    public static function getUserForLogin(array $request)
    {
        $sql = "SELECT * FROM users where username=? and password=?";
        return DB::query($sql, [$request['username'], md5($request['password'])])->fetch();
    }

    public static function register(array $request)
    {
        // Register
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        DB::query($sql, [$request['username'], md5($request['password'])]);

        // Return user
        return self::getUserByUsername($request['username']);
    }

    public static function getUserByUsername(string $username)
    {
        $sql = "SELECT * FROM users where username=?";

        return DB::query($sql, [$username])->fetch();
    }
}