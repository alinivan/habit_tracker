<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Routine;
use App\Models\RoutineCategory;
use Core\Components\Habit\HabitRenderer;
use Core\Database\Db;
use Core\View\ViewManager;

class RoutineService extends ViewManager
{
    public function getRoutineView(): string
    {
        $routineCategories = RoutineCategory::all();
        $routine = array_pluck(Routine::all(), 'routine_category_id');

        $habitsValues = DashboardService::getHabitsValuesOfTodayWithRoutineCategory();

        foreach ($routineCategories as &$routineCategory) {
            if (empty($routine[$routineCategory['id']])) {
                continue;
            }
            $html = '';
            $routineCategory['routines'] = $routine[$routineCategory['id']];

            $array = [];
            foreach ($routine[$routineCategory['id']] as $item) {
                $array[] = '?';
            }

            $habits = DB::query("select * from habits where id in (".implode(',', $array).")", array_column($routine[$routineCategory['id']], 'habit_id'))->fetchAll();

            $targetValues = array_column($routine[$routineCategory['id']], 'target_value', 'habit_id');

            foreach ($habits as $habit) {
                $html .= (new HabitRenderer())->render($habit, @$habitsValues[$routineCategory['id']][$habit['id']], @$targetValues[$habit['id']], $routineCategory['id']);
            }

            $routineCategory['html'] = $html;
        }

        return $this->renderView('app/routine/view.html.twig', [
            'routineCategories' => $routineCategories,
            'habits' => array_remap(Habit::all(), 'id')
        ]);
    }
}