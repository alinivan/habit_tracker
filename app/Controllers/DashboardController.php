<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Habit;
use App\Models\Tracker;
use App\Services\TrackerService;
use Core\Helpers\Date;

class DashboardController extends AbstractController
{
    private Habit $habit;
    private Tracker $tracker;

    public function __construct()
    {
        $this->habit = new Habit();
        $this->tracker = new Tracker();
        parent::__construct();
    }
    
    public function index()
    {
        $categories = $this->habit->allWithCategory();
        $tracker = $this->tracker->getToday();

        $habits = [];
        foreach ($tracker as $v) {
            @$habits[$v['habit_id']] += $v['value'];
        }

        $habits_for_graph = ['meditation', 'sport', 'eating', 'proteins', 'no m', 'kg'];
        $graphs = [];

        $date_range = dateRange();

        foreach ($habits_for_graph as $habit_name) {
            $habit_tracker = $this->tracker->getByHabitId($this->habit->getByName($habit_name)['id']);

            $tracker_by_date = array_pluck($habit_tracker, 'date_ymd');

            foreach ($date_range as $date) {
                $graphs[$habit_name][$date] = 0;

                if (isset($tracker_by_date[$date])) {
                    $graphs[$habit_name][$date] = array_sum(array_column($tracker_by_date[$date], 'value'));
                }
            }
        }

        $habit_points = array_column($this->habit->all(), 'points', 'id');
        $productivity_tracker = array_pluck($this->tracker->all(), 'date_ymd');

        foreach ($date_range as $date) {
            $graphs['productivity'][$date] = 0;

            if (isset($productivity_tracker[$date])) {
                foreach ($productivity_tracker[$date] as $productivity_habits) {
                    $graphs['productivity'][$date] += $productivity_habits['value'] * $habit_points[$productivity_habits['habit_id']];
                }
            }
        }

        $stats = $this->getStats();

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => $categories,
            'stats_html' => $stats,
            'habits' => $habits,
            'tracker_html' => (new TrackerService())->getTracker(Date::getStartAndEndDate()['start_date'], Date::getStartAndEndDate()['end_date']),
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

        $tracker = $this->tracker->getTodayWithHabits();
        $start_date = $this->tracker->getTodayStartHour();

        $start_hour = '--:--';

        if ($start_date) {
            $start_hour = date('H:i', strtotime($start_date['date']));
        }

        $productive_hours = round($tracker['sum'] / 60, 2);
        $avg_points = round(array_sum(array_column($this->tracker->getAvgScore(), 'score')) / 7, 2);
        $kg = $this->tracker->getLastValue(18)['value'];

        return $this->renderView('app/dashboard/components/stats.html.twig', [
            'productive_hours' => $productive_hours,
            'start_hour' => $start_hour,
            'avg_points' => $avg_points,
            'kg' => $kg
        ]);
    }
}