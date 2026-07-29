<?php

namespace App\Models;

class Role {
    public ?int $role_id = null;
    public string $role_name = '';

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->role_id = isset($data['role_id']) ? (int)$data['role_id'] : null;
            $this->role_name = $data['role_name'] ?? '';
        }
    }
}
