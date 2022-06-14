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
        $start_date = date('Y-m-d '.START_HOUR.':00');
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));
        return DB::query("SELECT * FROM tracker WHERE `date`>=? and `date`<=? and value > 0 ORDER BY `date` asc", [$start_date, $end_date])->fetchAll();
    }

    public static function all(): bool|array
    {
        return DB::query("SELECT *, DATE(`date`) date_ymd FROM tracker where value > 0 ORDER BY `date` desc")->fetchAll();
    }
}