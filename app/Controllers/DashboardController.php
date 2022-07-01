<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Tracker;
use App\Services\DashboardService;
use App\Services\TrackerService;
use Core\Base\BaseController;
use Core\Helpers\Date;

class DashboardController extends BaseController
{
    public function index()
    {
        $statsHtml = $this->getDashboardStatsHtml();
        $habitsValues = DashboardService::getHabitsValuesOfToday();

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => Category::allWithHabits(),
            'stats_html' => $statsHtml,
            'habits_values' => $habitsValues,
            'tracker_html' => (new TrackerService())->getTracker(Date::getStartAndEndDate()['start_date'], Date::getStartAndEndDate()['end_date']),
        ]);
    }

    private function getDashboardStatsHtml(): string
    {
        $sumOfTodayProductiveMinutes = Tracker::getSumOfTodayProductiveMinutes();
        $todayStartHour = Tracker::getTodayStartHour();

        $sumOfTodayProductiveHours = round($sumOfTodayProductiveMinutes / 60, 2);
        $averageDailyPointsLast7Days = round(Tracker::getSumOfPointsLast7Days() / 7, 2);
        $lastKgValue = Tracker::getLastInsertedValueOfHabit(18);

        return $this->renderView('app/dashboard/components/stats.html.twig', [
            'sumOfTodayProductiveHours' => $sumOfTodayProductiveHours,
            'todayStartHour' => $todayStartHour,
            'averageDailyPointsLast7Days' => $averageDailyPointsLast7Days,
            'lastKgValue' => $lastKgValue
        ]);
    }
}