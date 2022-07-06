<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Tracker;
use App\Services\DashboardService;
use App\Services\HabitService;
use App\Services\RoutineService;
use App\Services\TrackerService;
use Core\Base\BaseController;
use Core\Helpers\Date;

class DashboardController extends BaseController
{
    public function index()
    {
        $statsHtml = $this->getDashboardStatsHtml();
        $habitsValues = DashboardService::getHabitsValuesOfToday();

        $routineHtml = '';

        if (isset($_REQUEST['routine_view'])) {
            $routineHtml = (new RoutineService())->getRoutineView();
        }

        $categories = Category::allWithHabits();

        foreach ($categories as &$category) {
            $categoryHtml = '';
            foreach ($category['habits'] as $habit) {
                $categoryHtml .= (new HabitService())->getHabitHtml($habit, @$habitsValues[$habit['id']]);
            }
            $category['html'] = $categoryHtml;
        }

        echo $this->renderView('app/dashboard/index.html.twig', [
            'categories' => $categories,
            'routineHtml' => $routineHtml,
            'stats_html' => $statsHtml,
            'routineView' => isset($_REQUEST['routine_view']),
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