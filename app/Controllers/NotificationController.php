<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Services\NotificationService;

class NotificationController extends Controller {
    private NotificationService $notificationService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->notificationService = new NotificationService();
    }

    public function getUnread(): void {
        $user = $this->session->get('user');
        $notifications = $this->notificationService->getHeaderNotifications((int)$user['user_id']);
        $this->response->json(['success' => true, 'notifications' => $notifications, 'count' => count($notifications)]);
    }

    public function markRead(): void {
        $user = $this->session->get('user');
        $this->notificationService->markAllAsRead((int)$user['user_id']);
        $this->response->json(['success' => true]);
    }
}
