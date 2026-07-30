<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\MovementRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SupplierRepository;
use App\Services\InventoryService;

class ApiController extends Controller {
    private ProductRepository $productRepo;
    private MovementRepository $movementRepo;
    private CategoryRepository $categoryRepo;
    private SupplierRepository $supplierRepo;
    private InventoryService $inventoryService;

    public function __construct() {
        parent::__construct();
        $this->productRepo = new ProductRepository();
        $this->movementRepo = new MovementRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->supplierRepo = new SupplierRepository();
        $this->inventoryService = new InventoryService();
    }

    private function verifyAuth(): void {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $apiKey = $headers['X-API-KEY'] ?? $headers['x-api-key'] ?? $_GET['api_key'] ?? $_POST['api_key'] ?? null;
        $expectedKey = defined('SYS_API_KEY') ? SYS_API_KEY : 'nexus_sims_api_secret_key_2026';

        if (!$apiKey || $apiKey !== $expectedKey) {
            $this->response->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid or missing API key (X-API-KEY header required).'
            ], 401);
        }
    }

    public function stockSummary(): void {
        $this->verifyAuth();

        $products = $this->productRepo->getAll();
        $totalProducts = count($products);
        $totalValuation = 0.00;
        $lowStockCount = 0;
        $outOfStockCount = 0;

        foreach ($products as $p) {
            $totalValuation += ($p->unit_price * $p->quantity);
            if ($p->quantity <= 0) {
                $outOfStockCount++;
            } elseif ($p->quantity <= ($p->min_stock_level ?? 5)) {
                $lowStockCount++;
            }
        }

        $categoriesCount = count($this->categoryRepo->getAll());
        $suppliersCount = count($this->supplierRepo->getAll());

        $this->response->json([
            'status' => 'success',
            'data' => [
                'total_products' => $totalProducts,
                'total_inventory_valuation' => round($totalValuation, 2),
                'low_stock_alerts' => $lowStockCount,
                'out_of_stock_critical' => $outOfStockCount,
                'total_categories' => $categoriesCount,
                'total_suppliers' => $suppliersCount,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    public function products(): void {
        $this->verifyAuth();

        $search = $_GET['search'] ?? null;
        if ($search) {
            $products = $this->productRepo->search($search);
        } else {
            $products = $this->productRepo->getAll();
        }

        $data = array_map(function($p) {
            return [
                'product_id' => (int)$p->product_id,
                'sku' => $p->sku,
                'name' => $p->product_name,
                'category' => $p->category_name ?? 'N/A',
                'supplier' => $p->supplier_name ?? 'N/A',
                'cost_price' => (float)$p->unit_price,
                'selling_price' => (float)$p->selling_price,
                'stock_quantity' => (int)$p->quantity,
                'min_stock_level' => (int)($p->min_stock_level ?? 5),
                'status' => $p->quantity <= 0 ? 'OUT_OF_STOCK' : ($p->quantity <= ($p->min_stock_level ?? 5) ? 'LOW_STOCK' : 'IN_STOCK')
            ];
        }, $products);

        $this->response->json([
            'status' => 'success',
            'count' => count($data),
            'data' => $data
        ]);
    }

    public function showProduct(): void {
        $this->verifyAuth();

        $id = (int)($_GET['id'] ?? 0);
        $sku = $_GET['sku'] ?? null;

        $product = null;
        if ($id > 0) {
            $product = $this->productRepo->findById($id);
        } elseif ($sku) {
            $product = $this->productRepo->findBySKU($sku);
        }

        if (!$product) {
            $this->response->json([
                'status' => 'error',
                'message' => 'Product not found.'
            ], 404);
        }

        $this->response->json([
            'status' => 'success',
            'data' => [
                'product_id' => (int)$product->product_id,
                'sku' => $product->sku,
                'name' => $product->product_name,
                'description' => $product->description ?? '',
                'category_id' => $product->category_id,
                'category_name' => $product->category_name ?? 'N/A',
                'supplier_id' => $product->supplier_id,
                'supplier_name' => $product->supplier_name ?? 'N/A',
                'cost_price' => (float)$product->unit_price,
                'selling_price' => (float)$product->selling_price,
                'stock_quantity' => (int)$product->quantity,
                'min_stock_level' => (int)($product->min_stock_level ?? 5),
                'location' => $product->location ?? 'Main Warehouse'
            ]
        ]);
    }

    public function stockAdjust(): void {
        $this->verifyAuth();

        $rawInput = file_get_contents('php://input');
        $json = json_decode($rawInput, true) ?? $_POST;

        $productId = (int)($json['product_id'] ?? 0);
        $newQuantity = (int)($json['new_quantity'] ?? -1);
        $note = $json['note'] ?? 'API Stock Adjustment';

        if ($productId <= 0 || $newQuantity < 0) {
            $this->response->json([
                'status' => 'error',
                'message' => 'Invalid parameters. Requires positive product_id and non-negative new_quantity.'
            ], 400);
        }

        $success = $this->inventoryService->adjustStock($productId, $newQuantity, 1, $note);

        if ($success) {
            $product = $this->productRepo->findById($productId);
            $this->response->json([
                'status' => 'success',
                'message' => 'Stock adjusted successfully via REST API.',
                'data' => [
                    'product_id' => $productId,
                    'new_quantity' => $product ? (int)$product->quantity : $newQuantity
                ]
            ]);
        } else {
            $this->response->json([
                'status' => 'error',
                'message' => 'Failed to adjust stock.'
            ], 500);
        }
    }

    public function movements(): void {
        $this->verifyAuth();

        $movements = $this->movementRepo->getAll($_GET);
        $data = array_map(function($m) {
            return [
                'movement_id' => (int)$m->movement_id,
                'sku' => $m->sku,
                'product_name' => $m->product_name,
                'type' => $m->movement_type,
                'quantity' => (int)$m->quantity,
                'operator' => $m->user_name,
                'reference_note' => $m->reference_note ?? '',
                'created_at' => $m->created_at
            ];
        }, $movements);

        $this->response->json([
            'status' => 'success',
            'count' => count($data),
            'data' => $data
        ]);
    }
}
