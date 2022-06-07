<?php

namespace App\Models;

use Core\Database\Db;

class Habit
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO habits (name, value_type, user_id, category_id) VALUES (?,?,?,?)", [$request['name'], $request['value_type'], 1, $request['category_id']]);
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT * FROM habits")->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return DB::query("SELECT * FROM habits WHERE id=?", [$id])->fetch();
    }

    public static function update(int $id, array $request)
    {
        DB::query("UPDATE habits SET name=?, value_type=?, category_id=? WHERE id=?", [$request['name'], $request['value_type'], $request['category_id'], $id]);
    }

    public static function destroy(int $id): void
    {
        DB::query("DELETE FROM habits WHERE id=?", [$id]);
    }

    public static function allByCategoryId(int $id): bool|array
    {
        return DB::query("SELECT * FROM habits WHERE category_id=?", [$id])->fetchAll();
    }

    public static function allWithCategory(): bool|array
    {
        $categories = Category::all();

        foreach ($categories as $k => $v) {
            $categories[$k]['habits'] = Habit::allByCategoryId($v['id']);
        }

        return $categories;
    }
}