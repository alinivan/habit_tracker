<?php

namespace Core\Database;

use PDO;

final class DatabaseConnection
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    public static function getInstance($options = []): PDO
    {
        $dbConnection = $_ENV['DB_CONNECTION'];
        $db = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];

        if (self::$instance === null) {
            $default_options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            $options = array_replace($default_options, $options);
            $dsn = "$dbConnection:host=$host;dbname=$db;port=$port;charset=utf8mb4";

            self::$instance = new PDO($dsn, $username, $password, $options);
        }

        return self::$instance;
    }

}