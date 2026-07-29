<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require ROOT_PATH . '/config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                // If database doesn't exist yet, attempt connecting without dbname to create it automatically
                try {
                    $dsnNoDb = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
                    $pdo = new PDO($dsnNoDb, $config['username'], $config['password'], $config['options']);
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    
                    self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
                } catch (PDOException $e2) {
                    die("Database Connection Error: " . htmlspecialchars($e2->getMessage()));
                }
            }
        }

        return self::$instance;
    }
}
