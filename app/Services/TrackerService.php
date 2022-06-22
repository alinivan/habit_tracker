<?php

namespace App\Services;

use App\Controllers\AbstractController;
use App\Models\Habit;
use App\Models\Tracker;

class TrackerService extends AbstractController
{
    public function getTracker(string $fromDate, string $toDate, bool $timeline = true): string
    {
        $tracker = (new Tracker)->getFromTo($fromDate, $toDate);

        $habits = array_remap((new Habit)->all(), 'id');

        foreach ($tracker as &$item) {
            $habit = $habits[$item['habit_id']];

            $hour = date('H:i', strtotime($item['date']));

            $item['habit_name'] = $habit['name'];
            $item['value_type'] = $habit['value_type'];
            $item['measurement'] = $habit['measurement'];
            $item['hour'] = $hour;

            if ($habit['is_productive'] && $habit['value_type'] === 'number') {
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