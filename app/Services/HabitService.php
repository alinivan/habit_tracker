<?php

namespace App\Services;

class HabitService
{
    public static function habitIsProductive(array $habit): bool
    {
        return $habit['is_productive'];
    }

    public static function habitHasTimeInterval(array $habit): bool
    {
        return $habit['is_productive'] && $habit['value_type'] == 'number';
    }

}