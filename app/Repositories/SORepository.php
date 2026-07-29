<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\SalesOrder;
use Exception;
use PDO;

class SORepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "
            SELECT so.*, u.name as user_name
            FROM sales_orders so
            JOIN users u ON so.user_id = u.user_id
            ORDER BY so.so_id DESC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new SalesOrder($row), $rows);
    }

    public function findById(int $id): ?SalesOrder {
        $stmt = $this->db->prepare("
            SELECT so.*, u.name as user_name
            FROM sales_orders so
            JOIN users u ON so.user_id = u.user_id
            WHERE so.so_id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        $so = new SalesOrder($row);
        $so->items = $this->getSOItems($id);
        return $so;
    }

    public function getSOItems(int $soId): array {
        $stmt = $this->db->prepare("
            SELECT soi.*, p.product_name, p.sku
            FROM so_items soi
            JOIN products p ON soi.product_id = p.product_id
            WHERE soi.so_id = :so_id
        ");
        $stmt->execute(['so_id' => $soId]);
        return $stmt->fetchAll();
    }

    public function create(array $data, array $items): int {
        $this->db->beginTransaction();
        try {
            $orderNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $stmt = $this->db->prepare("
                INSERT INTO sales_orders (order_number, customer_name, customer_email, total_amount, payment_status, notes, user_id, created_at)
                VALUES (:order_number, :customer_name, :customer_email, :total_amount, :payment_status, :notes, :user_id, NOW())
            ");
            $stmt->execute([
                'order_number' => $orderNumber,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'] ?? null,
                'total_amount' => $data['total_amount'],
                'payment_status' => $data['payment_status'] ?? 'Paid',
                'notes' => $data['notes'] ?? null,
                'user_id' => $data['user_id']
            ]);
            $soId = (int)$this->db->lastInsertId();

            $itemStmt = $this->db->prepare("
                INSERT INTO so_items (so_id, product_id, quantity, unit_price, total_price)
                VALUES (:so_id, :product_id, :quantity, :unit_price, :total_price)
            ");

            foreach ($items as $item) {
                $itemStmt->execute([
                    'so_id' => $soId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price']
                ]);
            }

            $this->db->commit();
            return $soId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
