<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\MovementRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SORepository;
use App\Repositories\BatchRepository;
use App\Repositories\PORepository;

class ReportService {
    private ProductRepository $productRepository;
    private SupplierRepository $supplierRepository;
    private MovementRepository $movementRepository;
    private CategoryRepository $categoryRepository;
    private SORepository $soRepository;
    private BatchRepository $batchRepository;
    private PORepository $poRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->supplierRepository = new SupplierRepository();
        $this->movementRepository = new MovementRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->soRepository = new SORepository();
        $this->batchRepository = new BatchRepository();
        $this->poRepository = new PORepository();
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

    public function getSalesRevenueReport(): array {
        $orders = $this->soRepository->getAll();
        $totalRevenue = 0.0;
        $paidCount = 0;
        $pendingCount = 0;

        foreach ($orders as $o) {
            $totalRevenue += (float)$o->total_amount;
            if (strtolower($o->payment_status ?? '') === 'paid') {
                $paidCount++;
            } else {
                $pendingCount++;
            }
        }

        $orderCount = count($orders);
        $avgOrderValue = $orderCount > 0 ? ($totalRevenue / $orderCount) : 0.0;

        return [
            'orders' => $orders,
            'total_revenue' => $totalRevenue,
            'total_orders' => $orderCount,
            'avg_order_value' => $avgOrderValue,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount
        ];
    }

    public function getBatchExpiryReport(): array {
        $batches = $this->batchRepository->getAll();
        $expiringCount = 0;
        $expiredCount = 0;
        $totalAtRiskQty = 0;

        foreach ($batches as $b) {
            if ($b->status === 'Expiring Soon') {
                $expiringCount++;
                $totalAtRiskQty += $b->quantity;
            } elseif ($b->status === 'Expired') {
                $expiredCount++;
            }
        }

        return [
            'batches' => $batches,
            'expiring_soon_count' => $expiringCount,
            'expired_count' => $expiredCount,
            'at_risk_qty' => $totalAtRiskQty
        ];
    }

    public function getSupplierProcurementReport(): array {
        $purchaseOrders = $this->poRepository->getAll();
        $totalSpend = 0.0;
        $sentCount = 0;
        $receivedCount = 0;

        foreach ($purchaseOrders as $po) {
            $totalSpend += (float)$po->total_amount;
            if (strtolower($po->status ?? '') === 'received') {
                $receivedCount++;
            } else {
                $sentCount++;
            }
        }

        return [
            'purchase_orders' => $purchaseOrders,
            'total_spend' => $totalSpend,
            'total_pos' => count($purchaseOrders),
            'sent_count' => $sentCount,
            'received_count' => $receivedCount
        ];
    }

    public function getDashboardChartsData(): array {
        return [
            'category_distribution' => $this->movementRepository->getCategoryStockDistribution(),
            'monthly_movements' => $this->movementRepository->getMonthlyMovementStats(),
            'top_moving' => $this->movementRepository->getTopMovingProducts(5)
        ];
    }
}
