<?php

namespace Core;

use PDO;

class Db
{
    protected static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (is_null(self::$instance)) {
            $dsn = "mysql:host=" . config('db.host') . ";dbname=" . config('db.database');
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            self::$instance = new PDO(
                $dsn,
                config('db.user'),
                config('db.password'),
                $options
            );
        }

        return self::$instance;
    }
}
