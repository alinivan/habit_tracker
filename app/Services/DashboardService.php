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


}