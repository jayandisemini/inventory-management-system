<?php

namespace App\Models;

class Batch {
    public int $batch_id;
    public int $product_id;
    public string $batch_number;
    public int $quantity;
    public ?string $mfd_date;
    public string $expiry_date;
    public string $status;
    public string $created_at;

    // Joined fields
    public ?string $product_name = null;
    public ?string $sku = null;

    public function getStatusBadgeHtml(): string {
        $today = date('Y-m-d');
        $expDate = date('Y-m-d', strtotime($this->expiry_date));
        $thirtyDays = date('Y-m-d', strtotime('+30 days'));

        if ($expDate < $today) {
            return '<span class="badge bg-danger"><i class="fas fa-triangle-exclamation me-1"></i> Expired</span>';
        } elseif ($expDate <= $thirtyDays) {
            return '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Expiring Soon</span>';
        } else {
            return '<span class="badge bg-success"><i class="fas fa-circle-check me-1"></i> Active Fresh</span>';
        }
    }
}
