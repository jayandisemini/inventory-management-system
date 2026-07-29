<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Category;
use PDO;

class CategoryRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "
            SELECT c.*, COUNT(p.product_id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.category_id = p.category_id
            GROUP BY c.category_id
            ORDER BY c.category_name ASC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Category($row), $rows);
    }

    public function findById(int $id): ?Category {
        $stmt = $this->db->prepare("
            SELECT c.*, COUNT(p.product_id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.category_id = p.category_id
            WHERE c.category_id = :id
            GROUP BY c.category_id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Category($row) : null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO categories (category_name, description, created_at)
            VALUES (:category_name, :description, NOW())
        ");
        $stmt->execute([
            'category_name' => $data['category_name'],
            'description' => $data['description'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE categories 
            SET category_name = :category_name, description = :description 
            WHERE category_id = :id
        ");
        return $stmt->execute([
            'category_name' => $data['category_name'],
            'description' => $data['description'] ?? null,
            'id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
