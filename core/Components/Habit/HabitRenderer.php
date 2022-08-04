<?php

namespace Core\Components\Habit;

use Core\View\ViewManager;

class HabitRenderer extends ViewManager
{
    public function render(array $habit, ?float $trackerValue = 0, ?float $targetValue = 0, int $routineCategoryId = 0): string
    {
        if ($targetValue > 0) {
            $habit['min_value'] = $targetValue;
        }

        if ($trackerValue > 0) {
            $habit['tracker_value'] = $trackerValue;
        }

        if ($routineCategoryId > 0) {
            $habit['routine_category_id'] = $routineCategoryId;
        }

        return $this->renderView('app/habit/components/habit_item.html.twig', [
            'habit' => $habit
        ]);
    }
}