<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\MovementRepository;
use App\Repositories\ProductRepository;

class MovementController extends Controller {
    private MovementRepository $movementRepository;
    private ProductRepository $productRepository;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->movementRepository = new MovementRepository();
        $this->productRepository = new ProductRepository();
    }

    public function index(): void {
        $filters = $this->request->getBody();
        $movements = $this->movementRepository->getAll($filters);
        $products = $this->productRepository->getAll();

        $this->render('movements/index', [
            'pageTitle' => 'Stock Movement Audit History',
            'activeNav' => 'movements',
            'movements' => $movements,
            'products' => $products,
            'filters' => $filters
        ]);
    }
}
