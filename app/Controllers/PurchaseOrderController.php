<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\PORepository;
use App\Repositories\SupplierRepository;
use App\Repositories\ProductRepository;
use App\Services\InventoryService;

class PurchaseOrderController extends Controller {
    private PORepository $poRepository;
    private SupplierRepository $supplierRepository;
    private ProductRepository $productRepository;
    private InventoryService $inventoryService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->poRepository = new PORepository();
        $this->supplierRepository = new SupplierRepository();
        $this->productRepository = new ProductRepository();
        $this->inventoryService = new InventoryService();
    }

    public function index(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $orders = $this->poRepository->getAll();

        $this->render('purchase_orders/index', [
            'pageTitle' => 'Purchase Order (PO) Procurement',
            'activeNav' => 'reports',
            'orders' => $orders
        ]);
    }

    public function create(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $suppliers = $this->supplierRepository->getAll();
        $products = $this->productRepository->getAll();

        $this->render('purchase_orders/create', [
            'pageTitle' => 'Generate New Supplier Purchase Order',
            'activeNav' => 'reports',
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getRawPost();
        $supplierId = (int)($body['supplier_id'] ?? 0);
        $productIds = $body['product_id'] ?? [];
        $quantities = $body['quantity'] ?? [];
        $unitCosts = $body['unit_cost'] ?? [];
        $notes = $body['notes'] ?? null;

        if ($supplierId <= 0 || empty($productIds)) {
            $this->session->setFlash('error', 'Supplier and at least one product line item are required.');
            $this->response->redirect('/purchase-orders/create');
        }

        $items = [];
        $totalAmount = 0.00;

        for ($i = 0; $i < count($productIds); $i++) {
            $pId = (int)$productIds[$i];
            $qty = (int)($quantities[$i] ?? 0);
            $cost = (float)($unitCosts[$i] ?? 0);

            if ($pId > 0 && $qty > 0) {
                $items[] = [
                    'product_id' => $pId,
                    'quantity' => $qty,
                    'unit_cost' => $cost
                ];
                $totalAmount += ($qty * $cost);
            }
        }

        if (empty($items)) {
            $this->session->setFlash('error', 'Please enter valid positive quantities for PO items.');
            $this->response->redirect('/purchase-orders/create');
        }

        $user = $this->session->get('user');
        $poId = $this->poRepository->create([
            'supplier_id' => $supplierId,
            'user_id' => (int)$user['user_id'],
            'total_amount' => $totalAmount,
            'notes' => $notes
        ], $items);

        $this->session->setFlash('success', "Purchase Order generated successfully!");
        $this->response->redirect("/purchase-orders/show?id={$poId}");
    }

    public function show(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $po = $this->poRepository->findById($id);

        if (!$po) {
            $this->session->setFlash('error', 'Purchase Order not found.');
            $this->response->redirect('/purchase-orders');
        }

        $supplier = $this->supplierRepository->findById($po->supplier_id);

        $this->render('purchase_orders/show', [
            'pageTitle' => "Purchase Order - {$po->po_number}",
            'activeNav' => 'reports',
            'po' => $po,
            'supplier' => $supplier
        ]);
    }

    public function printPO(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $po = $this->poRepository->findById($id);

        if (!$po) {
            $this->session->setFlash('error', 'Purchase Order not found.');
            $this->response->redirect('/purchase-orders');
        }

        $supplier = $this->supplierRepository->findById($po->supplier_id);

        $this->renderAuthView('purchase_orders/print', [
            'pageTitle' => "Print PO - {$po->po_number}",
            'po' => $po,
            'supplier' => $supplier
        ]);
    }

    public function markReceived(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $poId = (int)($this->request->getBody()['id'] ?? 0);
        $po = $this->poRepository->findById($poId);

        if (!$po || $po->status === 'Received') {
            $this->session->setFlash('error', 'Purchase order is already marked as received or invalid.');
            $this->response->redirect('/purchase-orders');
        }

        $user = $this->session->get('user');

        // Process Stock In for each item in the PO
        foreach ($po->items as $item) {
            $this->inventoryService->processStockIn(
                (int)$item['product_id'],
                (int)$item['quantity'],
                "Auto-restocked from PO {$po->po_number}",
                (int)$user['user_id'],
                $user['name']
            );
        }

        $this->poRepository->updateStatus($poId, 'Received');
        $this->session->setFlash('success', "Purchase Order {$po->po_number} marked as Received! All items have been automatically restocked into inventory.");
        $this->response->redirect("/purchase-orders/show?id={$poId}");
    }
}
