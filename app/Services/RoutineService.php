<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Routine;
use App\Models\RoutineCategory;
use Core\Database\Db;
use Core\View\ViewManager;

class RoutineService extends ViewManager
{
    public function getRoutineView(): string
    {
        $routineCategories = RoutineCategory::all();
        $routine = array_pluck(Routine::all(), 'routine_category_id');

        // de preluat habit values in funectie de routine_category_id
        $habitsValues = DashboardService::getHabitsValuesOfToday();

        foreach ($routineCategories as &$routineCategory) {
            $html = '';
            $routineCategory['routines'] = $routine[$routineCategory['id']];

            $array = [];
            foreach ($routine[$routineCategory['id']] as $item) {
                $array[] = '?';
            }

            $habits = DB::query("select * from habits where id in (".implode(',', $array).")", array_column($routine[$routineCategory['id']], 'habit_id'))->fetchAll();

            $targetValues = array_column($routine[$routineCategory['id']], 'target_value', 'habit_id');

            foreach ($habits as $habit) {
                // to add $tracker_value, $target_value
                $html .= (new HabitService())->getHabitHtml($habit, @$habitsValues[$habit['id']], @$targetValues[$habit['id']]);
            }

            $routineCategory['html'] = $html;
        }

        return $this->renderView('app/routine/view.html.twig', [
            'routineCategories' => $routineCategories,
            'habits' => array_remap(Habit::all(), 'id')
        ]);
    }
}