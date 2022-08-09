<?php

namespace App\Models;

use Core\Base\BaseModel;

class User extends BaseModel
{
    protected static string $table = 'users';

    public static function getUserForLogin(array $request): bool|array
    {
        return static::query()
            ->select()
            ->where([
                'username' => $request['username'],
                'password' => md5($request['password'])
            ])
            ->fetch();
    }

    public static function register(array $request): bool|array
    {
        static::query()->insert([
            'username' => $request['username'],
            'password' => $request['password']
        ]);

        return static::getUserByUsername($request['username']);
    }

    public static function getUserByUsername(string $username): bool|array
    {
        return static::query()
            ->select()
            ->where(['username' => $username])
            ->fetch();
    }
}