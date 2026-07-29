<?php

// SIMS Application Configuration

define('APP_NAME', 'Smart Inventory Management System');
define('APP_SHORT_NAME', 'SIMS');
define('APP_VERSION', '1.0.0');

// Base URL calculation or default
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
define('BASE_URL', $protocol . $domainName);

// Directory Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads/products');
define('UPLOAD_URL', BASE_URL . '/uploads/products');

// Timezone
date_default_timezone_set('UTC');

// Session Config
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}
