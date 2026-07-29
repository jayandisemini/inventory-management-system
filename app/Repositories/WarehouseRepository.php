<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Warehouse;
use PDO;

class WarehouseRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM warehouses ORDER BY warehouse_id ASC");
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Warehouse($row), $rows);
    }

    public function findById(int $id): ?Warehouse {
        $stmt = $this->db->prepare("SELECT * FROM warehouses WHERE warehouse_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Warehouse($row) : null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO warehouses (warehouse_name, code, location, manager_name, phone, created_at)
            VALUES (:warehouse_name, :code, :location, :manager_name, :phone, NOW())
        ");
        $stmt->execute([
            'warehouse_name' => $data['warehouse_name'],
            'code' => $data['code'],
            'location' => $data['location'] ?? null,
            'manager_name' => $data['manager_name'] ?? null,
            'phone' => $data['phone'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE warehouses 
            SET warehouse_name = :warehouse_name,
                code = :code,
                location = :location,
                manager_name = :manager_name,
                phone = :phone
            WHERE warehouse_id = :id
        ");
        return $stmt->execute([
            'warehouse_name' => $data['warehouse_name'],
            'code' => $data['code'],
            'location' => $data['location'] ?? null,
            'manager_name' => $data['manager_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM warehouses WHERE warehouse_id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
