<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\StockRequest;
use PDO;

class RequestRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(?int $userId = null): array {
        $sql = "
            SELECT sr.*, p.product_name, p.sku, u.name as user_name, ab.name as action_by_name
            FROM stock_requests sr
            JOIN products p ON sr.product_id = p.product_id
            JOIN users u ON sr.user_id = u.user_id
            LEFT JOIN users ab ON sr.action_by = ab.user_id
            WHERE 1=1
        ";
        $params = [];

        if ($userId) {
            $sql .= " AND sr.user_id = :user_id";
            $params['user_id'] = $userId;
        }

        $sql .= " ORDER BY sr.request_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new StockRequest($row), $rows);
    }

    public function findById(int $id): ?StockRequest {
        $stmt = $this->db->prepare("
            SELECT sr.*, p.product_name, p.sku, u.name as user_name, ab.name as action_by_name
            FROM stock_requests sr
            JOIN products p ON sr.product_id = p.product_id
            JOIN users u ON sr.user_id = u.user_id
            LEFT JOIN users ab ON sr.action_by = ab.user_id
            WHERE sr.request_id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new StockRequest($row) : null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO stock_requests (product_id, user_id, quantity, reason, status, created_at)
            VALUES (:product_id, :user_id, :quantity, :reason, 'Pending', NOW())
        ");
        $stmt->execute([
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status, int $actionBy): bool {
        $stmt = $this->db->prepare("
            UPDATE stock_requests 
            SET status = :status, action_by = :action_by 
            WHERE request_id = :id
        ");
        return $stmt->execute([
            'status' => $status,
            'action_by' => $actionBy,
            'id' => $id
        ]);
    }
}
