<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\ProductRepository;
use App\Services\InventoryService;

class InventoryController extends Controller {
    private ProductRepository $productRepository;
    private InventoryService $inventoryService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->productRepository = new ProductRepository();
        $this->inventoryService = new InventoryService();
    }

    public function showStockIn(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $products = $this->productRepository->getAll();

        $this->render('inventory/stock_in', [
            'pageTitle' => 'Process Stock In (Receiving)',
            'activeNav' => 'stock_in',
            'products' => $products
        ]);
    }

    public function processStockIn(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $productId = (int)($body['product_id'] ?? 0);
        $quantity = (int)($body['quantity'] ?? 0);
        $note = $body['reference_note'] ?? null;

        $user = $this->session->get('user');
        $userId = (int)$user['user_id'];
        $userName = $user['name'];

        $result = $this->inventoryService->processStockIn($productId, $quantity, $note, $userId, $userName);

        if (!$result['success']) {
            $this->session->setFlash('error', $result['error']);
            $this->response->redirect('/inventory/stock-in');
        }

        $this->session->setFlash('success', "Stock In recorded successfully! Updated product quantity is {$result['new_quantity']}.");
        $this->response->redirect('/movements');
    }

    public function showStockOut(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $products = $this->productRepository->getAll();

        $this->render('inventory/stock_out', [
            'pageTitle' => 'Process Stock Out (Dispatch / Sales)',
            'activeNav' => 'stock_out',
            'products' => $products
        ]);
    }

    public function processStockOut(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $productId = (int)($body['product_id'] ?? 0);
        $quantity = (int)($body['quantity'] ?? 0);
        $reason = $body['reference_note'] ?? null;

        $user = $this->session->get('user');
        $userId = (int)$user['user_id'];
        $userName = $user['name'];

        $result = $this->inventoryService->processStockOut($productId, $quantity, $reason, $userId, $userName);

        if (!$result['success']) {
            $this->session->setFlash('error', $result['error']);
            $this->response->redirect('/inventory/stock-out');
        }

        $this->session->setFlash('success', "Stock Out recorded successfully! Remaining stock quantity is {$result['new_quantity']}.");
        $this->response->redirect('/movements');
    }

    public function showAdjust(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $products = $this->productRepository->getAll();

        $this->render('inventory/adjust', [
            'pageTitle' => 'Stock Audit Adjustment',
            'activeNav' => 'stock_adjust',
            'products' => $products
        ]);
    }

    public function processAdjust(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $productId = (int)($body['product_id'] ?? 0);
        $newQuantity = (int)($body['new_quantity'] ?? 0);
        $reason = $body['reference_note'] ?? null;

        $user = $this->session->get('user');
        $userId = (int)$user['user_id'];
        $userName = $user['name'];

        $result = $this->inventoryService->processAdjustment($productId, $newQuantity, $reason, $userId, $userName);

        if (!$result['success']) {
            $this->session->setFlash('error', $result['error']);
            $this->response->redirect('/inventory/adjust');
        }

        $this->session->setFlash('success', "Stock inventory reconciled! New quantity set to {$result['new_quantity']}.");
        $this->response->redirect('/movements');
    }
}
