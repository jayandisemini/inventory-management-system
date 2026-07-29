<?php

namespace App\Controllers;

use App\Repositories\AssemblyRepository;
use App\Repositories\ProductRepository;
use App\Core\CSRF;

class AssemblyController extends BaseController {
    private AssemblyRepository $assemblyRepo;
    private ProductRepository $productRepo;

    public function __construct() {
        $this->assemblyRepo = new AssemblyRepository();
        $this->productRepo = new ProductRepository();
    }

    public function index(): void {
        $this->requireLogin();

        $assemblies = $this->assemblyRepo->getAll();
        $products = $this->productRepo->getAll();

        $this->render('assemblies/index', [
            'activeNav' => 'assemblies',
            'assemblies' => $assemblies,
            'products' => $products
        ]);
    }

    public function store(): void {
        $this->requireLogin();
        $this->requireRole(['Admin', 'Inventory Manager']);
        CSRF::validateToken();

        $parentId = (int)($_POST['parent_product_id'] ?? 0);
        $componentId = (int)($_POST['component_product_id'] ?? 0);
        $requiredQty = (int)($_POST['required_qty'] ?? 0);
        $assembledUnits = (int)($_POST['assembled_units'] ?? 0);

        if ($parentId <= 0 || $componentId <= 0 || $requiredQty <= 0 || $assembledUnits <= 0) {
            $this->flash('error', 'Please fill in all required assembly recipe fields.');
            $this->redirect('/assemblies');
            return;
        }

        if ($parentId === $componentId) {
            $this->flash('error', 'Parent finished kit and component raw item cannot be the same product.');
            $this->redirect('/assemblies');
            return;
        }

        $code = 'ASM-' . date('Ymd') . '-' . rand(100, 999);

        $success = $this->assemblyRepo->create([
            'assembly_code' => $code,
            'parent_product_id' => $parentId,
            'component_product_id' => $componentId,
            'required_qty' => $requiredQty,
            'assembled_units' => $assembledUnits,
            'user_id' => $_SESSION['user']['user_id']
        ]);

        if ($success) {
            $this->flash('success', "Product Assembly {$code} executed successfully. Stock balances updated.");
        } else {
            $this->flash('error', 'Failed to execute product kit assembly.');
        }

        $this->redirect('/assemblies');
    }
}
