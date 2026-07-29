<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\WarehouseRepository;
use App\Repositories\ProductRepository;

class WarehouseController extends Controller {
    private WarehouseRepository $warehouseRepository;
    private ProductRepository $productRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->warehouseRepository = new WarehouseRepository();
        $this->productRepository = new ProductRepository();
    }

    public function index(): void {
        $warehouses = $this->warehouseRepository->getAll();
        $products = $this->productRepository->getAll();

        $this->render('warehouses/index', [
            'pageTitle' => 'Multi-Warehouse & Store Locations',
            'activeNav' => 'suppliers',
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }

    public function store(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();
        $body = $this->request->getBody();

        $name = trim($body['warehouse_name'] ?? '');
        $code = trim($body['code'] ?? '');

        if (empty($name) || empty($code)) {
            $this->session->setFlash('error', 'Warehouse name and unique code are required.');
            $this->response->redirect('/warehouses');
        }

        $this->warehouseRepository->create([
            'warehouse_name' => $name,
            'code' => strtoupper($code),
            'location' => trim($body['location'] ?? ''),
            'manager_name' => trim($body['manager_name'] ?? ''),
            'phone' => trim($body['phone'] ?? '')
        ]);

        $this->session->setFlash('success', 'New warehouse storage location added!');
        $this->response->redirect('/warehouses');
    }

    public function update(): void {
        AuthMiddleware::authorize(['Admin', 'Inventory Manager']);
        $this->validateCSRF();
        $body = $this->request->getBody();
        $id = (int)($body['id'] ?? 0);

        if ($id <= 0) {
            $this->session->setFlash('error', 'Invalid warehouse ID.');
            $this->response->redirect('/warehouses');
        }

        $this->warehouseRepository->update($id, [
            'warehouse_name' => trim($body['warehouse_name'] ?? ''),
            'code' => strtoupper(trim($body['code'] ?? '')),
            'location' => trim($body['location'] ?? ''),
            'manager_name' => trim($body['manager_name'] ?? ''),
            'phone' => trim($body['phone'] ?? '')
        ]);

        $this->session->setFlash('success', 'Warehouse details updated.');
        $this->response->redirect('/warehouses');
    }

    public function delete(): void {
        AuthMiddleware::authorize(['Admin']);
        $this->validateCSRF();
        $id = (int)($this->request->getBody()['id'] ?? 0);

        if ($id <= 0) {
            $this->session->setFlash('error', 'Invalid warehouse ID.');
            $this->response->redirect('/warehouses');
        }

        $this->warehouseRepository->delete($id);
        $this->session->setFlash('success', 'Warehouse location deleted.');
        $this->response->redirect('/warehouses');
    }
}
