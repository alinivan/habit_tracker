<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Tracker;
use Core\View\ViewManager;

class TrackerService extends ViewManager
{
    public function getTracker(string $fromDate, string $toDate, bool $timeline = true): string
    {
        $tracker = Tracker::getFromTo($fromDate, $toDate);
        $habits = arrayRemap(Habit::all(), 'id');

        foreach ($tracker as &$trackerItem) {
            $habit = $habits[$trackerItem['habit_id']];

            $hour = date('H:i', strtotime($trackerItem['date']));

            $trackerItem['habit_name'] = $habit['name'];
            $trackerItem['value_type'] = $habit['value_type'];
            $trackerItem['measurement'] = $habit['measurement'];
            $trackerItem['hour'] = $hour;

            if (HabitService::habitSupportsTimeInterval($habit)) {
                $minutes = (float)$trackerItem['value'];
                $startHour = date('H:i', strtotime("- $minutes minutes", strtotime($trackerItem['date'])));

                $trackerItem['hour'] = "$startHour - $hour";
            }
        }

        $trackerByDate = arrayPluck($tracker, 'date_ymd');
        krsort($trackerByDate);

        if (!$timeline) {
            $trackerByDateCompact = [];
            foreach ($trackerByDate as $date => $habitsTracker) {
                foreach ($habitsTracker as $v) {
                    @$trackerByDateCompact[$date][$v['habit_name']] = [
                        'value' => $trackerByDateCompact[$date][$v['habit_name']]['value'] + $v['value'],
                        'habit_name' => $v['habit_name'],
                        'value_type' => $v['value_type'],
                        'measurement' => $v['measurement']
                    ];
                }
            }
        }

        return $this->renderView('app/tracker/view.html.twig', [
            'dates' => $timeline ? $trackerByDate : $trackerByDateCompact,
            'timeline' => $timeline
        ]);
    }
}