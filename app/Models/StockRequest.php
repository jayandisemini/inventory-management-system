<?php

namespace App\Models;

class StockRequest {
    public ?int $request_id = null;
    public int $product_id = 0;
    public int $user_id = 0;
    public int $quantity = 0;
    public ?string $reason = null;
    public string $status = 'Pending'; // Pending, Approved, Rejected
    public ?int $action_by = null;
    public ?string $product_name = null;
    public ?string $sku = null;
    public ?string $user_name = null;
    public ?string $action_by_name = null;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->request_id = isset($data['request_id']) ? (int)$data['request_id'] : null;
            $this->product_id = isset($data['product_id']) ? (int)$data['product_id'] : 0;
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
            $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
            $this->reason = $data['reason'] ?? null;
            $this->status = $data['status'] ?? 'Pending';
            $this->action_by = isset($data['action_by']) ? (int)$data['action_by'] : null;
            $this->product_name = $data['product_name'] ?? null;
            $this->sku = $data['sku'] ?? null;
            $this->user_name = $data['user_name'] ?? null;
            $this->action_by_name = $data['action_by_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }

    public function getStatusBadgeHtml(): string {
        switch ($this->status) {
            case 'Approved':
                return '<span class="badge bg-emerald-subtle text-emerald"><i class="fas fa-check me-1"></i> Approved & Dispatched</span>';
            case 'Pending':
                return '<span class="badge bg-warning-subtle text-amber"><i class="fas fa-clock me-1"></i> Pending Approval</span>';
            case 'Rejected':
                return '<span class="badge bg-rose-subtle text-rose"><i class="fas fa-times me-1"></i> Rejected</span>';
            default:
                return '<span class="badge bg-slate-800">' . htmlspecialchars($this->status) . '</span>';
        }
    }
}
