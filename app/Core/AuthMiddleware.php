<?php

namespace App\Core;

class AuthMiddleware {
    public static function check(): bool {
        if (!isset($_SESSION['user'])) {
            (new Response())->redirect('/login');
            exit;
        }
        return true;
    }

    public static function authorize(array $allowedRoles): bool {
        self::check();

        $userRole = $_SESSION['user']['role_name'] ?? '';
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;

        if (!in_array($userRole, $allowedRoles) && !in_array($userRoleId, $allowedRoles)) {
            (new Response())->setStatusCode(403);
            require APP_PATH . '/Views/errors/403.php';
            exit;
        }
        return true;
    }

    public static function guest(): void {
        if (isset($_SESSION['user'])) {
            (new Response())->redirect('/dashboard');
            exit;
        }
    }
}
