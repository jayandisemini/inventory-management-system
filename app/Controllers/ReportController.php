<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Services\ReportService;

class ReportController extends Controller {
    private ReportService $reportService;

    public function __construct() {
        parent::__construct();
        AuthMiddleware::check();
        $this->reportService = new ReportService();
    }

    public function index(): void {
        $type = $this->request->getBody()['type'] ?? 'inventory_value';
        $reportData = [];

        switch ($type) {
            case 'low_stock':
                $reportData = [
                    'title' => 'Low & Out of Stock Product Report',
                    'products' => $this->reportService->getLowStockReport()
                ];
                break;
            case 'movements':
                $reportData = [
                    'title' => 'Stock Movement History Log Report',
                    'movements' => $this->reportService->getMovementReport($this->request->getBody())
                ];
                break;
            case 'suppliers':
                $reportData = [
                    'title' => 'Supplier Directory & Product Supply Report',
                    'suppliers' => $this->reportService->getSupplierReport()
                ];
                break;
            case 'inventory_value':
            default:
                $type = 'inventory_value';
                $reportData = array_merge([
                    'title' => 'Complete Inventory Valuation & Profitability Report'
                ], $this->reportService->getInventoryValueReport());
                break;
        }

        $this->render('reports/index', [
            'pageTitle' => 'Business Intelligence & Reports',
            'activeNav' => 'reports',
            'currentType' => $type,
            'reportData' => $reportData,
            'filters' => $this->request->getBody()
        ]);
    }

    public function printReport(): void {
        $type = $this->request->getBody()['type'] ?? 'inventory_value';
        $reportData = [];

        switch ($type) {
            case 'low_stock':
                $reportData = [
                    'title' => 'Low & Out of Stock Product Report',
                    'products' => $this->reportService->getLowStockReport()
                ];
                break;
            case 'movements':
                $reportData = [
                    'title' => 'Stock Movement Audit Log Report',
                    'movements' => $this->reportService->getMovementReport($this->request->getBody())
                ];
                break;
            case 'suppliers':
                $reportData = [
                    'title' => 'Supplier Directory & Inventory Summary Report',
                    'suppliers' => $this->reportService->getSupplierReport()
                ];
                break;
            case 'inventory_value':
            default:
                $type = 'inventory_value';
                $reportData = array_merge([
                    'title' => 'Inventory Valuation & Asset Report'
                ], $this->reportService->getInventoryValueReport());
                break;
        }

        $this->renderAuthView('reports/print', [
            'pageTitle' => $reportData['title'],
            'currentType' => $type,
            'reportData' => $reportData
        ]);
    }
}
