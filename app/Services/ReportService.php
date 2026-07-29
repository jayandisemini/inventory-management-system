<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\MovementRepository;
use App\Repositories\CategoryRepository;

class ReportService {
    private ProductRepository $productRepository;
    private SupplierRepository $supplierRepository;
    private MovementRepository $movementRepository;
    private CategoryRepository $categoryRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->supplierRepository = new SupplierRepository();
        $this->movementRepository = new MovementRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function getInventoryValueReport(): array {
        $products = $this->productRepository->getAll();
        $totalCostValuation = 0;
        $totalRetailValuation = 0;
        $totalItems = 0;

        foreach ($products as $p) {
            $totalCostValuation += ($p->quantity * $p->unit_price);
            $totalRetailValuation += ($p->quantity * $p->selling_price);
            $totalItems += $p->quantity;
        }

        return [
            'products' => $products,
            'total_cost_valuation' => $totalCostValuation,
            'total_retail_valuation' => $totalRetailValuation,
            'potential_profit' => $totalRetailValuation - $totalCostValuation,
            'total_items_count' => $totalItems
        ];
    }

    public function getLowStockReport(): array {
        return $this->productRepository->getLowStockProducts();
    }

    public function getMovementReport(array $filters = []): array {
        return $this->movementRepository->getAll($filters);
    }

    public function getSupplierReport(): array {
        return $this->supplierRepository->getAll();
    }

    public function getDashboardChartsData(): array {
        return [
            'category_distribution' => $this->movementRepository->getCategoryStockDistribution(),
            'monthly_movements' => $this->movementRepository->getMonthlyMovementStats(),
            'top_moving' => $this->movementRepository->getTopMovingProducts(5)
        ];
    }
}
