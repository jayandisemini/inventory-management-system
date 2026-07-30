<?php

namespace App\Services;

use App\Repositories\NotificationRepository;

class NotificationService {
    private NotificationRepository $notificationRepository;
    private EmailService $emailService;

    public function __construct() {
        $this->notificationRepository = new NotificationRepository();
        $this->emailService = new EmailService();
    }

    public function notifyLowStock(string $productName, int $currentQty, int $minLevel): void {
        $msg = "Low Stock Alert: [{$productName}] has reached {$currentQty} units (Minimum required: {$minLevel}).";
        $this->notificationRepository->create(null, 'warning', $msg);
        $this->emailService->sendLowStockAlert($productName, $currentQty, $minLevel);
    }

    public function notifyOutOfStock(string $productName): void {
        $msg = "OUT OF STOCK CRITICAL: [{$productName}] has zero available inventory!";
        $this->notificationRepository->create(null, 'danger', $msg);
        $this->emailService->sendOutOfStockAlert($productName);
    }

    public function notifyStockMovement(string $productName, string $movementType, int $qty, string $userName): void {
        $msg = "{$movementType}: {$qty} units of [{$productName}] processed by {$userName}.";
        $this->notificationRepository->create(null, 'info', $msg);
    }

    public function getHeaderNotifications(?int $userId = null): array {
        return $this->notificationRepository->getUnread($userId);
    }

    public function markAllAsRead(?int $userId = null): bool {
        return $this->notificationRepository->markAllAsRead($userId);
    }
}
