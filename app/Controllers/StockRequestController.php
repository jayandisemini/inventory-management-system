<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\RequestRepository;
use App\Repositories\ProductRepository;
use App\Services\InventoryService;

class StockRequestController extends Controller {
    private RequestRepository $requestRepository;
    private ProductRepository $productRepository;
    private InventoryService $inventoryService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->requestRepository = new RequestRepository();
        $this->productRepository = new ProductRepository();
        $this->inventoryService = new InventoryService();
    }

    public function index(): void {
        $user = $this->session->get('user');
        $roleName = $user['role_name'] ?? 'Staff';
        
        // Staff see their own requests; Managers/Admins see all pending and historical requests
        $userIdFilter = ($roleName === 'Staff') ? (int)$user['user_id'] : null;
        $requests = $this->requestRepository->getAll($userIdFilter);
        $products = $this->productRepository->getAll();

        $this->render('stock_requests/index', [
            'pageTitle' => 'Staff Requisitions & Request Approvals',
            'activeNav' => 'movements',
            'requests' => $requests,
            'products' => $products,
            'userRole' => $roleName
        ]);
    }

    public function store(): void {
        $this->validateCSRF();
        $body = $this->request->getBody();

        $productId = (int)($body['product_id'] ?? 0);
        $quantity = (int)($body['quantity'] ?? 0);
        $reason = trim($body['reason'] ?? '');

        if ($productId <= 0 || $quantity <= 0) {
            $this->session->setFlash('error', 'Valid product and positive quantity are required.');
            $this->response->redirect('/stock-requests');
        }

        $user = $this->session->get('user');

        $this->requestRepository->create([
            'product_id' => $productId,
            'user_id' => (int)$user['user_id'],
            'quantity' => $quantity,
            'reason' => $reason
        ]);

        $this->session->setFlash('success', 'Stock requisition submitted! Awaiting Manager approval.');
        $this->response->redirect('/stock-requests');
    }

    public function approve(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $id = (int)($this->request->getBody()['id'] ?? 0);
        $req = $this->requestRepository->findById($id);

        if (!$req || $req->status !== 'Pending') {
            $this->session->setFlash('error', 'Request is invalid or already processed.');
            $this->response->redirect('/stock-requests');
        }

        $user = $this->session->get('user');

        // Process Stock Out for the approved quantity
        $result = $this->inventoryService->processStockOut(
            $req->product_id,
            $req->quantity,
            "Approved Staff Requisition #REQ-{$req->request_id} for {$req->user_name}",
            (int)$user['user_id'],
            $user['name']
        );

        if (!$result['success']) {
            $this->session->setFlash('error', 'Cannot approve request: ' . $result['error']);
            $this->response->redirect('/stock-requests');
        }

        $this->requestRepository->updateStatus($id, 'Approved', (int)$user['user_id']);
        $this->session->setFlash('success', "Requisition #REQ-{$req->request_id} Approved! {$req->quantity} units deducted from inventory.");
        $this->response->redirect('/stock-requests');
    }

    public function reject(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $id = (int)($this->request->getBody()['id'] ?? 0);
        $req = $this->requestRepository->findById($id);

        if (!$req || $req->status !== 'Pending') {
            $this->session->setFlash('error', 'Request is invalid or already processed.');
            $this->response->redirect('/stock-requests');
        }

        $user = $this->session->get('user');
        $this->requestRepository->updateStatus($id, 'Rejected', (int)$user['user_id']);
        $this->session->setFlash('success', "Requisition #REQ-{$req->request_id} Rejected.");
        $this->response->redirect('/stock-requests');
    }
}
