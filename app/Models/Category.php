<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;

class Category
{
    public static function insert(array $request): void
    {
        $sql = "INSERT INTO category (name, color, `order`, user_id) VALUES (?,?,?)";
        DB::query($sql, [$request['name'], $request['color'], $request['order'], Auth::getUserId()]);
    }

    public static function all(): bool|array
    {
        $sql = "SELECT * FROM category where user_id=? order by `order`";
        return DB::query($sql, [Auth::getUserId()])->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        $sql = "SELECT * FROM category WHERE id=?";
        return DB::query($sql, [$id])->fetch();
    }

    public static function update(int $id, array $request): void
    {
        $sql = "UPDATE category SET name=?, color=?, `order`=? WHERE id=?";
        DB::query($sql, [$request['name'], $request['color'], $request['order'], $id]);
    }

    public static function destroy(int $id): void
    {
        $sql = "DELETE FROM category WHERE id=?";
        DB::query($sql, [$id]);
    }
}