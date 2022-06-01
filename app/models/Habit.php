<?php

namespace App\Models;

use Core\Database\Db;

class Habit
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO HABITS (name, value_type, user_id) VALUES (?,?,?)", [$request['name'], $request['value_type'], 1]);
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT * FROM HABITS")->fetchAll();
    }

    public static function get(int $id): bool|array
    {
        return DB::query("SELECT * FROM HABITS WHERE id=?", [$id])->fetch();
    }

    public static function update(int $id, array $request) {
        DB::query("UPDATE HABITS SET name=?, value_type=? WHERE id=?", [$request['name'], $request['value_type'], $id]);
    }

    public static function destroy(int $id): void
    {
        DB::query("DELETE FROM HABITS WHERE id=?", [$id]);
    }

}