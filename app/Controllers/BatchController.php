<?php

namespace App\Controllers;

use App\Repositories\BatchRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class BatchController extends BaseController {
    private BatchRepository $batchRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        $this->batchRepo = new BatchRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $this->requireLogin();

        $batches = $this->batchRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('batches/index', [
            'activeNav' => 'batches',
            'batches' => $batches,
            'products' => $products
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->requireRole(['Admin', 'Inventory Manager']);
        CSRF::validateToken();

        $productId = (int)($_POST['product_id'] ?? 0);
        $batchNumber = trim($_POST['batch_number'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 0);
        $mfdDate = trim($_POST['mfd_date'] ?? '');
        $expiryDate = trim($_POST['expiry_date'] ?? '');

        if ($productId <= 0 || empty($batchNumber) || $quantity <= 0 || empty($expiryDate)) {
            $this->flash('error', 'Please fill in all required batch fields.');
            $this->redirect('/batches');
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
            $this->flash('success', "Batch #{$batchNumber} registered successfully.");
        } else {
            $this->flash('error', 'Failed to register batch.');
        }

        $this->redirect('/batches');
    }
}
