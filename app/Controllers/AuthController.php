<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Services\AuthService;

class AuthController extends Controller {
    private AuthService $authService;

    public function __construct() {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showLogin(): void {
        AuthMiddleware::guest();
        $this->renderAuthView('auth/login', [
            'pageTitle' => 'Sign In - Smart Inventory System'
        ]);
    }

    public function login(): void {
        AuthMiddleware::guest();
        $this->validateCSRF();

        $body = $this->request->getBody();
        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->session->setFlash('error', 'Please fill in both email and password fields.');
            $this->response->redirect('/login');
        }

        $user = $this->authService->authenticate($email, $password);
        if (!$user) {
            $this->session->setFlash('error', 'Invalid email address or password credentials.');
            $this->response->redirect('/login');
        }

        $this->session->set('user', [
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_name' => $user->role_name
        ]);

        $this->session->setFlash('success', "Welcome back, {$user->name}!");
        $this->response->redirect('/dashboard');
    }

    public function showRegister(): void {
        AuthMiddleware::guest();
        $this->renderAuthView('auth/register', [
            'pageTitle' => 'Create Account - Smart Inventory System'
        ]);
    }

    public function register(): void {
        AuthMiddleware::guest();
        $this->validateCSRF();

        $body = $this->request->getBody();
        $result = $this->authService->registerUser($body);

        if (!$result['success']) {
            $this->session->setFlash('error', implode('<br>', $result['errors']));
            $this->response->redirect('/register');
        }

        $this->session->setFlash('success', 'Registration successful! You may now sign in to your account.');
        $this->response->redirect('/login');
    }

    public function showResetPassword(): void {
        AuthMiddleware::guest();
        $this->renderAuthView('auth/reset-password', [
            'pageTitle' => 'Reset Password - Smart Inventory System'
        ]);
    }

    public function resetPassword(): void {
        AuthMiddleware::guest();
        $this->validateCSRF();

        $body = $this->request->getBody();
        $email = $body['email'] ?? '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlash('error', 'Please enter a valid email address.');
            $this->response->redirect('/reset-password');
        }

        $this->session->setFlash('success', 'If an account exists with that email, a password reset link has been dispatched.');
        $this->response->redirect('/login');
    }

    public function logout(): void {
        $this->session->destroy();
        header("Location: /login");
        exit;
    }
}
