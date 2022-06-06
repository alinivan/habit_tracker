<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;

class DashboardController extends AbstractController
{
    public function index()
    {
        $habits = Habit::all();
        $tracker = Tracker::getToday();

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

        echo $this->renderView('app/dashboard/index.html.twig', ['habits' => $habits, 'tracker' => $tracker]);
    }
}