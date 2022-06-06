<?php

namespace App\Models;

use Core\Database\Db;

class Tracker
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO tracker (habit_id, date, value) VALUES (?,?,?)", [$request['habit_id'], $request['date'], (int)$request['value']]);
    }

    public static function getToday(): bool|array
    {
        return DB::query("SELECT * FROM tracker WHERE `date`>=? and `date`<=? ORDER BY `date` asc", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->fetchAll();
    }
}