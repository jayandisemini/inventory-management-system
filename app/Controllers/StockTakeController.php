<?php

namespace App\Controllers;

use App\Repositories\StockTakeRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class StockTakeController extends BaseController {
    private StockTakeRepository $stockTakeRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        $this->stockTakeRepo = new StockTakeRepository();
        $this->warehouseRepo = new WarehouseRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $this->requireLogin();

        $stockTakes = $this->stockTakeRepo->getAll();
        $warehouses = $this->warehouseRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('stock_takes/index', [
            'activeNav' => 'stock_takes',
            'stockTakes' => $stockTakes,
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->requireRole(['Admin', 'Inventory Manager']);
        CSRF::validateToken();

        $warehouseId = (int)($_POST['warehouse_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $countedQty = (int)($_POST['counted_qty'] ?? -1);
        $notes = trim($_POST['notes'] ?? '');

        if ($warehouseId <= 0 || $productId <= 0 || $countedQty < 0) {
            $this->flash('error', 'Please fill in all required count audit fields.');
            $this->redirect('/stock-takes');
            return;
        }

        $product = $this->productRepo->getById($productId);
        $expectedQty = $product ? $product->quantity : 0;
        $code = 'STK-' . date('Ymd') . '-' . rand(100, 999);

        $success = $this->stockTakeRepo->create([
            'take_code' => $code,
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'expected_qty' => $expectedQty,
            'counted_qty' => $countedQty,
            'conducted_by' => $_SESSION['user']['user_id'],
            'notes' => $notes
        ]);

        if ($success) {
            $this->flash('success', "Stock-Take Reconciliation {$code} logged successfully.");
        } else {
            $this->flash('error', 'Failed to log stock-take audit count.');
        }

        $this->redirect('/stock-takes');
    }
}
