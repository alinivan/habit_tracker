<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;
use Core\Database\Db;

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

        $habits_for_graph = ['meditation', 'sport', 'eating', 'proteins', 'no m'];
        $graphs = [];

        $date_range = dateRange();

        foreach ($habits_for_graph as $habit_name) {
            $habit_tracker = Tracker::getByHabitId(Habit::getByName($habit_name)['id']);

            $tracker_by_date = array_pluck($habit_tracker, 'date_ymd');

            foreach ($date_range as $date) {
                $graphs[$habit_name][$date] = 0;

                if (isset($tracker_by_date[$date])) {
                    $graphs[$habit_name][$date] = array_sum(array_column($tracker_by_date[$date], 'value'));
                }

            }
        }

        $habit_points = array_column(Habit::all(), 'points', 'id');
        $productivity_tracker = array_pluck(Tracker::all(), 'date_ymd');

        foreach ($date_range as $date) {
            $graphs['productivity'][$date] = 0;

            if (isset($productivity_tracker[$date])) {
                foreach ($productivity_tracker[$date] as $productivity_habits) {
                    $graphs['productivity'][$date] += $productivity_habits['value'] * $habit_points[$productivity_habits['habit_id']];
                }
            }
        }

        $routine_items = DB::query("SELECT * FROM routine_items ri inner join habits h on (ri.habit_id = h.id) ")->fetchAll();

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => $categories,
            'tracker' => $tracker,
            'habits' => $habits,
            'routine' => [
                'name' => 'Routine v1',
                'items' => $routine_items
            ],
            'productive_hours' => $productive_hours,
            'graph' => [
                'meditation' => [
                    'labels' => array_keys($graphs['meditation']),
                    'data' => array_values($graphs['meditation'])
                ],
                'sport' => [
                    'labels' => array_keys($graphs['sport']),
                    'data' => array_values($graphs['sport'])
                ],
                'eating' => [
                    'labels' => array_keys($graphs['eating']),
                    'data' => array_values($graphs['eating'])
                ],
                'proteins' => [
                    'labels' => array_keys($graphs['proteins']),
                    'data' => array_values($graphs['proteins'])
                ],
                'no m' => [
                    'labels' => array_keys($graphs['no m']),
                    'data' => array_values($graphs['no m'])
                ],
                'productivity' => [
                    'labels' => array_keys($graphs['productivity']),
                    'data' => array_values($graphs['productivity'])
                ]
            ]
        ]);
    }
}