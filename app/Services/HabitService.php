<?php

namespace App\Services;

use App\Models\Routine;
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

    public function getHabitHtml(array $habit, ?float $tracker_value = 0, ?float $target_value = 0): string
    {
        if ($target_value > 0) {
            $habit['min_value'] = $target_value;
        }

        if ($tracker_value > 0) {
            $habit['tracker_value'] = $tracker_value;
        }

        return $this->renderView('app/habit/components/habit_item.html.twig', [
            'habit' => $habit,
        ]);
    }

}