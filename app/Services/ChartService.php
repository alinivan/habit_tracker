<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Tracker;
use DateTime;

class ChartService
{
    public static function productiveDataset(bool $weekly = false): array
    {
        $tracker = array_pluck(Tracker::all($weekly), 'date_ymd');
        $habits = array_remap(Habit::all(), 'id');
        $date_range = dateRange('', '', 'weekly');
        $productive_dataset = [];

        foreach ($date_range as $date) {
            if (!empty($tracker[$date])) {
                foreach ($tracker[$date] as $tracker_item) {
                    $habit = $habits[$tracker_item['habit_id']];

                    if (HabitService::habitIsProductive($habit)) {
                        @$productive_dataset[$date] += (int)($habit['points'] * $tracker_item['value']);
                    }
                }
            } else {
                $productive_dataset[$date] = 0;
            }
        }

        return $productive_dataset;
    }

    public static function habitChart(string $habit_name): array
    {
        $habit = Habit::getByName($habit_name);
        $tracker = array_pluck(Tracker::getByHabitId($habit['id']), 'date_ymd');
        $date_range = dateRange();
        $productive_dataset = [];

        foreach ($date_range as $date) {
            if (!empty($tracker[$date])) {
                foreach ($tracker[$date] as $tracker_item) {
                    @$productive_dataset[$date] += $tracker_item['value'];
                }
            } else {
                $productive_dataset[$date] = 0;
            }
        }

        return $productive_dataset;
    }
}