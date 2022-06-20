<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Db;
use Core\Helpers\Date;

class Tracker
{
    static string $date_ymd = "if (HOUR(`date`) < '".START_HOUR."', DATE_SUB(DATE(`date`), INTERVAL 1 DAY), DATE(`date`)) as date_ymd";

    public static function insert(array $request): void
    {
        $sql = "INSERT INTO tracker (habit_id, date, value) VALUES (?,?,?)";
        DB::query($sql, [$request['habit_id'], $request['date'], round($request['value'], 1)]);
    }

    public static function getToday(): bool|array
    {
        $sql = "SELECT * FROM tracker WHERE habit_id in (select id from habits where user_id=?) and `date`>=? and `date`<=? and value > 0 ORDER BY `date` asc";

        return DB::query($sql, [Auth::getUserId(), Date::getStartAndEndDate()['start_date'], Date::getStartAndEndDate()['end_date']])->fetchAll();
    }

    public static function getFromTo(string $from, string $to): bool|array
    {
        $from = Date::addStartHour($from);
        $to = Date::addStartHour($to);

        $start_date = $from;
        $end_date = $to;

        $sql = "SELECT *, ".self::$date_ymd." FROM tracker WHERE habit_id in (select id from habits where user_id=?) and `date`>=? and `date`<=? and value > 0 ORDER BY `date` asc";

        return DB::query($sql, [Auth::getUserId(), $start_date, $end_date])->fetchAll();
    }

    public static function getTodayWithHabits(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
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
        $sql = "SELECT *, ".self::$date_ymd." FROM tracker where habit_id in (select id from habits where user_id = ?) and value > 0 ORDER BY `date` desc";
        return DB::query($sql, [Auth::getUserId()])->fetchAll();
    }

    public static function getByHabitId(int $habit_id): bool|array
    {
        $sql = "SELECT *, ".self::$date_ymd." FROM tracker where habit_id=? and value > 0 ORDER BY `date` asc";
        return DB::query($sql, [$habit_id])->fetchAll();
    }

    public static function getTodayScore(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
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
        $end_date = date('Y-m-d ' . START_HOUR);
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

    public static function getTodayStartHour()
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $sql = "SELECT 
                    date
                FROM
                    tracker t
                INNER JOIN habits h on (h.id= t.habit_id)
                WHERE
                    h.user_id = ? AND
                    `date` >= ? AND
                    value > 0
                order BY date asc 
                limit 1";

        return DB::query($sql, [Auth::getUserId(), $start_date])->fetch();
    }

    public static function getLastValue(int $habit_id)
    {
        $sql = "SELECT 
                    value
                FROM
                    tracker t
                INNER JOIN habits h on (h.id= t.habit_id)
                WHERE
                    h.user_id = ? AND
                    h.id = ? AND
                    value > 0
                order BY date desc 
                limit 1";

        return DB::query($sql, [Auth::getUserId(), $habit_id])->fetch();
    }
}