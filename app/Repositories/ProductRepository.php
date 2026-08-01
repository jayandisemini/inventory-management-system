<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Product;
use PDO;

class ProductRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $sql = "
            SELECT p.*, c.category_name, s.supplier_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
            ORDER BY p.product_id DESC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Product($row), $rows);
    }

    public function findById(int $id): ?Product {
        $stmt = $this->db->prepare("
            SELECT p.*, c.category_name, s.supplier_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
            WHERE p.product_id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new Product($row) : null;
    }

    public function findBySku(string $sku, ?int $excludeId = null): ?Product {
        $sql = "SELECT * FROM products WHERE sku = :sku";
        $params = ['sku' => $sku];
        if ($excludeId) {
            $sql .= " AND product_id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ? new Product($row) : null;
    }

    public function findBySKU(string $sku, ?int $excludeId = null): ?Product {
        return $this->findBySku($sku, $excludeId);
    }

    public function findByBarcode(string $barcode, ?int $excludeId = null): ?Product {
        if (empty($barcode)) return null;
        $sql = "SELECT * FROM products WHERE barcode = :barcode";
        $params = ['barcode' => $barcode];
        if ($excludeId) {
            $sql .= " AND product_id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ? new Product($row) : null;
    }

    public function search(array $filters = []): array {
        $sql = "
            SELECT p.*, c.category_name, s.supplier_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.product_name LIKE :kw OR p.sku LIKE :kw OR p.barcode LIKE :kw)";
            $params['kw'] = "%" . $filters['keyword'] . "%";
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :cat_id";
            $params['cat_id'] = $filters['category_id'];
        }
        if (!empty($filters['supplier_id'])) {
            $sql .= " AND p.supplier_id = :sup_id";
            $params['sup_id'] = $filters['supplier_id'];
        }
        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'low') {
                $sql .= " AND p.quantity > 0 AND p.quantity <= p.min_stock_level";
            } elseif ($filters['stock_status'] === 'out') {
                $sql .= " AND p.quantity <= 0";
            } elseif ($filters['stock_status'] === 'in') {
                $sql .= " AND p.quantity > p.min_stock_level";
            }
        }

        $sql .= " ORDER BY p.product_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Product($row), $rows);
    }

    public function getLowStockProducts(): array {
        $sql = "
            SELECT p.*, c.category_name, s.supplier_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
            WHERE p.quantity <= p.min_stock_level
            ORDER BY p.quantity ASC
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Product($row), $rows);
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO products (
                product_name, sku, barcode, description, category_id, supplier_id, 
                unit_price, selling_price, quantity, min_stock_level, image, created_at
            ) VALUES (
                :product_name, :sku, :barcode, :description, :category_id, :supplier_id, 
                :unit_price, :selling_price, :quantity, :min_stock_level, :image, NOW()
            )
        ");
        $stmt->execute([
            'product_name' => $data['product_name'],
            'sku' => $data['sku'],
            'barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'],
            'unit_price' => $data['unit_price'],
            'selling_price' => $data['selling_price'],
            'quantity' => $data['quantity'],
            'min_stock_level' => $data['min_stock_level'],
            'image' => $data['image'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE products SET 
                product_name = :product_name,
                sku = :sku,
                barcode = :barcode,
                description = :description,
                category_id = :category_id,
                supplier_id = :supplier_id,
                unit_price = :unit_price,
                selling_price = :selling_price,
                quantity = :quantity,
                min_stock_level = :min_stock_level,
                image = :image
            WHERE product_id = :id
        ");
        return $stmt->execute([
            'product_name' => $data['product_name'],
            'sku' => $data['sku'],
            'barcode' => !empty($data['barcode']) ? $data['barcode'] : null,
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'],
            'supplier_id' => $data['supplier_id'],
            'unit_price' => $data['unit_price'],
            'selling_price' => $data['selling_price'],
            'quantity' => $data['quantity'],
            'min_stock_level' => $data['min_stock_level'],
            'image' => $data['image'] ?? null,
            'id' => $id
        ]);
    }

    public function updateQuantity(int $id, int $newQuantity): bool {
        $stmt = $this->db->prepare("UPDATE products SET quantity = :quantity WHERE product_id = :id");
        return $stmt->execute(['quantity' => $newQuantity, 'id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getSummaryMetrics(): array {
        $totalProducts = (int)$this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $totalValuation = (float)$this->db->query("SELECT SUM(quantity * selling_price) FROM products")->fetchColumn();
        $lowStockCount = (int)$this->db->query("SELECT COUNT(*) FROM products WHERE quantity > 0 AND quantity <= min_stock_level")->fetchColumn();
        $outOfStockCount = (int)$this->db->query("SELECT COUNT(*) FROM products WHERE quantity <= 0")->fetchColumn();

        return [
            'total_products' => $totalProducts,
            'total_valuation' => $totalValuation,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount
        ];
    }
}
