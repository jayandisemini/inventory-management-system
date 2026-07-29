<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\PurchaseOrder;
use Exception;
use PDO;

class PORepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "
            SELECT po.*, s.supplier_name, u.name as user_name
            FROM purchase_orders po
            JOIN suppliers s ON po.supplier_id = s.supplier_id
            JOIN users u ON po.user_id = u.user_id
            ORDER BY po.po_id DESC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new PurchaseOrder($row), $rows);
    }

    public function findById(int $id): ?PurchaseOrder {
        $stmt = $this->db->prepare("
            SELECT po.*, s.supplier_name, u.name as user_name
            FROM purchase_orders po
            JOIN suppliers s ON po.supplier_id = s.supplier_id
            JOIN users u ON po.user_id = u.user_id
            WHERE po.po_id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        $po = new PurchaseOrder($row);
        $po->items = $this->getPOItems($id);
        return $po;
    }

    public function getPOItems(int $poId): array {
        $stmt = $this->db->prepare("
            SELECT poi.*, p.product_name, p.sku
            FROM po_items poi
            JOIN products p ON poi.product_id = p.product_id
            WHERE poi.po_id = :po_id
        ");
        $stmt->execute(['po_id' => $poId]);
        return $stmt->fetchAll();
    }

    public function create(array $data, array $items): int {
        $this->db->beginTransaction();
        try {
            $poNumber = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $stmt = $this->db->prepare("
                INSERT INTO purchase_orders (po_number, supplier_id, user_id, total_amount, status, notes, created_at)
                VALUES (:po_number, :supplier_id, :user_id, :total_amount, 'Sent', :notes, NOW())
            ");
            $stmt->execute([
                'po_number' => $poNumber,
                'supplier_id' => $data['supplier_id'],
                'user_id' => $data['user_id'],
                'total_amount' => $data['total_amount'],
                'notes' => $data['notes'] ?? null
            ]);
            $poId = (int)$this->db->lastInsertId();

            $itemStmt = $this->db->prepare("
                INSERT INTO po_items (po_id, product_id, quantity, unit_cost, total_cost)
                VALUES (:po_id, :product_id, :quantity, :unit_cost, :total_cost)
            ");

            foreach ($items as $item) {
                $itemStmt->execute([
                    'po_id' => $poId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['quantity'] * $item['unit_cost']
                ]);
            }

            $this->db->commit();
            return $poId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatus(int $poId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE purchase_orders SET status = :status WHERE po_id = :id");
        return $stmt->execute(['status' => $status, 'id' => $poId]);
    }
}
