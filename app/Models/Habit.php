<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;

class Habit
{
    public static function insert(array $request): void
    {
        $sql = "INSERT INTO habits (name, value_type, category_id, min_value, `order`, active, is_productive, points, user_id) VALUES (?,?,?,?,?,?,?,?,?)";
        DB::query($sql, [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['order'], $request['active'], $request['is_productive'], $request['points'], Auth::getUserId()]);
    }

    public static function all(): bool|array
    {
        $sql = "SELECT * FROM habits where user_id=?";
        return DB::query($sql, [Auth::getUserId()])->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        $sql = "SELECT * FROM habits WHERE id=?";
        return DB::query($sql, [$id])->fetch();
    }

    public static function update(int $id, array $request): void
    {
        $sql = "UPDATE habits SET name=?, value_type=?, category_id=?, min_value=?, `order`=?, active=?, is_productive=?, points=? WHERE id=?";
        DB::query($sql, [$request['name'], $request['value_type'], $request['category_id'], $request['min_value'], $request['order'], $request['active'], $request['is_productive'], $request['points'], $id]);
    }

    public static function destroy(int $id): void
    {
        $sql = "DELETE FROM habits WHERE id=?";
        DB::query($sql, [$id]);
    }

    public static function allByCategoryId(int $id): bool|array
    {
        $sql = "SELECT * FROM habits WHERE category_id=? and active=1 order by `order`";
        return DB::query($sql, [$id])->fetchAll();
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
        $sql = "SELECT * FROM habits WHERE user_id=? and `name`=?";
        return DB::query($sql, [Auth::getUserId(), $string])->fetch();
    }
}