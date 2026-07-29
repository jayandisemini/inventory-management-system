<?php

namespace App\Models;

class Transfer {
    public int $transfer_id;
    public string $transfer_code;
    public int $source_warehouse_id;
    public int $dest_warehouse_id;
    public int $product_id;
    public int $quantity;
    public string $status;
    public int $user_id;
    public ?string $notes;
    public string $created_at;

    // Joined fields
    public ?string $source_warehouse_name = null;
    public ?string $dest_warehouse_name = null;
    public ?string $product_name = null;
    public ?string $sku = null;
    public ?string $user_name = null;

    public function getStatusBadgeHtml(): string {
        switch ($this->status) {
            case 'Completed':
                return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Completed</span>';
            case 'In Transit':
                return '<span class="badge bg-warning text-dark"><i class="fas fa-truck-fast me-1"></i> In Transit</span>';
            case 'Pending':
            default:
                return '<span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Pending</span>';
        }
    }
}
