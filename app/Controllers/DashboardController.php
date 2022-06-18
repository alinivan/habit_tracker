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

        $habits_for_graph = ['meditation', 'sport', 'eating', 'proteins', 'no m', 'kg'];
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

//        $routine_items = DB::query("SELECT * FROM routine_items ri inner join habits h on (ri.habit_id = h.id) ")->fetchAll();


        $stats = $this->getStats();

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => $categories,
            'stats_html' => $stats,
            'tracker' => $tracker,
            'habits' => $habits,
//            'routine' => [
//                'name' => 'Routine v1',
//                'items' => $routine_items
//            ],
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
                'kg' => [
                    'labels' => array_keys($graphs['kg']),
                    'data' => array_values($graphs['kg'])
                ],
                'productivity' => [
                    'labels' => array_keys($graphs['productivity']),
                    'data' => array_values($graphs['productivity'])
                ]
            ]
        ]);
    }

    private function getStats(): string
    {

        $tracker = Tracker::getTodayWithHabits();
        $start_date = Tracker::getTodayStartHour();

        $start_hour = '--:--';

        if ($start_date) {
            $start_hour = date('H:i', strtotime($start_date['date']));
        }

//        $points = array_sum(array_column(Tracker::getTodayScore(), 'score'));

        $productive_hours = round($tracker['sum'] / 60, 2);
        $avg_points = round(array_sum(array_column(Tracker::getAvgScore(), 'score')) / 7, 2);
        $kg = Tracker::getLastValue(18)['value'];

        return $this->renderView('app/dashboard/components/stats.html.twig', [
            'productive_hours' => $productive_hours,
//            'points' => $points,
            'start_hour' => $start_hour,
            'avg_points' => $avg_points,
            'kg' => $kg
        ]);
    }
}