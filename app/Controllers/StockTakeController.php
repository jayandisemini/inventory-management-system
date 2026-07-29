<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\StockTakeRepository;
use App\Repositories\WarehouseRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class StockTakeController extends Controller {
    private StockTakeRepository $stockTakeRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->stockTakeRepo = new StockTakeRepository();
        $this->warehouseRepo = new WarehouseRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $stockTakes = $this->stockTakeRepo->getAll();
        $warehouses = $this->warehouseRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('stock_takes/index', [
            'pageTitle' => 'Stock-Take & Audit Reconciliation',
            'activeNav' => 'stock_takes',
            'stockTakes' => $stockTakes,
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);

        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'CSRF validation failed.'];
            $this->response->redirect('/stock-takes');
            return;
        }

        $warehouseId = (int)($_POST['warehouse_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $countedQty = (int)($_POST['counted_qty'] ?? -1);
        $notes = trim($_POST['notes'] ?? '');

        if ($warehouseId <= 0 || $productId <= 0 || $countedQty < 0) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Please fill in all required count audit fields.'];
            $this->response->redirect('/stock-takes');
            return;
        }

        $product = $this->productRepo->findById($productId);
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
            $_SESSION['flash_messages'][] = ['type' => 'success', 'value' => "Stock-Take Reconciliation {$code} logged successfully."];
        } else {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Failed to log stock-take audit count.'];
        }

        $this->response->redirect('/stock-takes');
    }
}
