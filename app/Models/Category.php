<?php

namespace App\Models;

use Core\Auth\Auth;
use Core\Base\BaseModel;

class Category extends BaseModel
{
    protected static string $table = 'category';

    public static function create(array $request): void
    {
        static::query()->insert([
            'name' => $request['name'],
            'color' => $request['color'],
            'order' => $request['order'],
            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function all(): bool|array
    {
        return static::query()
            ->select()
            ->where(['user_id' => Auth::getAuthenticatedUserId()])
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
            'user_id' => Auth::getAuthenticatedUserId()
        ]);
    }

    public static function delete(int $id): void
    {
        static::query()->destroy($id);
    }

    public static function allWithHabits(): array
    {
        $categories = static::all();

        if (!empty($categories)) {
            foreach ($categories as $k => &$category) {
                $habits = Habit::allInCategoryId($category['id']);

                $category['habits'] = [];

                if (!empty($habits)) {
                    $category['habits'] = $habits;
                }
            }
        } else {
            $categories = [];
        }

        return $categories;
    }

}