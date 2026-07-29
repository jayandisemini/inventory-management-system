<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, array|callable $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array|callable $handler): void {
        $path = rtrim($path, '/') ?: '/';
        $this->routes[$method][$path] = $handler;
    }

    public function resolve(Request $request, Response $response): mixed {
        $method = $request->getMethod();
        $path = $request->getPath();

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            $response->setStatusCode(404);
            require APP_PATH . '/Views/errors/404.php';
            return null;
        }

        if (is_array($handler)) {
            [$controllerClass, $action] = $handler;
            $controller = new $controllerClass();
            return call_user_func([$controller, $action]);
        }

        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        return null;
    }
}
