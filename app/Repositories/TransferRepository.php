<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Transfer;
use PDO;

class TransferRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `stock_transfers` (
            `transfer_id` INT AUTO_INCREMENT PRIMARY KEY,
            `transfer_code` VARCHAR(50) NOT NULL UNIQUE,
            `source_warehouse_id` INT NOT NULL,
            `dest_warehouse_id` INT NOT NULL,
            `product_id` INT NOT NULL,
            `quantity` INT NOT NULL,
            `status` ENUM('Pending', 'In Transit', 'Completed') NOT NULL DEFAULT 'Completed',
            `user_id` INT NOT NULL,
            `notes` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array {
        $sql = "SELECT t.*, 
                       w1.warehouse_name AS source_warehouse_name, 
                       w2.warehouse_name AS dest_warehouse_name, 
                       p.product_name, p.sku, 
                       u.name AS user_name
                FROM stock_transfers t
                JOIN warehouses w1 ON t.source_warehouse_id = w1.warehouse_id
                JOIN warehouses w2 ON t.dest_warehouse_id = w2.warehouse_id
                JOIN products p ON t.product_id = p.product_id
                JOIN users u ON t.user_id = u.user_id
                ORDER BY t.transfer_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Transfer::class);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO stock_transfers (transfer_code, source_warehouse_id, dest_warehouse_id, product_id, quantity, status, user_id, notes) 
                VALUES (:transfer_code, :source_warehouse_id, :dest_warehouse_id, :product_id, :quantity, :status, :user_id, :notes)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':transfer_code' => $data['transfer_code'],
            ':source_warehouse_id' => $data['source_warehouse_id'],
            ':dest_warehouse_id' => $data['dest_warehouse_id'],
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
            ':status' => $data['status'] ?? 'Completed',
            ':user_id' => $data['user_id'],
            ':notes' => $data['notes'] ?? null
        ]);
    }
}
