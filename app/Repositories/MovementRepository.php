<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\StockMovement;
use PDO;

class MovementRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(array $filters = []): array {
        $sql = "
            SELECT m.*, p.product_name, p.sku, u.name as user_name
            FROM stock_movements m
            JOIN products p ON m.product_id = p.product_id
            JOIN users u ON m.user_id = u.user_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['product_id'])) {
            $sql .= " AND m.product_id = :product_id";
            $params['product_id'] = $filters['product_id'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND m.movement_type = :type";
            $params['type'] = $filters['type'];
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND DATE(m.created_at) >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND DATE(m.created_at) <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY m.movement_id DESC";
        if (isset($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new StockMovement($row), $rows);
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO stock_movements (product_id, movement_type, quantity, reference_note, user_id, created_at)
            VALUES (:product_id, :movement_type, :quantity, :reference_note, :user_id, NOW())
        ");
        $stmt->execute([
            'product_id' => $data['product_id'],
            'movement_type' => $data['movement_type'],
            'quantity' => $data['quantity'],
            'reference_note' => $data['reference_note'] ?? null,
            'user_id' => $data['user_id']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getMonthlyMovementStats(): array {
        $sql = "
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month_label,
                SUM(CASE WHEN movement_type = 'Stock In' THEN quantity ELSE 0 END) as stock_in,
                SUM(CASE WHEN movement_type = 'Stock Out' THEN quantity ELSE 0 END) as stock_out,
                SUM(CASE WHEN movement_type = 'Adjustment' THEN quantity ELSE 0 END) as adjustment
            FROM stock_movements
            GROUP BY month_label
            ORDER BY month_label ASC
            LIMIT 12
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getCategoryStockDistribution(): array {
        $sql = "
            SELECT c.category_name, SUM(p.quantity) as total_qty
            FROM products p
            JOIN categories c ON p.category_id = c.category_id
            GROUP BY c.category_id
            ORDER BY total_qty DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTopMovingProducts(int $limit = 5): array {
        $sql = "
            SELECT p.product_name, SUM(m.quantity) as total_moved
            FROM stock_movements m
            JOIN products p ON m.product_id = p.product_id
            GROUP BY p.product_id
            ORDER BY total_moved DESC
            LIMIT " . (int)$limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
