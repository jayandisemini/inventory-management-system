<?php

namespace App\Models;

class Assembly {
    public int $assembly_id;
    public string $assembly_code;
    public int $parent_product_id;
    public int $component_product_id;
    public int $required_qty;
    public int $assembled_units;
    public int $user_id;
    public string $created_at;

    // Joined fields
    public ?string $parent_product_name = null;
    public ?string $parent_sku = null;
    public ?string $component_product_name = null;
    public ?string $component_sku = null;
    public ?string $user_name = null;

    public function getStatusBadgeHtml(): string {
        return '<span class="badge bg-success"><i class="fas fa-cubes me-1"></i> Assembled</span>';
    }
}
