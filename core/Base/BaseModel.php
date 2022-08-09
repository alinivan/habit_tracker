<?php

namespace Core\Base;

use Core\Database\QueryBuilder\MySQLQueryBuilder;
use Core\Database\QueryBuilder\PostgreSQLQueryBuilder;
use Core\Database\QueryBuilder\SqlQueryBuilderInterface;

abstract class BaseModel
{
    public static function query(): SqlQueryBuilderInterface
    {
        if ($_ENV['DB_CONNECTION'] === 'pgsql') {
            return new PostgreSQLQueryBuilder(static::$table);
        }

        return new MySQLQueryBuilder(static::$table);
    }
}