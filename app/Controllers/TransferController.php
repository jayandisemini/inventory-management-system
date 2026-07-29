<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\TransferRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class TransferController extends Controller {
    private TransferRepository $transferRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->transferRepo = new TransferRepository();
        $this->warehouseRepo = new WarehouseRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $transfers = $this->transferRepo->getAll();
        $warehouses = $this->warehouseRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('transfers/index', [
            'pageTitle' => 'Inter-Warehouse Stock Transfers',
            'activeNav' => 'transfers',
            'transfers' => $transfers,
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);

        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'CSRF validation failed.'];
            $this->response->redirect('/transfers');
            return;
        }

        $sourceId = (int)($_POST['source_warehouse_id'] ?? 0);
        $destId = (int)($_POST['dest_warehouse_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

        if ($sourceId <= 0 || $destId <= 0 || $productId <= 0 || $quantity <= 0) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Please fill in all required transfer fields.'];
            $this->response->redirect('/transfers');
            return;
        }

        if ($sourceId === $destId) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Source and destination warehouses cannot be the same location.'];
            $this->response->redirect('/transfers');
            return;
        }

        $code = 'TRF-' . date('Ymd') . '-' . rand(100, 999);

        $success = $this->transferRepo->create([
            'transfer_code' => $code,
            'source_warehouse_id' => $sourceId,
            'dest_warehouse_id' => $destId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 'Completed',
            'user_id' => $_SESSION['user']['user_id'],
            'notes' => $notes
        ]);

        if ($success) {
            $_SESSION['flash_messages'][] = ['type' => 'success', 'value' => "Stock Transfer {$code} executed successfully."];
        } else {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Failed to create stock transfer order.'];
        }

        $this->response->redirect('/transfers');
    }
}
