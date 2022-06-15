<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;

class Habit
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO habits (name, value_type, category_id, min_value, active, is_productive, points, user_id) VALUES (?,?,?,?,?,?)", [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['active'], $request['is_productive'], $request['points'], Auth::getUserId()]);
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT * FROM habits where user_id=?", [Auth::getUserId()])->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return DB::query("SELECT * FROM habits WHERE id=?", [$id])->fetch();
    }

    public static function update(int $id, array $request): void
    {
        DB::query("UPDATE habits SET name=?, value_type=?, category_id=?, min_value=?, active=?, is_productive=?, points=? WHERE id=?", [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['active'], $request['is_productive'], $request['points'], $id]);
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

    public static function getByName(string $string): bool|array
    {
        return DB::query("SELECT * FROM habits WHERE user_id=? and `name`=?", [Auth::getUserId(), $string])->fetch();
    }
}