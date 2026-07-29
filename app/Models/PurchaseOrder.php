<?php

namespace App\Models;

class PurchaseOrder {
    public ?int $po_id = null;
    public string $po_number = '';
    public int $supplier_id = 0;
    public int $user_id = 0;
    public float $total_amount = 0.00;
    public string $status = 'Sent'; // Draft, Sent, Received, Cancelled
    public ?string $notes = null;
    public ?string $supplier_name = null;
    public ?string $user_name = null;
    public ?string $created_at = null;
    public array $items = [];

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->po_id = isset($data['po_id']) ? (int)$data['po_id'] : null;
            $this->po_number = $data['po_number'] ?? '';
            $this->supplier_id = isset($data['supplier_id']) ? (int)$data['supplier_id'] : 0;
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
            $this->total_amount = isset($data['total_amount']) ? (float)$data['total_amount'] : 0.00;
            $this->status = $data['status'] ?? 'Sent';
            $this->notes = $data['notes'] ?? null;
            $this->supplier_name = $data['supplier_name'] ?? null;
            $this->user_name = $data['user_name'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
            $this->items = $data['items'] ?? [];
        }
    }

    public function getStatusBadgeHtml(): string {
        switch ($this->status) {
            case 'Received':
                return '<span class="badge bg-emerald-subtle text-emerald"><i class="fas fa-check-circle me-1"></i> Received & Stocked</span>';
            case 'Sent':
                return '<span class="badge bg-cyan-subtle text-cyan"><i class="fas fa-paper-plane me-1"></i> Sent to Supplier</span>';
            case 'Draft':
                return '<span class="badge bg-warning-subtle text-amber"><i class="fas fa-file-lines me-1"></i> Draft</span>';
            case 'Cancelled':
                return '<span class="badge bg-rose-subtle text-rose"><i class="fas fa-ban me-1"></i> Cancelled</span>';
            default:
                return '<span class="badge bg-slate-800">' . htmlspecialchars($this->status) . '</span>';
        }
    }
}
