<?php

namespace App\Models;

class Warehouse {
    public ?int $warehouse_id = null;
    public string $warehouse_name = '';
    public string $code = '';
    public ?string $location = null;
    public ?string $manager_name = null;
    public ?string $phone = null;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->warehouse_id = isset($data['warehouse_id']) ? (int)$data['warehouse_id'] : null;
            $this->warehouse_name = $data['warehouse_name'] ?? '';
            $this->code = $data['code'] ?? '';
            $this->location = $data['location'] ?? null;
            $this->manager_name = $data['manager_name'] ?? null;
            $this->phone = $data['phone'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
