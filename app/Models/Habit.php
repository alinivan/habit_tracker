<?php

namespace App\Models;

use Core\Database\Db;

class Habit
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO habits (name, value_type, category_id, min_value, active, user_id) VALUES (?,?,?,?,?,?)", [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['active'], 1]);
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
        DB::query("UPDATE habits SET name=?, value_type=?, category_id=?, min_value=?, active=? WHERE id=?", [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['active'], $id]);
    }

    public static function destroy(int $id): void
    {
        DB::query("DELETE FROM habits WHERE id=?", [$id]);
    }

    public static function allByCategoryId(int $id): bool|array
    {
        return DB::query("SELECT * FROM habits WHERE category_id=? and active=1", [$id])->fetchAll();
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