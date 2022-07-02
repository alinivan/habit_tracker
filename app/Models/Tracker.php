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
                'user_id' => Auth::getAuthenticatedUserId()
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

    public static function getSumOfTodayProductiveMinutes(): int
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        $return = static::query()
            ->select('sum(value) as sum')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id = t.habit_id')
            ->where([
                'h.is_productive' => 1,
                'h.value_type' => 'number',
                'h.user_id' => Auth::getAuthenticatedUserId(),
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->fetch();

        return !empty($return['sum']) ? $return['sum'] : 0;
    }

    public static function all(bool $weekly = false): array
    {
        $select = static::$date_ymd;

        if ($weekly) {
            $select = static::$date_week;
        }

        $return = static::query()
            ->select("*, " . $select)
            ->whereIn('habit_id', static::whereInHabits())
            ->where([
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date', 'desc')
            ->fetchAll();

        return !empty($return) ? $return : [];
    }

    public static function getByHabitId(int $habit_id, string $start_date = ''): bool|array
    {
        $where = [
            'habit_id' => $habit_id,
            'value' => [
                '>' => 0
            ]
        ];

        if ($start_date) {
            $where['date'] = [
                '>=' => $start_date
            ];
        }
        return static::query()
            ->select("*, " . static::$date_ymd)
            ->where($where)
            ->orderBy('date')
            ->fetchAll();
    }

    public static function getSumOfPointsLast7Days(): int
    {
        $end_date = date('Y-m-d ' . START_HOUR);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '-7 day'));

        $return = static::query()
            ->select('sum(value * points) as score')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id= t.habit_id')
            ->where([
                'h.user_id' => Auth::getAuthenticatedUserId(),
                'date' => [
                    '>=' => $start_date,
                    '<=' => $end_date,
                ],
                'value' => [
                    '>' => 0
                ]
            ])
            ->fetch();

        return !empty($return['score']) ? (int)$return['score'] : 0;
    }

    public static function getTodayStartHour(): string
    {
        $start_date = date('Y-m-d ' . START_HOUR);

        $return = static::query()
            ->select("TIME_FORMAT(`date`, '%H:%i') as hour")
            ->whereIn('habit_id', static::whereInHabits())
            ->where([
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

        return !empty($return['hour']) ? $return['hour'] : '--:--';
    }

    public static function getLastInsertedValueOfHabit(int $habit_id): float
    {
        $return = static::query()
            ->select('value')
            ->from('tracker', 't')
            ->join(['habits', 'h'], 'h.id = t.habit_id')
            ->where([
                'h.user_id' => Auth::getAuthenticatedUserId(),
                'h.id' => $habit_id,
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date', 'desc')
            ->limit(1)
            ->fetch();

        return !empty($return['value']) ? $return['value'] : 0;
    }
}