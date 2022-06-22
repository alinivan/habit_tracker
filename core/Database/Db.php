<?php

namespace Core\Database;

use PDOStatement;

class Db
{
    public static function query($sql, $args = NULL): bool|PDOStatement
    {
        $connection = DatabaseConnection::getInstance();

        if (!$args) {
            return $connection->query($sql);
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}