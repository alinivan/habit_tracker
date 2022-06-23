<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Model;
use Core\Helpers\Date;

class Tracker extends Model
{
    protected string $table_name = 'tracker';
    private string $date_ymd = "if (HOUR(`date`) < '".START_HOUR."', DATE_SUB(DATE(`date`), INTERVAL 1 DAY), DATE(`date`)) as date_ymd";

    public function create(array $request): void
    {
        $this->insert([
            'habit_id' => $request['habit_id'],
            'date' => $request['date'],
            'value' => round($request['value'], 1)
        ]);
    }

    public function whereInHabits(): Model
    {
        return (new Habit())
            ->select('id')
            ->where([
                'user_id' => Auth::getUserId()
            ]);
    }

    public function getToday(): bool|array
    {
        return $this
            ->select()
            ->whereIn('habit_id', $this->whereInHabits())
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

    public function getFromTo(string $from, string $to): bool|array
    {
        $from = Date::addStartHour($from);
        $to = Date::addStartHour($to);

        $start_date = $from;
        $end_date = $to;

        return $this
            ->select("*, $this->date_ymd")
            ->whereIn('habit_id', $this->whereInHabits())
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

    public function getTodayWithHabits(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        return $this
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

    public function all(): bool|array
    {
        return $this
            ->select("*, $this->date_ymd")
            ->whereIn('habit_id', $this->whereInHabits())
            ->where([
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date', 'desc')
            ->fetchAll();
    }

    public function getByHabitId(int $habit_id): bool|array
    {
        return $this
            ->select("*, $this->date_ymd")
            ->where([
                'habit_id' => $habit_id,
                'value' => [
                    '>' => 0
                ]
            ])
            ->orderBy('date')
            ->fetchAll();
    }

    public function getTodayScore(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));

        return $this
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

    public function getAvgScore(): bool|array
    {
        $end_date = date('Y-m-d ' . START_HOUR);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '-7 day'));

        return $this
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

    public function getTodayStartHour(): bool|array
    {
        $start_date = date('Y-m-d ' . START_HOUR);

        return $this
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

    public function getLastValue(int $habit_id): bool|array
    {
        return $this
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