<?php

use App\Models\Habit;
use Core\Database\QueryBuilder\PostgreSQLQueryBuilder;
use function DI\create;

return [
    'model' => create(PostgreSQLQueryBuilder::class),
    'habit' => create(Habit::class)
];