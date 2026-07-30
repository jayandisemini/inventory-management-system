<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\AuthMiddleware;
use App\Services\ReportService;
use App\Repositories\ProductRepository;
use App\Repositories\MovementRepository;
use App\Repositories\SORepository;
use App\Repositories\BatchRepository;
use App\Repositories\PORepository;

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
            case 'sales_revenue':
                $reportData = array_merge([
                    'title' => 'Sales Performance & Revenue Analytics Report'
                ], $this->reportService->getSalesRevenueReport());
                break;
            case 'batch_expiry':
                $reportData = array_merge([
                    'title' => 'Batch Expiry & Inventory Waste Risk Report'
                ], $this->reportService->getBatchExpiryReport());
                break;
            case 'supplier_procurement':
                $reportData = array_merge([
                    'title' => 'Supplier Procurement & Purchase Order Analytics'
                ], $this->reportService->getSupplierProcurementReport());
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
            case 'sales_revenue':
                $reportData = array_merge([
                    'title' => 'Sales Performance & Revenue Analytics Report'
                ], $this->reportService->getSalesRevenueReport());
                break;
            case 'batch_expiry':
                $reportData = array_merge([
                    'title' => 'Batch Expiry & Inventory Waste Risk Report'
                ], $this->reportService->getBatchExpiryReport());
                break;
            case 'supplier_procurement':
                $reportData = array_merge([
                    'title' => 'Supplier Procurement & Purchase Order Analytics'
                ], $this->reportService->getSupplierProcurementReport());
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

    public function exportInventoryCsv(): void {
        $productRepo = new ProductRepository();
        $products = $productRepo->getAll();

        $filename = "inventory_valuation_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product ID', 'SKU', 'Product Name', 'Category', 'Supplier', 'Cost Price ($)', 'Selling Price ($)', 'Stock Qty', 'Total Cost Value ($)', 'Total Retail Value ($)']);

        foreach ($products as $p) {
            $costVal = $p->unit_price * $p->quantity;
            $retailVal = $p->selling_price * $p->quantity;
            fputcsv($output, [
                $p->product_id,
                $p->sku,
                $p->product_name,
                $p->category_name ?? 'N/A',
                $p->supplier_name ?? 'N/A',
                number_format($p->unit_price, 2, '.', ''),
                number_format($p->selling_price, 2, '.', ''),
                $p->quantity,
                number_format($costVal, 2, '.', ''),
                number_format($retailVal, 2, '.', '')
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportMovementsCsv(): void {
        $movementRepo = new MovementRepository();
        $movements = $movementRepo->getAll($this->request->getBody());

        $filename = "stock_movements_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Log ID', 'Product SKU', 'Product Name', 'Movement Type', 'Quantity Transacted', 'Operator Name', 'Timestamp', 'Reference Note']);

        foreach ($movements as $m) {
            fputcsv($output, [
                '#LOG-' . str_pad($m->movement_id, 5, '0', STR_PAD_LEFT),
                $m->sku,
                $m->product_name,
                $m->movement_type,
                $m->quantity,
                $m->user_name,
                $m->created_at,
                $m->reference_note ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportSalesCsv(): void {
        $soRepo = new SORepository();
        $orders = $soRepo->getAll();

        $filename = "sales_orders_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['SO ID', 'Invoice Number', 'Customer Name', 'Customer Email', 'Total Billed ($)', 'Payment Status', 'Issued By', 'Created Timestamp']);

        foreach ($orders as $so) {
            fputcsv($output, [
                $so->so_id,
                $so->order_number,
                $so->customer_name,
                $so->customer_email ?? 'N/A',
                number_format($so->total_amount, 2, '.', ''),
                $so->payment_status,
                $so->user_name,
                $so->created_at
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportBatchExpiryCsv(): void {
        $batchRepo = new BatchRepository();
        $batches = $batchRepo->getAll();

        $filename = "batch_expiry_risk_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Batch ID', 'Product SKU', 'Product Name', 'Batch Number', 'Quantity', 'MFD Date', 'Expiry Date', 'Status']);

        foreach ($batches as $b) {
            fputcsv($output, [
                $b->batch_id,
                $b->sku,
                $b->product_name,
                $b->batch_number,
                $b->quantity,
                $b->mfd_date ?? 'N/A',
                $b->expiry_date,
                $b->status
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportProcurementCsv(): void {
        $poRepo = new PORepository();
        $pos = $poRepo->getAll();

        $filename = "procurement_pos_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['PO ID', 'PO Number', 'Supplier Name', 'Total Spend ($)', 'Status', 'Issued By', 'Created Timestamp']);

        foreach ($pos as $po) {
            fputcsv($output, [
                $po->po_id,
                $po->po_number,
                $po->supplier_name,
                number_format($po->total_amount, 2, '.', ''),
                $po->status,
                $po->user_name,
                $po->created_at
            ]);
        }

        fclose($output);
        exit;
    }
}
