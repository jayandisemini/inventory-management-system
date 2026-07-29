<?php

namespace App\Models;

class Product {
    public ?int $product_id = null;
    public string $product_name = '';
    public string $sku = '';
    public ?string $barcode = null;
    public ?string $description = null;
    public int $category_id = 0;
    public int $supplier_id = 0;
    public float $unit_price = 0.00;
    public float $selling_price = 0.00;
    public int $quantity = 0;
    public int $min_stock_level = 5;
    public ?string $image = null;
    public ?string $category_name = null;
    public ?string $supplier_name = null;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->product_id = isset($data['product_id']) ? (int)$data['product_id'] : null;
            $this->product_name = $data['product_name'] ?? '';
            $this->sku = $data['sku'] ?? '';
            $this->barcode = $data['barcode'] ?? null;
            $this->description = $data['description'] ?? null;
            $this->category_id = isset($data['category_id']) ? (int)$data['category_id'] : 0;
            $this->supplier_id = isset($data['supplier_id']) ? (int)$data['supplier_id'] : 0;
            $this->unit_price = isset($data['unit_price']) ? (float)$data['unit_price'] : 0.00;
            $this->selling_price = isset($data['selling_price']) ? (float)$data['selling_price'] : 0.00;
            $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
            $this->min_stock_level = isset($data['min_stock_level']) ? (int)$data['min_stock_level'] : 5;
            $this->image = $data['image'] ?? null;
            $this->category_name = $data['category_name'] ?? null;
            $this->supplier_name = $data['supplier_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }

    public function isLowStock(): bool {
        return $this->quantity > 0 && $this->quantity <= $this->min_stock_level;
    }

    public function isOutOfStock(): bool {
        return $this->quantity <= 0;
    }

    public function getStockStatusHtml(): string {
        if ($this->isOutOfStock()) {
            return '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Out of Stock</span>';
        }
        if ($this->isLowStock()) {
            return '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Low Stock</span>';
        }
        return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> In Stock</span>';
    }
}
