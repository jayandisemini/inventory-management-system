<?php

namespace App\Models;

class Customer {
    public int $customer_id;
    public string $customer_name;
    public ?string $email;
    public ?string $phone;
    public ?string $company_name;
    public ?string $address;
    public float $total_spend = 0.00;
    public string $created_at;

    public function getStatusBadgeHtml(): string {
        return '<span class="badge bg-cyan text-slate-950"><i class="fas fa-user-check me-1"></i> Active Client</span>';
    }
}
