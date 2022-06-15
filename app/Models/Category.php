<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;

class Category
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO category (name, color, user_id) VALUES (?,?,?)", [$request['name'], $request['color'], Auth::getUserId()]);
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT * FROM category where user_id=?", [Auth::getUserId()])->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return DB::query("SELECT * FROM category WHERE id=?", [$id])->fetch();
    }

    public static function update(int $id, array $request): void
    {
        DB::query("UPDATE category SET name=?, color=? WHERE id=?", [$request['name'], $request['color'], $id]);
    }

    public static function destroy(int $id): void
    {
        DB::query("DELETE FROM category WHERE id=?", [$id]);
    }
}