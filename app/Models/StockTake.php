<?php

namespace App\Models;

class StockTake {
    public int $take_id;
    public string $take_code;
    public int $warehouse_id;
    public int $product_id;
    public int $expected_qty;
    public int $counted_qty;
    public int $variance_qty;
    public string $status;
    public int $conducted_by;
    public ?string $notes;
    public string $created_at;

    // Joined fields
    public ?string $warehouse_name = null;
    public ?string $product_name = null;
    public ?string $sku = null;
    public ?string $user_name = null;

    public function getVarianceBadgeHtml(): string {
        if ($this->variance_qty === 0) {
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Match (0)</span>';
        } elseif ($this->variance_qty > 0) {
            return '<span class="badge bg-cyan text-slate-950"><i class="fas fa-arrow-up me-1"></i> Surplus (+' . $this->variance_qty . ')</span>';
        } else {
            return '<span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i> Shortage (' . $this->variance_qty . ')</span>';
        }
    }
}
