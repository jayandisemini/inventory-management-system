<?php

namespace App\Models;

class Category {
    public ?int $category_id = null;
    public string $category_name = '';
    public ?string $description = null;
    public ?string $created_at = null;
    public int $product_count = 0;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->category_id = isset($data['category_id']) ? (int)$data['category_id'] : null;
            $this->category_name = $data['category_name'] ?? '';
            $this->description = $data['description'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
            $this->product_count = isset($data['product_count']) ? (int)$data['product_count'] : 0;
        }
    }
}
