<?php

class Database
{
    private static ?PDO $connection = null;
    public PDO $db;

    public function __construct()
    {
        if (self::$connection === null) {
            self::$connection = new PDO(
                'mysql:host=' . ENV['DB_HOST'] . ';dbname=' . ENV['DB_NAME'] . ';charset=utf8mb4',
                ENV['DB_USER'],
                ENV['DB_PASSWORD'],
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        }
        $this->db = self::$connection;
    }
}
