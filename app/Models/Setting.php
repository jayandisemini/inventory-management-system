<?php

namespace App\Models;

class Setting {
    public int $id = 1;
    public string $company_name = 'Smart Inventory Systems';
    public string $tax_id = 'TAX-889920';
    public string $currency_symbol = '$';
    public int $default_min_stock = 5;
    public ?string $company_address = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = (int)($data['id'] ?? 1);
            $this->company_name = $data['company_name'] ?? 'Smart Inventory Systems';
            $this->tax_id = $data['tax_id'] ?? 'TAX-889920';
            $this->currency_symbol = $data['currency_symbol'] ?? '$';
            $this->default_min_stock = (int)($data['default_min_stock'] ?? 5);
            $this->company_address = $data['company_address'] ?? null;
        }
    }
}
