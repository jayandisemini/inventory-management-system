<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\MovementRepository;
use App\Repositories\UserRepository;
use App\Services\ReportService;

class DashboardController extends Controller {
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private SupplierRepository $supplierRepository;
    private MovementRepository $movementRepository;
    private UserRepository $userRepository;
    private ReportService $reportService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->supplierRepository = new SupplierRepository();
        $this->movementRepository = new MovementRepository();
        $this->userRepository = new UserRepository();
        $this->reportService = new ReportService();
    }

    public function index(): void {
        $user = $this->session->get('user');
        $roleName = $user['role_name'] ?? 'Staff';
        $roleId = (int)($user['role_id'] ?? 3);

        $metrics = $this->productRepository->getSummaryMetrics();
        $categories = $this->categoryRepository->getAll();
        $suppliers = $this->supplierRepository->getAll();
        $allProducts = $this->productRepository->getAll();
        $lowStockProducts = $this->productRepository->getLowStockProducts();
        $recentMovements = $this->movementRepository->getAll(['limit' => 8]);
        $chartsData = $this->reportService->getDashboardChartsData();

        // Calculate Stock Health percentage
        $totalProds = max(1, $metrics['total_products']);
        $healthyCount = max(0, $totalProds - ($metrics['low_stock_count'] + $metrics['out_of_stock_count']));
        $healthPercentage = round(($healthyCount / $totalProds) * 100);

        // Calculate Valuation Breakdown
        $valData = $this->reportService->getInventoryValueReport();

        // Compute Smart Restock Recommendations for Manager
        $restockQueue = [];
        foreach ($lowStockProducts as $p) {
            $suggestedQty = max(1, ($p->min_stock_level * 2) - $p->quantity);
            $p->suggested_reorder_qty = $suggestedQty;
            $restockQueue[] = $p;
        }

        // Render role-tailored view
        if ($roleId === 1 || $roleName === 'Admin') {
            $usersCount = count($this->userRepository->getAll());
            $this->render('dashboard/admin', [
                'pageTitle' => 'Admin Executive Command Center',
                'activeNav' => 'dashboard',
                'metrics' => array_merge($metrics, [
                    'total_categories' => count($categories),
                    'total_suppliers' => count($suppliers),
                    'total_users' => $usersCount,
                    'potential_profit' => $valData['potential_profit'] ?? 0,
                    'cost_valuation' => $valData['total_cost_valuation'] ?? 0,
                    'retail_valuation' => $valData['total_retail_valuation'] ?? 0,
                    'health_percentage' => $healthPercentage
                ]),
                'lowStockProducts' => $lowStockProducts,
                'recentMovements' => $recentMovements,
                'chartsData' => $chartsData
            ]);
        } elseif ($roleId === 2 || $roleName === 'Inventory Manager') {
            $this->render('dashboard/manager', [
                'pageTitle' => 'Manager Operations Hub & Restock Engine',
                'activeNav' => 'dashboard',
                'metrics' => array_merge($metrics, [
                    'total_categories' => count($categories),
                    'total_suppliers' => count($suppliers),
                    'healthy_count' => $healthyCount,
                    'health_percentage' => $healthPercentage
                ]),
                'products' => $allProducts,
                'restockQueue' => $restockQueue,
                'recentMovements' => $recentMovements,
                'chartsData' => $chartsData
            ]);
        } else {
            // Staff Role View
            $this->render('dashboard/staff', [
                'pageTitle' => 'Staff Inventory Request & Search Terminal',
                'activeNav' => 'dashboard',
                'products' => $allProducts,
                'lowStockProducts' => $lowStockProducts,
                'recentMovements' => $recentMovements
            ]);
        }
    }
}
