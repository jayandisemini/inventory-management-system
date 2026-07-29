<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Customer;
use PDO;

class CustomerRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `customers` (
            `customer_id` INT AUTO_INCREMENT PRIMARY KEY,
            `customer_name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(100) NULL,
            `phone` VARCHAR(30) NULL,
            `company_name` VARCHAR(150) NULL,
            `address` TEXT NULL,
            `total_spend` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array {
        $sql = "SELECT * FROM customers ORDER BY customer_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Customer::class);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO customers (customer_name, email, phone, company_name, address) 
                VALUES (:customer_name, :email, :phone, :company_name, :address)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':customer_name' => $data['customer_name'],
            ':email' => !empty($data['email']) ? $data['email'] : null,
            ':phone' => !empty($data['phone']) ? $data['phone'] : null,
            ':company_name' => !empty($data['company_name']) ? $data['company_name'] : null,
            ':address' => !empty($data['address']) ? $data['address'] : null
        ]);
    }
}
