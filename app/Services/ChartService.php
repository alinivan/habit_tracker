<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Tracker;

class ChartService
{
    public static function productiveDataset(bool $weekly = false): array
    {
        $tracker = array_pluck(Tracker::all($weekly), 'date_ymd');
        $habits = array_remap(Habit::all(), 'id');
        $dateRange = date_range('', '', 'weekly');
        $productiveDataset = [];

        foreach ($dateRange as $date) {
            if (!empty($tracker[$date])) {
                foreach ($tracker[$date] as $trackerItem) {
                    $habit = $habits[$trackerItem['habit_id']];

                    if (HabitService::habitIsProductive($habit)) {
                        @$productiveDataset[$date] += (int)($habit['points'] * $trackerItem['value']);
                    }
                }
            } else {
                $productiveDataset[$date] = 0;
            }
        }

        return $productiveDataset;
    }

    public static function habitChart(string $habitName, string $startDate = ''): array
    {
        $habit = Habit::getByName($habitName);
        $tracker = array_pluck(Tracker::getByHabitId($habit['id'], $startDate), 'date_ymd');
        $dateRange = date_range($startDate);
        $productiveDataset = [];

        foreach ($dateRange as $date) {
            if (!empty($tracker[$date])) {
                foreach ($tracker[$date] as $trackerItem) {
                    @$productiveDataset[$date] += $trackerItem['value'];
                }
            } else {
                $productiveDataset[$date] = 0;
            }
        }

        return $productiveDataset;
    }
}