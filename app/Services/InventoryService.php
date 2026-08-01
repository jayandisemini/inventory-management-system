<?php

namespace App\Services;

use App\Core\Database;
use App\Repositories\ProductRepository;
use App\Repositories\MovementRepository;
use Exception;
use PDO;

class InventoryService {
    private ProductRepository $productRepository;
    private MovementRepository $movementRepository;
    private NotificationService $notificationService;
    private PDO $db;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->movementRepository = new MovementRepository();
        $this->notificationService = new NotificationService();
        $this->db = Database::getInstance();
    }

    public function processStockIn(int $productId, int $quantity, ?string $note, int $userId, string $userName): array {
        if ($quantity <= 0) {
            return ['success' => false, 'error' => 'Quantity for Stock In must be greater than zero.'];
        }

        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return ['success' => false, 'error' => 'Selected product does not exist.'];
        }

        $this->db->beginTransaction();
        try {
            $newQty = $product->quantity + $quantity;
            $this->productRepository->updateQuantity($productId, $newQty);

            $this->movementRepository->create([
                'product_id' => $productId,
                'movement_type' => 'Stock In',
                'quantity' => $quantity,
                'reference_note' => $note ?: 'Stock In replenishment',
                'user_id' => $userId
            ]);

            $this->db->commit();

            $this->notificationService->notifyStockMovement($product->product_name, 'Stock In', $quantity, $userName);

            return ['success' => true, 'new_quantity' => $newQty];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'Database transaction failed: ' . $e->getMessage()];
        }
    }

    public function processStockOut(int $productId, int $quantity, ?string $reason, int $userId, string $userName): array {
        if ($quantity <= 0) {
            return ['success' => false, 'error' => 'Quantity for Stock Out must be greater than zero.'];
        }

        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return ['success' => false, 'error' => 'Selected product does not exist.'];
        }

        if ($product->quantity < $quantity) {
            return [
                'success' => false, 
                'error' => "Cannot remove {$quantity} units. Current available stock is only {$product->quantity} units."
            ];
        }

        $this->db->beginTransaction();
        try {
            $newQty = $product->quantity - $quantity;
            $this->productRepository->updateQuantity($productId, $newQty);

            $this->movementRepository->create([
                'product_id' => $productId,
                'movement_type' => 'Stock Out',
                'quantity' => $quantity,
                'reference_note' => $reason ?: 'Stock Out dispatch',
                'user_id' => $userId
            ]);

            $this->db->commit();

            if ($newQty <= 0) {
                $this->notificationService->notifyOutOfStock($product->product_name);
            } elseif ($newQty <= $product->min_stock_level) {
                $this->notificationService->notifyLowStock($product->product_name, $newQty, $product->min_stock_level);
            }

            $this->notificationService->notifyStockMovement($product->product_name, 'Stock Out', $quantity, $userName);

            return ['success' => true, 'new_quantity' => $newQty];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'Database transaction failed: ' . $e->getMessage()];
        }
    }

    public function processAdjustment(int $productId, int $newQuantity, ?string $reason, int $userId, string $userName): array {
        if ($newQuantity < 0) {
            return ['success' => false, 'error' => 'Adjusted quantity cannot be negative.'];
        }

        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return ['success' => false, 'error' => 'Selected product does not exist.'];
        }

        $difference = $newQuantity - $product->quantity;
        if ($difference === 0) {
            return ['success' => false, 'error' => 'Adjusted quantity is identical to current stock level.'];
        }

        $this->db->beginTransaction();
        try {
            $this->productRepository->updateQuantity($productId, $newQuantity);

            $this->movementRepository->create([
                'product_id' => $productId,
                'movement_type' => 'Adjustment',
                'quantity' => abs($difference),
                'reference_note' => $reason ? "Adjustment (" . ($difference > 0 ? "+$difference" : "$difference") . "): $reason" : "Inventory reconciliation adjustment (" . ($difference > 0 ? "+$difference" : "$difference") . ")",
                'user_id' => $userId
            ]);

            $this->db->commit();

            if ($newQuantity <= 0) {
                $this->notificationService->notifyOutOfStock($product->product_name);
            } elseif ($newQuantity <= $product->min_stock_level) {
                $this->notificationService->notifyLowStock($product->product_name, $newQuantity, $product->min_stock_level);
            }

            $this->notificationService->notifyStockMovement($product->product_name, 'Adjustment', abs($difference), $userName);

            return ['success' => true, 'new_quantity' => $newQuantity];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'Database transaction failed: ' . $e->getMessage()];
        }
    }

    public function adjustStock(int $productId, int $newQuantity, int $userId = 1, ?string $note = null, string $userName = 'API System'): bool {
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return false;
        }

        if ((int)$product->quantity === $newQuantity) {
            return true;
        }

        $result = $this->processAdjustment($productId, $newQuantity, $note, $userId, $userName);
        return !empty($result['success']);
    }
}
