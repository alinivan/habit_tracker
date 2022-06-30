<?php

namespace App\Models;

use Core\Auth;
use Core\Base\BaseModel;
use Core\Database\QueryBuilder;
use Core\Helpers\Date;

class Tracker extends BaseModel
{
    protected string $table_name = 'tracker';
    private static string $date_ymd = "if (HOUR(`date`) < '".START_HOUR."', DATE_SUB(DATE(`date`), INTERVAL 1 DAY), DATE(`date`)) as date_ymd";
    private static string $date_week = "WEEK(`date`, 1) as date_ymd";

    public static function create(array $request): void
    {
        static::query()->insert([
            'habit_id' => $request['habit_id'],
            'date' => $request['date'],
            'value' => round($request['value'], 1)
        ]);
    }

    public static function whereInHabits(): QueryBuilder
    {
        return Habit::query()
            ->select('id')
            ->where([
                'user_id' => Auth::getUserId()
            ]);
    }

    public static function getToday(): bool|array
    {
        return static::query()
            ->select()
            ->whereIn('habit_id', static::whereInHabits())
            ->where([
                'date' => [
                    '>=' => Date::getStartAndEndDate()['start_date'],
                    '<=' => Date::getStartAndEndDate()['end_date'],
                ],
                'value' => [
                    '>' => 0
                ]])
            ->orderBy('date')
            ->fetchAll();
    }

    public static function getFromTo(string $from, string $to): bool|array
    {
        $from = Date::addStartHour($from);
        $to = Date::addStartHour($to);

        $start_date = $from;
        $end_date = $to;

        return static::query()
            ->select("*, " . static::$date_ymd)
            ->whereIn('habit_id', static::whereInHabits())
            ->where([
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date')
            ->fetchAll();
    }

    public static function getTodayWithHabits(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        return static::query()
            ->select('sum(value) as sum')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id = t.habit_id')
            ->where([
                'h.is_productive' => 1,
                'h.value_type' => 'number',
                'h.user_id' => Auth::getUserId(),
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->fetch();
    }

    public static function all(bool $weekly = false): bool|array
    {
        $select = static::$date_ymd;

        if ($weekly) {
            $select = static::$date_week;
        }

        return static::query()
            ->select("*, " . $select)
            ->whereIn('habit_id', static::whereInHabits())
            ->where([
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date', 'desc')
            ->fetchAll();
    }

    public static function getByHabitId(int $habit_id): bool|array
    {
        return static::query()
            ->select("*, " . static::$date_ymd)
            ->where([
                'habit_id' => $habit_id,
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date')
            ->fetchAll();
    }

    public static function getTodayScore(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        return static::query()
            ->select('sum(value * points) as score')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id= t.habit_id')
            ->where([
                'h.user_id' => Auth::getUserId(),
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ]
            ])
            ->groupBy('h.id')
            ->fetchAll();
    }

    public static function getAvgScore(): bool|array
    {
        $end_date = date('Y-m-d ' . START_HOUR);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '-7 day'));

        return static::query()
            ->select('sum(value * points) as score')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id= t.habit_id')
            ->where([
                'h.user_id' => Auth::getUserId(),
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->groupBy('h.id')
            ->fetchAll();
    }

    public static function getTodayStartHour(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);

        return static::query()
            ->select('date')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id = t.habit_id')
            ->where([
                'h.user_id' => Auth::getUserId(),
                'date' => [
                    '>=' => $start_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date')
            ->limit(1)
            ->fetch();
    }

    public static function getLastValue(int $habit_id): bool|array
    {
        return static::query()
            ->select('value')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id = t.habit_id')
            ->where([
                'h.user_id' => Auth::getUserId(),
                'h.id' => $habit_id,
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date', 'desc')
            ->limit(1)
            ->fetch();
    }
}