<?php

namespace App\models;

use Core\Database\Db;

class Tracker
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO TRACKER (habit_id, date, value) VALUES (?,?,?)", [$request['habit_id'], $request['date'], (int)$request['value']]);
    }

    public static function getToday(): bool|array
    {
        return DB::query("SELECT * FROM TRACKER WHERE `date`>=? and `date`<=?", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->fetchAll();
    }
}