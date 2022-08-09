<?php

namespace App\Models;

use Core\Base\BaseModel;

class Routine extends BaseModel
{
    protected static string $table = 'routine';

    public static function create(array $request): void
    {
        static::query()->insert([
//            'name' => $request['name'],
//            'color' => $request['color'],
//            'order' => $request['order'],
//            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function all(): bool|array
    {
        return static::query()
            ->select()
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
//            'name' => $request['name'],
//            'color' => $request['color'],
//            'order' => $request['order'],
//            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }
}