<?php

namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    protected string $table_name = 'users';

    public function getUserForLogin(array $request): bool|array
    {
        return $this
            ->select()
            ->where([
                'username' => $request['username'],
                'password' => md5($request['password'])
            ])
            ->fetchAll();
    }

    public function register(array $request): bool|array
    {
        $this->insert([
            'username' => $request['username'],
            'password' => $request['password']
        ]);

        return self::getUserByUsername($request['username']);
    }

    public function getUserByUsername(string $username): bool|array
    {
        return $this
            ->select()
            ->where(['username' => $username])
            ->fetch();
    }
}