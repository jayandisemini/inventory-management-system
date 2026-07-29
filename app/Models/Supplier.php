<?php

namespace App\Models;

class Supplier {
    public ?int $supplier_id = null;
    public string $supplier_name = '';
    public ?string $contact_person = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $address = null;
    public ?string $created_at = null;
    public int $product_count = 0;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->supplier_id = isset($data['supplier_id']) ? (int)$data['supplier_id'] : null;
            $this->supplier_name = $data['supplier_name'] ?? '';
            $this->contact_person = $data['contact_person'] ?? null;
            $this->phone = $data['phone'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->address = $data['address'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
            $this->product_count = isset($data['product_count']) ? (int)$data['product_count'] : 0;
        }
    }
}
