<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Tracker;

class ChartService
{
    public static function productiveDataset(bool $weekly = false): array
    {
        $tracker = arrayPluck(Tracker::all($weekly), 'date_ymd');
        $habits = arrayRemap(Habit::all(), 'id');
        $dateRange = dateRange('', '', 'weekly');
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
        $tracker = arrayPluck(Tracker::getByHabitId($habit['id'], $startDate), 'date_ymd');
        $dateRange = dateRange($startDate);
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