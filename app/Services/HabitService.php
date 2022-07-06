<?php

namespace App\Services;

use Core\View\ViewManager;

class HabitService extends ViewManager
{
    public static function habitIsProductive(array $habit): bool
    {
        return $habit['is_productive'];
    }

    public static function habitSupportsTimeInterval(array $habit): bool
    {
        return $habit['is_productive'] && $habit['value_type'] == 'number';
    }

    public function getHabitHtml(array $habit, ?float $trackerValue = 0, ?float $targetValue = 0, int $routineCategoryId = 0): string
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