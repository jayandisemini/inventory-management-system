<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Notification;
use PDO;

class NotificationRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getUnread(?int $userId = null): array {
        $sql = "SELECT * FROM notifications WHERE is_read = 0";
        $params = [];
        if ($userId) {
            $sql .= " AND (user_id IS NULL OR user_id = :user_id)";
            $params['user_id'] = $userId;
        }
        $sql .= " ORDER BY notification_id DESC LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new Notification($row), $rows);
    }

    public function create(?int $userId, string $type, string $message): int {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, message, is_read, created_at)
            VALUES (:user_id, :type, :message, 0, NOW())
        ");
        $stmt->execute([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function markAllAsRead(?int $userId = null): bool {
        $sql = "UPDATE notifications SET is_read = 1 WHERE is_read = 0";
        $params = [];
        if ($userId) {
            $sql .= " AND (user_id IS NULL OR user_id = :user_id)";
            $params['user_id'] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
