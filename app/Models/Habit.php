<?php

namespace App\Models;

use Core\Auth\Auth;
use Core\Base\BaseModel;

class Habit extends BaseModel
{
    protected string $table_name = 'habits';

    public static function create(array $request): void
    {
        static::query()->insert([
            'name' => $request['name'],
            'value_type' => $request['value_type'],
            'category_id' => $request['category_id'],
            'min_value' => $request['min_value'],
            'order' => $request['order'],
            'active' => $request['active'],
            'measurement' => $request['measurement'],
            'is_productive' => $request['is_productive'],
            'points' => $request['points'],
            'parent_id' => (int)$request['parent_id'],
            'user_id' => Auth::getAuthenticatedUserId()
        ]);
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
        static::query()->update($id, [
            'name' => $request['name'],
            'value_type' => $request['value_type'],
            'category_id' => $request['category_id'],
            'min_value' => $request['min_value'],
            'order' => $request['order'],
            'active' => $request['active'],
            'measurement' => $request['measurement'],
            'is_productive' => $request['is_productive'],
            'parent_id' => (int)$request['parent_id'],
            'points' => $request['points']
        ]);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }

    public static function allInCategoryId(int $category_id): bool|array
    {
        return static::query()
            ->select()
            ->where(['category_id' => $category_id, 'active' => 1])
            ->orderBy('order')
            ->fetchAll();
    }

    public static function getByName(string $string): bool|array
    {
        return static::query()
            ->select()
            ->where([
                'user_id' => Auth::getAuthenticatedUserId(),
                'name' => $string
            ])
            ->fetch();
    }
}