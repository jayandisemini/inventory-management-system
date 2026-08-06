<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Setting;
use PDO;

class SettingRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getSettings(): Setting {
        $stmt = $this->db->query("SELECT * FROM settings WHERE id = 1 LIMIT 1");
        $row = $stmt->fetch();
        return $row ? new Setting($row) : new Setting();
    }

    public function update(array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE settings 
            SET company_name = :company_name,
                tax_id = :tax_id,
                currency_symbol = :currency_symbol,
                default_min_stock = :default_min_stock,
                company_address = :company_address
            WHERE id = 1
        ");
        return $stmt->execute([
            'company_name' => $data['company_name'],
            'tax_id' => $data['tax_id'] ?? null,
            'currency_symbol' => $data['currency_symbol'] ?? 'Rs.',
            'default_min_stock' => (int)($data['default_min_stock'] ?? 5),
            'company_address' => $data['company_address'] ?? null
        ]);
    }
}
