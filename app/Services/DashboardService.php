<?php

namespace App\Services;

use App\Models\Tracker;

class DashboardService
{
    public static function getHabitsValuesOfToday(): array
    {
        $habits = [];
        $tracker = Tracker::getToday();

        foreach ($tracker as $v) {
            @$habits[$v['habit_id']] += $v['value'];
        }

        return $habits;
    }


}