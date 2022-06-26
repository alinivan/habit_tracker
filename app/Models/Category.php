<?php

namespace App\Models;

use Core\Auth;
use Core\Base\BaseModel;

class Category extends BaseModel
{
    protected static string $table = 'category';
    protected string $table_name = 'category';

    public static function create(array $request): void
    {
        static::query()->insert([
            'name' => $request['name'],
            'color' => $request['color'],
            'order' => $request['order'],
            'user_id' => Auth::getUserId()
        ]);
    }

    public static function all(): bool|array
    {
        return static::query()
            ->select()
            ->where(['user_id' => Auth::getUserId()])
            ->orderBy('order')
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
            'color' => $request['color'],
            'order' => $request['order'],
            'user_id' => Auth::getUserId()
        ]);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }
}