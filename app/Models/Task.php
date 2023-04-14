<?php

namespace App\Models;

use Core\Auth\Auth;
use Core\Base\BaseModel;

class Task extends BaseModel
{
    protected static string $table = 'task';

    public static function create(array $request): void
    {
        static::query()->insert([
            'name' => $request['name'],
            'done' => (int)$request['done'],
            'date_start' => $request['date_start'],
            'date_end' => $request['date_end'],
            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function all(): bool|array
    {
        return static::query()
            ->select()
            ->orderBy('date_start', 'desc')
            ->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return static::query()
            ->select()
            ->where(['id' => $id])
            ->fetch();
    }

    public static function modify(int $id, array $request): void
    {
        static::query()->update($id, [
            'name' => $request['name'],
            'done' => (int)$request['done'],
            'date_start' => $request['date_start'],
            'date_end' => $request['date_end'],
            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }
}