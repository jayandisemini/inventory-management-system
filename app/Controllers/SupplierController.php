<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\SupplierRepository;

class SupplierController extends Controller {
    private SupplierRepository $supplierRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->supplierRepository = new SupplierRepository();
    }

    public function index(): void {
        $suppliers = $this->supplierRepository->getAll();

        $this->render('suppliers/index', [
            'pageTitle' => 'Supplier Management Directory',
            'activeNav' => 'suppliers',
            'suppliers' => $suppliers
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        if (empty($body['supplier_name'])) {
            $this->session->setFlash('error', 'Supplier Name is required.');
            $this->response->redirect('/suppliers');
        }

        $this->supplierRepository->create($body);
        $this->session->setFlash('success', "Supplier '{$body['supplier_name']}' created successfully!");
        $this->response->redirect('/suppliers');
    }

    public function update(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $body = $this->request->getBody();
        $id = (int)($body['id'] ?? 0);

        if ($id <= 0 || empty($body['supplier_name'])) {
            $this->session->setFlash('error', 'Supplier ID and Name are required.');
            $this->response->redirect('/suppliers');
        }

        $this->supplierRepository->update($id, $body);
        $this->session->setFlash('success', "Supplier details updated successfully!");
        $this->response->redirect('/suppliers');
    }

    public function show(): void {
        $id = (int)($this->request->getBody()['id'] ?? 0);
        $supplier = $this->supplierRepository->findById($id);

        if (!$supplier) {
            $this->session->setFlash('error', 'Requested supplier could not be found.');
            $this->response->redirect('/suppliers');
        }

        $products = $this->supplierRepository->getProductsBySupplierId($id);

        $this->render('suppliers/show', [
            'pageTitle' => "Supplier Profile - {$supplier->supplier_name}",
            'activeNav' => 'suppliers',
            'supplier' => $supplier,
            'products' => $products
        ]);
    }

    public function delete(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();

        $id = (int)($this->request->getBody()['id'] ?? 0);
        $supplier = $this->supplierRepository->findById($id);

        if (!$supplier) {
            $this->session->setFlash('error', 'Supplier not found.');
            $this->response->redirect('/suppliers');
        }

        if ($supplier->product_count > 0) {
            $this->session->setFlash('error', "Cannot delete supplier '{$supplier->supplier_name}' because it supplies {$supplier->product_count} active product(s).");
            $this->response->redirect('/suppliers');
        }

        $this->supplierRepository->delete($id);
        $this->session->setFlash('success', 'Supplier profile deleted successfully!');
        $this->response->redirect('/suppliers');
    }
}
