<?php

namespace App\Models;

use Core\Auth\Auth;
use Core\Base\BaseModel;

class PageCategory extends BaseModel
{
    protected static string $table = 'pages_category';
    protected string $table_name = 'pages_category';

    public static function create(array $request): void
    {
        $request['user_id'] = Auth::getAuthenticatedUserId();
        static::query()->insert($request);
    }

    public static function all(): bool|array
    {
        return static::query()
            ->select()
            ->where(['user_id' => Auth::getAuthenticatedUserId()])
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
        static::query()->update($id, $request);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }
}