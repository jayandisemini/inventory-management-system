<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\UserRepository;

class ProfileController extends Controller {
    private UserRepository $userRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->userRepository = new UserRepository();
    }

    public function index(): void {
        $sessionUser = $this->session->get('user');
        $user = $this->userRepository->findById((int)$sessionUser['user_id']);

        $this->render('profile/index', [
            'pageTitle' => 'My Account & Security Profile',
            'activeNav' => 'dashboard',
            'user' => $user
        ]);
    }

    public function update(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();
        $sessionUser = $this->session->get('user');
        $userId = (int)$sessionUser['user_id'];

        $name = trim($body['name'] ?? '');
        $email = trim($body['email'] ?? '');

        if (empty($name) || empty($email)) {
            $this->session->setFlash('error', 'Name and Email are required.');
            $this->response->redirect('/profile');
        }

        $existing = $this->userRepository->findByEmail($email);
        if ($existing && $existing->user_id !== $userId) {
            $this->session->setFlash('error', 'This email address is already in use by another account.');
            $this->response->redirect('/profile');
        }

        $user = $this->userRepository->findById($userId);
        $this->userRepository->update($userId, [
            'name' => $name,
            'email' => $email,
            'role_id' => $user->role_id
        ]);

        $sessionUser['name'] = $name;
        $sessionUser['email'] = $email;
        $this->session->set('user', $sessionUser);

        $this->session->setFlash('success', 'Profile information updated successfully!');
        $this->response->redirect('/profile');
    }

    public function changePassword(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();
        $sessionUser = $this->session->get('user');
        $userId = (int)$sessionUser['user_id'];

        $currentPassword = $body['current_password'] ?? '';
        $newPassword = $body['new_password'] ?? '';
        $confirmPassword = $body['confirm_password'] ?? '';

        $user = $this->userRepository->findById($userId);

        if (!password_verify($currentPassword, $user->password)) {
            $this->session->setFlash('error', 'Current password is incorrect.');
            $this->response->redirect('/profile');
        }

        if (strlen($newPassword) < 6) {
            $this->session->setFlash('error', 'New password must be at least 6 characters long.');
            $this->response->redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            $this->session->setFlash('error', 'New password and confirmation do not match.');
            $this->response->redirect('/profile');
        }

        $this->userRepository->updatePassword($userId, password_hash($newPassword, PASSWORD_BCRYPT));
        $this->session->setFlash('success', 'Password updated successfully!');
        $this->response->redirect('/profile');
    }
}
