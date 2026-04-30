<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            // Load environment variables if .env exists
            $envPath = __DIR__ . '/../.env';
            if (file_exists($envPath)) {
                $env = parse_ini_file($envPath);
                $host = $env['DB_HOST'] ?? 'localhost';
                $db   = $env['DB_NAME'] ?? 'fanikclean';
                $user = $env['DB_USER'] ?? 'postgres';
                $pass = $env['DB_PASS'] ?? '';
                $port = $env['DB_PORT'] ?? '5432';
            } else {
                // Fallback to defaults
                $host = 'localhost';
                $db   = 'fanikclean';
                $user = 'postgres';
                $pass = ''; 
                $port = '5432';
            }

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
