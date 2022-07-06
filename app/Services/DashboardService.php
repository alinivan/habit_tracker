<?php

namespace App\Services;

use App\Models\Tracker;

class DashboardService
{
    public static function getHabitsValuesOfToday(): array
    {
        $tracker = Tracker::getToday();

        $habits = [];
        foreach ($tracker as $v) {
            @$habits[$v['habit_id']] += $v['value'];
        }

        return $habits;
    }

    public static function getHabitsValuesOfTodayWithRoutineCategory(): array
    {
        $tracker = Tracker::getToday();
        $habits = [];

        foreach ($tracker as $v) {
            if ($v['routine_category_id']) {
                @$habits[$v['routine_category_id']][$v['habit_id']] += $v['value'];
            }
        }

        return $habits;
    }


}