<?php

namespace App\Services;

use App\Models\Tracker;

class DashboardComponentsService
{
    public static function getHabitValuesOfToday(): array
    {
        $habits = [];
        $tracker = Tracker::getToday();

        foreach ($tracker as $v) {
            @$habits[$v['habit_id']] += $v['value'];
        }

        return $habits;
    }
}