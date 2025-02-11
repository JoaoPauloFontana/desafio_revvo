<?php

namespace RevvoApi\database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                $host = getenv('DB_HOST') ?: 'db';
                $dbname = getenv('DB_NAME') ?: 'revvo_db';
                $user = getenv('DB_USER') ?: 'revvo';
                $password = getenv('DB_PASSWORD') ?: 'password';
                $port = getenv('DB_PORT') ?: '5432';

                $dsn = "pgsql:host=$host;dbname=$dbname;port=$port";

                self::$connection = new PDO($dsn, $user, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}