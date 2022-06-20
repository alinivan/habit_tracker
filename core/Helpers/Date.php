<?php

namespace Core\Helpers;

class Date
{
    public static function addStartHour(string $date): string
    {
        return date('Y-m-d', strtotime($date)) . ' ' . START_HOUR;
    }

    public static function getStartAndEndDate(): array
    {
        if (date('H') < (int)START_HOUR) {
            $end_date = date('Y-m-d ' . START_HOUR);
            $start_date = date('Y-m-d H:i:s', strtotime($end_date . '-1 day'));
        } else {
            $start_date = date('Y-m-d ' . START_HOUR);
            $end_date = date('Y-m-d H:i:s', strtotime($start_date . '+1 day'));
        }

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }
}