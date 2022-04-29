<?php

namespace Core;

use mysqli;

class Database
{
    private mysqli $connection;
    private string $password, $username, $database, $host;
    private int $port;

    function __construct($auto_connect = true)
    {
        $this->host = DB_HOST;
        $this->database = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->port = DB_PORT;

        if ($auto_connect) $this->open();
    }

    function open()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
    }

    function close(): void
    {
        $this->connection->close();
    }

    function query($query): \mysqli_result|bool
    {
        return $this->connection->query($query);
    }

    function escape($string): string
    {
        return $this->connection->escape_string($string);
    }
}