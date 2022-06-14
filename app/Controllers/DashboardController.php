<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;

class DashboardController extends AbstractController
{
    public function index()
    {
        $categories = Habit::allWithCategory();
        $tracker = Tracker::getToday();

        $habits = [];
        foreach ($tracker as $v) {
            @$habits[$v['habit_id']] += $v['value'];
        }

        foreach ($tracker as &$item) {
            $habit = Habit::get($item['habit_id']);
            $date = date('H:i', strtotime($item['date']));
            $minutes = (int)$item['value'];

            $item['habit'] = $habit;
            $item['hour'] = $date;

            if ($habit['value_type'] === 'number') {
                $item['start_hour'] = date('H:i', strtotime("- $minutes minutes", strtotime($item['date'])));
            }
        }

        $productive_hours = round(array_sum(array_column($tracker, 'value')) / 60, 2);

        echo $this->renderView('app/dashboard/index.html.twig', ['categories' => $categories, 'tracker' => $tracker, 'habits' => $habits, 'productive_hours' => $productive_hours]);
    }
}