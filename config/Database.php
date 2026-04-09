<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            // Modify these credentials to match your local PostgreSQL setup
            $host = 'localhost';
            $db   = 'fanikclean';
            $user = 'postgres';
            $pass = 'Mskumar@05'; 
            $port = '5432';

            $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
            try {
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (\PDOException $e) {
                die("Database Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
