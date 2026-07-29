<?php

namespace App\Models;

class User {
    public ?int $user_id = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public int $role_id = 3;
    public ?string $role_name = null;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;
            $this->name = $data['name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
            $this->role_id = isset($data['role_id']) ? (int)$data['role_id'] : 3;
            $this->role_name = $data['role_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
