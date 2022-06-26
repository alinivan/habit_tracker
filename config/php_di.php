<?php

use App\Models\Habit;
use Core\Database\QueryBuilder;
use function DI\create;

return [
    'model' => create(QueryBuilder::class),
    'habit' => create(Habit::class)
];