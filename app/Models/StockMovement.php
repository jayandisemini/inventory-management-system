<?php

namespace App\Models;

class StockMovement {
    public ?int $movement_id = null;
    public int $product_id = 0;
    public string $movement_type = 'Stock In'; // Stock In, Stock Out, Adjustment
    public int $quantity = 0;
    public ?string $reference_note = null;
    public int $user_id = 0;
    public ?string $product_name = null;
    public ?string $sku = null;
    public ?string $user_name = null;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->movement_id = isset($data['movement_id']) ? (int)$data['movement_id'] : null;
            $this->product_id = isset($data['product_id']) ? (int)$data['product_id'] : 0;
            $this->movement_type = $data['movement_type'] ?? 'Stock In';
            $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
            $this->reference_note = $data['reference_note'] ?? null;
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
            $this->product_name = $data['product_name'] ?? null;
            $this->sku = $data['sku'] ?? null;
            $this->user_name = $data['user_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }

    public function getTypeBadgeHtml(): string {
        switch ($this->movement_type) {
            case 'Stock In':
                return '<span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i> Stock In</span>';
            case 'Stock Out':
                return '<span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i> Stock Out</span>';
            case 'Adjustment':
                return '<span class="badge bg-info text-dark"><i class="fas fa-sliders-h me-1"></i> Adjustment</span>';
            default:
                return '<span class="badge bg-secondary">' . htmlspecialchars($this->movement_type) . '</span>';
        }
    }
}
