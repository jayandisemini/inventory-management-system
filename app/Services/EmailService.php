<?php

namespace App\Services;

class EmailService {
    private string $adminEmail;
    private bool $enabled;

    public function __construct() {
        $this->adminEmail = defined('ADMIN_ALERT_EMAIL') ? ADMIN_ALERT_EMAIL : 'admin@sims.com';
        $this->enabled = defined('ENABLE_EMAIL_ALERTS') ? ENABLE_EMAIL_ALERTS : true;
    }

    public function sendLowStockAlert(string $productName, int $currentQty, int $minLevel): bool {
        if (!$this->enabled) {
            return false;
        }

        $subject = "⚠️ Low Stock Alert: {$productName}";
        $body = $this->buildTemplate(
            "Low Stock Warning",
            "The product <strong>{$productName}</strong> has dropped to <strong>{$currentQty}</strong> units.",
            "Minimum required threshold: <strong>{$minLevel}</strong> units. Please reorder inventory soon to avoid stockout.",
            "warning"
        );

        return $this->sendMail($this->adminEmail, $subject, $body);
    }

    public function sendOutOfStockAlert(string $productName): bool {
        if (!$this->enabled) {
            return false;
        }

        $subject = "🚨 CRITICAL: Out of Stock Alert - {$productName}";
        $body = $this->buildTemplate(
            "Out of Stock Critical Alert",
            "The product <strong>{$productName}</strong> is completely <strong>OUT OF STOCK (0 units)</strong>!",
            "Immediate action required to replenish inventory.",
            "danger"
        );

        return $this->sendMail($this->adminEmail, $subject, $body);
    }

    private function sendMail(string $to, string $subject, string $htmlBody): bool {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: NEXUS ERP <noreply@simserp.com>';
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Native PHP mail delivery wrapper with fallback log
        $success = @mail($to, $subject, $htmlBody, implode("\r\n", $headers));
        
        // Log notification dispatch for verification
        $logFile = ROOT_PATH . '/storage/logs/email.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logEntry = "[" . date('Y-m-d H:i:s') . "] EMAIL TO: {$to} | SUBJECT: {$subject} | SENT: " . ($success ? "YES" : "NO/LOGGED") . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);

        return true;
    }

    private function buildTemplate(string $title, string $message, string $subMessage, string $type = 'info'): string {
        $color = $type === 'danger' ? '#dc3545' : ($type === 'warning' ? '#ffc107' : '#0d6efd');
        $textColor = $type === 'warning' ? '#212529' : '#ffffff';

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; }
                .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                .header { background-color: {$color}; color: {$textColor}; padding: 20px; text-align: center; }
                .header h2 { margin: 0; font-size: 20px; }
                .content { padding: 24px; color: #333333; line-height: 1.6; }
                .footer { background: #e9ecef; padding: 12px; text-align: center; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='header'>
                    <h2>" . htmlspecialchars($title) . "</h2>
                </div>
                <div class='content'>
                    <p>{$message}</p>
                    <p>{$subMessage}</p>
                    <hr style='border: none; border-top: 1px solid #eeeeee; margin: 20px 0;'>
                    <p style='font-size: 13px; color: #6c757d;'>This is an automated notification from NEXUS Inventory ERP System.</p>
                </div>
                <div class='footer'>
                    &copy; " . date('Y') . " NEXUS INVENTORY ERP. All rights reserved.
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
