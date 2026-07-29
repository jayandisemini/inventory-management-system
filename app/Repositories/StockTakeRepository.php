<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\StockTake;
use PDO;

class StockTakeRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `stock_takes` (
            `take_id` INT AUTO_INCREMENT PRIMARY KEY,
            `take_code` VARCHAR(50) NOT NULL UNIQUE,
            `warehouse_id` INT NOT NULL,
            `product_id` INT NOT NULL,
            `expected_qty` INT NOT NULL,
            `counted_qty` INT NOT NULL,
            `variance_qty` INT NOT NULL,
            `status` ENUM('Completed', 'Pending') NOT NULL DEFAULT 'Completed',
            `conducted_by` INT NOT NULL,
            `notes` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array {
        $sql = "SELECT st.*, 
                       w.warehouse_name, 
                       p.product_name, p.sku, 
                       u.name AS user_name
                FROM stock_takes st
                JOIN warehouses w ON st.warehouse_id = w.warehouse_id
                JOIN products p ON st.product_id = p.product_id
                JOIN users u ON st.conducted_by = u.user_id
                ORDER BY st.take_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, StockTake::class);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO stock_takes (take_code, warehouse_id, product_id, expected_qty, counted_qty, variance_qty, status, conducted_by, notes) 
                VALUES (:take_code, :warehouse_id, :product_id, :expected_qty, :counted_qty, :variance_qty, :status, :conducted_by, :notes)";
        $stmt = $this->db->prepare($sql);

        $variance = $data['counted_qty'] - $data['expected_qty'];

        return $stmt->execute([
            ':take_code' => $data['take_code'],
            ':warehouse_id' => $data['warehouse_id'],
            ':product_id' => $data['product_id'],
            ':expected_qty' => $data['expected_qty'],
            ':counted_qty' => $data['counted_qty'],
            ':variance_qty' => $variance,
            ':status' => 'Completed',
            ':conducted_by' => $data['conducted_by'],
            ':notes' => $data['notes'] ?? null
        ]);
    }
}
