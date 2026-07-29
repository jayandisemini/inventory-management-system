<?php

namespace App\Models;

class SalesOrder {
    public ?int $so_id = null;
    public string $order_number = '';
    public string $customer_name = '';
    public ?string $customer_email = null;
    public float $total_amount = 0.00;
    public string $payment_status = 'Paid'; // Paid, Pending, Cancelled
    public ?string $notes = null;
    public int $user_id = 0;
    public ?string $user_name = null;
    public ?string $created_at = null;
    public array $items = [];

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->so_id = isset($data['so_id']) ? (int)$data['so_id'] : null;
            $this->order_number = $data['order_number'] ?? '';
            $this->customer_name = $data['customer_name'] ?? '';
            $this->customer_email = $data['customer_email'] ?? null;
            $this->total_amount = isset($data['total_amount']) ? (float)$data['total_amount'] : 0.00;
            $this->payment_status = $data['payment_status'] ?? 'Paid';
            $this->notes = $data['notes'] ?? null;
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
            $this->user_name = $data['user_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
            $this->items = $data['items'] ?? [];
        }
    }

    public function getStatusBadgeHtml(): string {
        switch ($this->payment_status) {
            case 'Paid':
                return '<span class="badge bg-emerald-subtle text-emerald"><i class="fas fa-circle-check me-1"></i> Paid & Completed</span>';
            case 'Pending':
                return '<span class="badge bg-warning-subtle text-amber"><i class="fas fa-clock me-1"></i> Payment Pending</span>';
            case 'Cancelled':
                return '<span class="badge bg-rose-subtle text-rose"><i class="fas fa-ban me-1"></i> Cancelled</span>';
            default:
                return '<span class="badge bg-slate-800">' . htmlspecialchars($this->payment_status) . '</span>';
        }
    }
}
