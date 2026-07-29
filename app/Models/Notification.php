<?php

namespace App\Models;

class Notification {
    public ?int $notification_id = null;
    public ?int $user_id = null;
    public string $type = 'info';
    public string $message = '';
    public int $is_read = 0;
    public ?string $created_at = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->notification_id = isset($data['notification_id']) ? (int)$data['notification_id'] : null;
            $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;
            $this->type = $data['type'] ?? 'info';
            $this->message = $data['message'] ?? '';
            $this->is_read = isset($data['is_read']) ? (int)$data['is_read'] : 0;
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
