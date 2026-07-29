<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class AuthService {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function authenticate(string $email, string $password): ?User {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function registerUser(array $data): array {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Full name is required.';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        } elseif ($this->userRepository->findByEmail($data['email'])) {
            $errors[] = 'An account with this email address already exists.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $roleId = isset($data['role_id']) ? (int)$data['role_id'] : 3; // Default Staff

        $userId = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role_id' => $roleId
        ]);

        return ['success' => true, 'user_id' => $userId];
    }
}
