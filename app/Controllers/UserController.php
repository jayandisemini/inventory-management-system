<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\UserRepository;

class UserController extends Controller {
    private UserRepository $userRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::authorize(['Admin']);
        $this->userRepository = new UserRepository();
    }

    public function index(): void {
        $users = $this->userRepository->getAll();
        $roles = $this->userRepository->getRoles();

        $this->render('users/index', [
            'pageTitle' => 'System User Management',
            'activeNav' => 'users',
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function store(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();

        if (empty($body['name']) || empty($body['email']) || empty($body['password'])) {
            $this->session->setFlash('error', 'Name, Email, and Password are required.');
            $this->response->redirect('/users');
        }

        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlash('error', 'Invalid email address provided.');
            $this->response->redirect('/users');
        }

        if ($this->userRepository->findByEmail($body['email'])) {
            $this->session->setFlash('error', 'User with this email already exists.');
            $this->response->redirect('/users');
        }

        $this->userRepository->create([
            'name' => $body['name'],
            'email' => $body['email'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'role_id' => (int)($body['role_id'] ?? 3)
        ]);

        $this->session->setFlash('success', "New user account '{$body['name']}' created successfully!");
        $this->response->redirect('/users');
    }

    public function update(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();
        $id = (int)($body['id'] ?? 0);

        if ($id <= 0 || empty($body['name']) || empty($body['email'])) {
            $this->session->setFlash('error', 'Valid User ID, Name, and Email are required.');
            $this->response->redirect('/users');
        }

        $data = [
            'name' => $body['name'],
            'email' => $body['email'],
            'role_id' => (int)($body['role_id'] ?? 3)
        ];

        if (!empty($body['password'])) {
            $data['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }

        $this->userRepository->update($id, $data);
        $this->session->setFlash('success', "User account updated successfully!");
        $this->response->redirect('/users');
    }

    public function delete(): void {
        $this->validateCSRF();
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $currentUser = $this->session->get('user');

        if ($id === (int)$currentUser['user_id']) {
            $this->session->setFlash('error', 'Security Policy: You cannot delete your own active administrator account.');
            $this->response->redirect('/users');
        }

        $this->userRepository->delete($id);
        $this->session->setFlash('success', 'User account permanently deleted.');
        $this->response->redirect('/users');
    }
}
