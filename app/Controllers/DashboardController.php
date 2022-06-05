<?php

namespace App\Controllers;

use App\Models\Habit;
use App\models\Tracker;

class DashboardController extends AbstractController
{
    public function index()
    {
        $habits = Habit::all();
        $tracker = Tracker::getToday();

        foreach ($tracker as &$item) {
            $item['habit_name'] = Habit::get($item['habit_id'])['name'];
            $item['hour'] = date('H:i', strtotime($item['date']));
        }

        echo $this->renderView('app/dashboard/index.html.twig', ['habits' => $habits, 'tracker' => $tracker]);
    }
}