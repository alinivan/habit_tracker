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

        /*foreach ($categories as $k => $v) {
            foreach ($v['habits'] as $k2 => $v2) {
                if ($v2['value_type'] === 'boolean') {

                } else {

                }
            }
        }*/

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

        echo $this->renderView('app/dashboard/index.html.twig', ['categories' => $categories, 'tracker' => $tracker]);
    }
}