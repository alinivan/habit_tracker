<?php

namespace App\Models;

use Core\Database\Db;

class Category
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO category (name) VALUES (?)", [$request['name']]);
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT * FROM category")->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return DB::query("SELECT * FROM category WHERE id=?", [$id])->fetch();
    }

    public static function update(int $id, array $request)
    {
        DB::query("UPDATE category SET name=?, value_type=? WHERE id=?", [$request['name'], $request['value_type'], $id]);
    }

    public static function destroy(int $id): void
    {
        DB::query("DELETE FROM category WHERE id=?", [$id]);
    }
}