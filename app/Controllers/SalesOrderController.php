<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\SORepository;
use App\Repositories\ProductRepository;
use App\Services\InventoryService;

class SalesOrderController extends Controller {
    private SORepository $soRepository;
    private ProductRepository $productRepository;
    private InventoryService $inventoryService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->soRepository = new SORepository();
        $this->productRepository = new ProductRepository();
        $this->inventoryService = new InventoryService();
    }

    public function index(): void {
        $orders = $this->soRepository->getAll();

        $this->render('sales_orders/index', [
            'pageTitle' => 'Customer Sales Orders & Invoices',
            'activeNav' => 'reports',
            'orders' => $orders
        ]);
    }

    public function create(): void {
        $products = $this->productRepository->getAll();

        $this->render('sales_orders/create', [
            'pageTitle' => 'Create New Customer Sales Invoice',
            'activeNav' => 'reports',
            'products' => $products
        ]);
    }

    public function store(): void {
        $this->validateCSRF();
        $body = $this->request->getRawPost();

        $customerName = trim($body['customer_name'] ?? '');
        $customerEmail = trim($body['customer_email'] ?? '');
        $productIds = $body['product_id'] ?? [];
        $quantities = $body['quantity'] ?? [];
        $unitPrices = $body['unit_price'] ?? [];
        $notes = $body['notes'] ?? null;

        if (empty($customerName) || empty($productIds)) {
            $this->session->setFlash('error', 'Customer name and at least one line item product are required.');
            $this->response->redirect('/sales-orders/create');
        }

        $user = $this->session->get('user');
        $items = [];
        $totalAmount = 0.00;

        // Check stock availability and process line items
        for ($i = 0; $i < count($productIds); $i++) {
            $pId = (int)$productIds[$i];
            $qty = (int)($quantities[$i] ?? 0);
            $price = (float)($unitPrices[$i] ?? 0);

            if ($pId > 0 && $qty > 0) {
                $product = $this->productRepository->findById($pId);
                if ($product->quantity < $qty) {
                    $this->session->setFlash('error', "Insufficient stock for '{$product->product_name}'. Requested: {$qty}, Available: {$product->quantity}");
                    $this->response->redirect('/sales-orders/create');
                }

                $items[] = [
                    'product_id' => $pId,
                    'quantity' => $qty,
                    'unit_price' => $price
                ];
                $totalAmount += ($qty * $price);
            }
        }

        if (empty($items)) {
            $this->session->setFlash('error', 'Please enter valid positive quantities.');
            $this->response->redirect('/sales-orders/create');
        }

        // Save Sales Order
        $soId = $this->soRepository->create([
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'total_amount' => $totalAmount,
            'payment_status' => 'Paid',
            'notes' => $notes,
            'user_id' => (int)$user['user_id']
        ], $items);

        // Process Stock Out auto deduction for each sold product
        foreach ($items as $item) {
            $this->inventoryService->processStockOut(
                $item['product_id'],
                $item['quantity'],
                "Customer Sales Order #{$soId} for {$customerName}",
                (int)$user['user_id'],
                $user['name']
            );
        }

        $this->session->setFlash('success', 'Sales Invoice created! Inventory stock automatically deducted.');
        $this->response->redirect("/sales-orders/show?id={$soId}");
    }

    public function show(): void {
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $so = $this->soRepository->findById($id);

        if (!$so) {
            $this->session->setFlash('error', 'Sales Order not found.');
            $this->response->redirect('/sales-orders');
        }

        $this->render('sales_orders/show', [
            'pageTitle' => "Sales Invoice - {$so->order_number}",
            'activeNav' => 'reports',
            'so' => $so
        ]);
    }

    public function printReceipt(): void {
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $so = $this->soRepository->findById($id);

        if (!$so) {
            $this->session->setFlash('error', 'Sales Order not found.');
            $this->response->redirect('/sales-orders');
        }

        $this->renderAuthView('sales_orders/print', [
            'pageTitle' => "Customer Receipt - {$so->order_number}",
            'so' => $so
        ]);
    }
}
