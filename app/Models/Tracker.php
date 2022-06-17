<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;

class Tracker
{
    public static function insert(array $request): void
    {
        DB::query("INSERT INTO tracker (habit_id, date, value) VALUES (?,?,?)", [$request['habit_id'], $request['date'], round($request['value'], 1)]);
    }

    public static function getToday(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR . ':00');
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));
        return DB::query("SELECT * FROM tracker WHERE habit_id in (select id from habits where user_id=?) and `date`>=? and `date`<=? and value > 0 ORDER BY `date` asc", [Auth::getUserId(), $start_date, $end_date])->fetchAll();
    }

    public static function getTodayWithHabits(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR . ':00');
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        $sql = "SELECT 
                    sum(value) as sum
                FROM
                    tracker t
                INNER JOIN habits h on (h.id= t.habit_id)
                WHERE
                    h.is_productive = 1 AND
                    h.value_type = 'number' AND 
                    h.user_id = ? AND
                    `date` >= ? AND
                    `date` <= ? AND
                    value > 0";

        return DB::query($sql, [Auth::getUserId(), $start_date, $end_date])->fetch();
    }

    public static function all(): bool|array
    {
        $start_hour = (int)START_HOUR;
        return DB::query("SELECT *, if (HOUR(`date`) < '$start_hour', DATE_SUB(DATE(`date`), INTERVAL 1 DAY), DATE(`date`)) as date_ymd FROM tracker where habit_id in (select id from habits where user_id = ".Auth::getUserId().") and value > 0 ORDER BY `date` desc")->fetchAll();
    }

    public static function getByHabitId(int $habit_id): bool|array
    {
        $start_hour = (int)START_HOUR;
        return DB::query("SELECT *, if (HOUR(`date`) < '$start_hour', DATE_SUB(DATE(`date`), INTERVAL 1 DAY), DATE(`date`)) as date_ymd FROM tracker where habit_id=? and value > 0 ORDER BY `date` asc", [$habit_id])->fetchAll();
    }

    public static function getTodayScore(): bool|array
    {

        $start_date = date('Y-m-d ' . START_HOUR . ':00');
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        $sql = "SELECT 
                    sum(value * points) as score
                FROM
                    tracker t
                INNER JOIN habits h on (h.id= t.habit_id)
                WHERE
                    h.user_id = ? AND
                    `date` >= ? AND
                    `date` <= ? AND
                    value > 0
                GROUP BY h.id";


        return DB::query($sql, [Auth::getUserId(), $start_date, $end_date])->fetchAll();
    }

    public static function getAvgScore(): bool|array
    {
        $end_date = date('Y-m-d ' . START_HOUR . ':00');
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '-7 day'));

        $sql = "SELECT 
                    sum(value * points) as score
                FROM
                    tracker t
                INNER JOIN habits h on (h.id= t.habit_id)
                WHERE
                    h.user_id = ? AND
                    `date` >= ? AND
                    `date` <= ? AND
                    value > 0
                GROUP BY h.id";

        return DB::query($sql, [Auth::getUserId(), $start_date, $end_date])->fetchAll();
    }
}