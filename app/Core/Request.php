<?php

namespace App\Core;

class Request {
    public function getMethod(): string {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return rtrim($path, '/') ?: '/';
    }

    public function getBody(): array {
        $body = [];
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    $body[$key] = array_map(fn($item) => is_string($item) ? htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8') : $item, $value);
                } else {
                    $body[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
                }
            }
        }
        return $body;
    }

    public function getRawPost(): array {
        return $_POST;
    }

    public function getFile(string $key): ?array {
        return $_FILES[$key] ?? null;
    }

    public function isAjax(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
               (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }
}
