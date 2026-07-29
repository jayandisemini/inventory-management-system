<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Batch;
use PDO;

class BatchRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `product_batches` (
            `batch_id` INT AUTO_INCREMENT PRIMARY KEY,
            `product_id` INT NOT NULL,
            `batch_number` VARCHAR(100) NOT NULL,
            `quantity` INT NOT NULL DEFAULT 0,
            `mfd_date` DATE NULL,
            `expiry_date` DATE NOT NULL,
            `status` ENUM('Active', 'Expiring Soon', 'Expired') NOT NULL DEFAULT 'Active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array {
        $sql = "SELECT b.*, p.product_name, p.sku 
                FROM product_batches b 
                JOIN products p ON b.product_id = p.product_id 
                ORDER BY b.expiry_date ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Batch::class);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO product_batches (product_id, batch_number, quantity, mfd_date, expiry_date, status) 
                VALUES (:product_id, :batch_number, :quantity, :mfd_date, :expiry_date, :status)";
        $stmt = $this->db->prepare($sql);

        $today = date('Y-m-d');
        $expDate = $data['expiry_date'];
        $thirtyDays = date('Y-m-d', strtotime('+30 days'));

        $status = 'Active';
        if ($expDate < $today) {
            $status = 'Expired';
        } elseif ($expDate <= $thirtyDays) {
            $status = 'Expiring Soon';
        }

        return $stmt->execute([
            ':product_id' => $data['product_id'],
            ':batch_number' => $data['batch_number'],
            ':quantity' => $data['quantity'],
            ':mfd_date' => !empty($data['mfd_date']) ? $data['mfd_date'] : null,
            ':expiry_date' => $data['expiry_date'],
            ':status' => $status
        ]);
    }
}
