<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\BatchRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class BatchController extends Controller {
    private BatchRepository $batchRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->batchRepo = new BatchRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $batches = $this->batchRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('batches/index', [
            'pageTitle' => 'Batch & Expiry Date Tracking',
            'activeNav' => 'batches',
            'batches' => $batches,
            'products' => $products
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        
        if (!CSRF::verifyToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'CSRF validation failed.'];
            $this->response->redirect('/batches');
            return;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $batchNumber = trim($_POST['batch_number'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 0);
        $mfdDate = trim($_POST['mfd_date'] ?? '');
        $expiryDate = trim($_POST['expiry_date'] ?? '');

        if ($productId <= 0 || empty($batchNumber) || $quantity <= 0 || empty($expiryDate)) {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Please fill in all required batch fields.'];
            $this->response->redirect('/batches');
            return;
        }

        $success = $this->batchRepo->create([
            'product_id' => $productId,
            'batch_number' => $batchNumber,
            'quantity' => $quantity,
            'mfd_date' => $mfdDate,
            'expiry_date' => $expiryDate
        ]);

        if ($success) {
            $_SESSION['flash_messages'][] = ['type' => 'success', 'value' => "Batch #{$batchNumber} registered successfully."];
        } else {
            $_SESSION['flash_messages'][] = ['type' => 'error', 'value' => 'Failed to register batch.'];
        }

        $this->response->redirect('/batches');
    }
}
