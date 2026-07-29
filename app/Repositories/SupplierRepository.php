<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Supplier;
use PDO;

class SupplierRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "
            SELECT s.*, COUNT(p.product_id) as product_count
            FROM suppliers s
            LEFT JOIN products p ON s.supplier_id = p.supplier_id
            GROUP BY s.supplier_id
            ORDER BY s.supplier_name ASC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Supplier($row), $rows);
    }

    public function findById(int $id): ?Supplier {
        $stmt = $this->db->prepare("
            SELECT s.*, COUNT(p.product_id) as product_count
            FROM suppliers s
            LEFT JOIN products p ON s.supplier_id = p.supplier_id
            WHERE s.supplier_id = :id
            GROUP BY s.supplier_id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Supplier($row) : null;
    }

    public function getProductsBySupplierId(int $supplierId): array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.supplier_id = :supplier_id
            ORDER BY p.product_name ASC
        ");
        $stmt->execute(['supplier_id' => $supplierId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO suppliers (supplier_name, contact_person, phone, email, address, created_at)
            VALUES (:supplier_name, :contact_person, :phone, :email, :address, NOW())
        ");
        $stmt->execute([
            'supplier_name' => $data['supplier_name'],
            'contact_person' => $data['contact_person'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE suppliers 
            SET supplier_name = :supplier_name, 
                contact_person = :contact_person, 
                phone = :phone, 
                email = :email, 
                address = :address 
            WHERE supplier_id = :id
        ");
        return $stmt->execute([
            'supplier_name' => $data['supplier_name'],
            'contact_person' => $data['contact_person'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM suppliers WHERE supplier_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
