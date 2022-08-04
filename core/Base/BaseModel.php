<?php

namespace Core\Base;

use Core\Database\QueryBuilder;

class BaseModel extends QueryBuilder
{
    public static function query(): static
    {
        return new static;
    }
}