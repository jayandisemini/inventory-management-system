<?php

// Database Configuration Settings

return [
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'sims_db',
    'username' => 'root',
    'password' => '', // Default XAMPP MariaDB/MySQL password is blank
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
