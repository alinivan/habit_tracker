<?php

namespace Core\Database;

use PDO;

class DatabaseConnection
{
    public static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance($db = DB_NAME, $username = DB_USER, $password = DB_PASS, $host = DB_HOST, $port = DB_PORT, $options = []): PDO
    {
        if (self::$instance === null) {
            $default_options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            $options = array_replace($default_options, $options);
            $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

            self::$instance = new \PDO($dsn, $username, $password, $options);
        }

        return self::$instance;
    }

}