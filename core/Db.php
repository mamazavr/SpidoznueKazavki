<?php

namespace Core;

use PDO;

class Db
{
    protected static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (is_null(self::$instance)) {
            try {
                $dsn = "mysql:host=localhost;port=33061;dbname=mvc_db";
                $options = [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];

                self::$instance = new PDO(
                    $dsn,
                    'root',
                    'secret',
                    $options
                );
            } catch (\PDOException $e) {
                throw new \Exception("Database connection error: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
