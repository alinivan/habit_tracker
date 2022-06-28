<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Tracker;

class ChartService
{
    public static function productiveDataset(): array
    {
        $tracker = array_pluck(Tracker::all(), 'date_ymd');
        $habits = array_remap(Habit::all(), 'id');
        $date_range = dateRange();

        $productive_dataset = [];

        foreach ($date_range as $date) {
            if (!empty($tracker[$date])) {
                foreach ($tracker[$date] as $tracker_item) {
                    $habit = $habits[$tracker_item['habit_id']];

                    if (HabitService::habitIsProductive($habit)) {
                        @$productive_dataset[$date] += (int)$habit['points'] * $tracker_item['value'];
                    }
                }
            } else {
                $productive_dataset[$date] = 0;
            }
        }

        return $productive_dataset;
    }
}