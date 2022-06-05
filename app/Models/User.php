<?php

namespace App\Models;

use Core\Database\Db;

class User {

    public static function getUserForLogin(array $request) {
        return DB::query("SELECT * FROM users where username=? and password=?", [$request['username'], md5($request['password'])])->fetch();
    }

    public static function register(array $request) {
        // Register
        DB::query("INSERT INTO users (username, password) VALUES (?, ?)", [$request['username'], md5($request['password'])]);

        // Return user
        return self::getUserByUsername($request['username']);
    }

    public static function getUserByUsername(string $username) {
        return DB::query("SELECT * FROM users where username=?", [$username])->fetch();
    }
}