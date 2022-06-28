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

        $habits = array_remap(Habit::all(), 'id');

        foreach ($tracker as &$item) {
            $habit = $habits[$item['habit_id']];

            $hour = date('H:i', strtotime($item['date']));

            $item['habit_name'] = $habit['name'];
            $item['value_type'] = $habit['value_type'];
            $item['measurement'] = $habit['measurement'];
            $item['hour'] = $hour;

            if (HabitService::habitHasTimeInterval($habit)) {
                $minutes = (float)$item['value'];
                $start_hour = date('H:i', strtotime("- $minutes minutes", strtotime($item['date'])));

                $item['hour'] = "$start_hour - $hour";
            }
        }

        $tracker_by_date = array_pluck($tracker, 'date_ymd');
        krsort($tracker_by_date);

        if (!$timeline) {
            $tracker_by_date_compact = [];
            foreach ($tracker_by_date as $date => $habits_tracker) {
                foreach ($habits_tracker as $k => $v) {
                    @$tracker_by_date_compact[$date][$v['habit_name']] = [
                        'value' => $tracker_by_date_compact[$date][$v['habit_name']]['value'] + $v['value'],
                        'habit_name' => $v['habit_name'],
                        'value_type' => $v['value_type'],
                        'measurement' => $v['measurement']
                    ];
                }
            }
        }

        return $this->renderView('app/tracker/view.html.twig', [
            'dates' => $timeline ? $tracker_by_date : $tracker_by_date_compact,
            'timeline' => $timeline
        ]);
    }
}