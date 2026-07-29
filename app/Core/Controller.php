<?php

namespace App\Core;

abstract class Controller {
    protected Request $request;
    protected Response $response;
    protected Session $session;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
    }

    protected function render(string $view, array $data = [], string $layout = 'header'): void {
        extract($data);
        
        $viewFile = APP_PATH . "/Views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("View [{$view}] not found at {$viewFile}");
        }

        require APP_PATH . "/Views/layouts/header.php";
        require APP_PATH . "/Views/layouts/sidebar.php";
        require APP_PATH . "/Views/layouts/navbar.php";
        
        require $viewFile;
        
        require APP_PATH . "/Views/layouts/footer.php";
    }

    protected function renderAuthView(string $view, array $data = []): void {
        extract($data);
        $viewFile = APP_PATH . "/Views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("Auth View [{$view}] not found.");
        }
        require $viewFile;
    }

    protected function validateCSRF(): void {
        $body = $this->request->getBody();
        $token = $body['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!CSRF::verifyToken($token)) {
            $this->session->setFlash('error', 'Security token expired or invalid CSRF request.');
            $this->response->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
            exit;
        }
    }
}
