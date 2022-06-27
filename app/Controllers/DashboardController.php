<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;
use App\Services\DashboardComponentsService;
use App\Services\TrackerService;
use Core\Base\BaseController;
use Core\Helpers\Date;

class DashboardController extends BaseController
{
    public function index()
    {
        $habits = DashboardComponentsService::getHabitValuesOfToday();

        $stats = $this->getStats();

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => Habit::allWithCategory(),
            'stats_html' => $stats,
            'habits' => $habits,
            'tracker_html' => (new TrackerService())->getTracker(Date::getStartAndEndDate()['start_date'], Date::getStartAndEndDate()['end_date']),
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

        $productive_hours = round($tracker['sum'] / 60, 2);
        $avg_points = round(array_sum(array_column(Tracker::getAvgScore(), 'score')) / 7, 2);
        $kg = Tracker::getLastValue(18)['value'];

        return $this->renderView('app/dashboard/components/stats.html.twig', [
            'productive_hours' => $productive_hours,
            'start_hour' => $start_hour,
            'avg_points' => $avg_points,
            'kg' => $kg
        ]);
    }
}