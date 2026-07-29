<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Assembly;
use PDO;

class AssemblyRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS `product_assemblies` (
            `assembly_id` INT AUTO_INCREMENT PRIMARY KEY,
            `assembly_code` VARCHAR(50) NOT NULL UNIQUE,
            `parent_product_id` INT NOT NULL,
            `component_product_id` INT NOT NULL,
            `required_qty` INT NOT NULL,
            `assembled_units` INT NOT NULL,
            `user_id` INT NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db->exec($sql);
    }

    public function getAll(): array {
        $sql = "SELECT a.*, 
                       p1.product_name AS parent_product_name, p1.sku AS parent_sku,
                       p2.product_name AS component_product_name, p2.sku AS component_sku,
                       u.name AS user_name
                FROM product_assemblies a
                JOIN products p1 ON a.parent_product_id = p1.product_id
                JOIN products p2 ON a.component_product_id = p2.product_id
                JOIN users u ON a.user_id = u.user_id
                ORDER BY a.assembly_id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Assembly::class);
    }

    public function create(array $data): bool {
        $this->db->beginTransaction();

        try {
            $sql = "INSERT INTO product_assemblies (assembly_code, parent_product_id, component_product_id, required_qty, assembled_units, user_id) 
                    VALUES (:assembly_code, :parent_product_id, :component_product_id, :required_qty, :assembled_units, :user_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':assembly_code' => $data['assembly_code'],
                ':parent_product_id' => $data['parent_product_id'],
                ':component_product_id' => $data['component_product_id'],
                ':required_qty' => $data['required_qty'],
                ':assembled_units' => $data['assembled_units'],
                ':user_id' => $data['user_id']
            ]);

            // Deduct raw component stock
            $totalComponentDeduction = $data['required_qty'] * $data['assembled_units'];
            $deductSql = "UPDATE products SET quantity = GREATEST(0, quantity - :qty) WHERE product_id = :id";
            $deductStmt = $this->db->prepare($deductSql);
            $deductStmt->execute([':qty' => $totalComponentDeduction, ':id' => $data['component_product_id']]);

            // Increase parent kit product stock
            $addSql = "UPDATE products SET quantity = quantity + :qty WHERE product_id = :id";
            $addStmt = $this->db->prepare($addSql);
            $addStmt->execute([':qty' => $data['assembled_units'], ':id' => $data['parent_product_id']]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
