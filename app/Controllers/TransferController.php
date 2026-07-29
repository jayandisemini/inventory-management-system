<?php

namespace App\Controllers;

use App\Repositories\TransferRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class TransferController extends BaseController {
    private TransferRepository $transferRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        $this->transferRepo = new TransferRepository();
        $this->warehouseRepo = new WarehouseRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $this->requireLogin();

        $transfers = $this->transferRepo->getAll();
        $warehouses = $this->warehouseRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('transfers/index', [
            'activeNav' => 'transfers',
            'transfers' => $transfers,
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->requireRole(['Admin', 'Inventory Manager']);
        CSRF::validateToken();

        $sourceId = (int)($_POST['source_warehouse_id'] ?? 0);
        $destId = (int)($_POST['dest_warehouse_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

        if ($sourceId <= 0 || $destId <= 0 || $productId <= 0 || $quantity <= 0) {
            $this->flash('error', 'Please fill in all required transfer fields.');
            $this->redirect('/transfers');
            return;
        }

        if ($sourceId === $destId) {
            $this->flash('error', 'Source and destination warehouses cannot be the same location.');
            $this->redirect('/transfers');
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
            $this->flash('success', "Stock Transfer {$code} executed successfully.");
        } else {
            $this->flash('error', 'Failed to create stock transfer order.');
        }

        $this->redirect('/transfers');
    }
}
