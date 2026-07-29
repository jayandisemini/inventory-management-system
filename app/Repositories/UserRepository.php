<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.role_id 
            WHERE u.email = :email 
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.role_id 
            WHERE u.user_id = :id 
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT u.*, r.role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.role_id 
            ORDER BY u.user_id DESC
        ");
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new User($row), $rows);
    }

    public function getRoles(): array {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY role_id ASC");
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role_id, created_at)
            VALUES (:name, :email, :password, :role_id, NOW())
        ");
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = :name, email = :email, password = :password, role_id = :role_id 
                WHERE user_id = :id
            ");
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id' => $data['role_id'],
                'id' => $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = :name, email = :email, role_id = :role_id 
                WHERE user_id = :id
            ");
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'id' => $id
            ]);
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
