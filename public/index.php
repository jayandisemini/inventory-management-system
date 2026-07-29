<?php

// Smart Inventory Management System (SIMS Pro) Front Controller

require_once dirname(__DIR__) . '/config/config.php';

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\CategoryController;
use App\Controllers\SupplierController;
use App\Controllers\InventoryController;
use App\Controllers\MovementController;
use App\Controllers\ReportController;
use App\Controllers\UserController;
use App\Controllers\NotificationController;
use App\Controllers\PurchaseOrderController;
use App\Controllers\StockRequestController;
use App\Controllers\SettingController;
use App\Controllers\ProfileController;
use App\Controllers\WarehouseController;
use App\Controllers\SalesOrderController;
use App\Controllers\BatchController;
use App\Controllers\TransferController;
use App\Controllers\StockTakeController;
use App\Controllers\AssemblyController;
use App\Controllers\CustomerController;

$request = new Request();
$response = new Response();
$router = new Router();

// -------------------------------------------------------------
// Application Routes
// -------------------------------------------------------------

// Authentication Routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/reset-password', [AuthController::class, 'showResetPassword']);
$router->post('/reset-password', [AuthController::class, 'resetPassword']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// User Profile & Password Security
$router->get('/profile', [ProfileController::class, 'index']);
$router->post('/profile/update', [ProfileController::class, 'update']);
$router->post('/profile/password', [ProfileController::class, 'changePassword']);

// System Settings (Admin only)
$router->get('/settings', [SettingController::class, 'index']);
$router->post('/settings/update', [SettingController::class, 'update']);

// Product Management
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/store', [ProductController::class, 'store']);
$router->get('/products/edit', [ProductController::class, 'edit']);
$router->post('/products/update', [ProductController::class, 'update']);
$router->get('/products/show', [ProductController::class, 'show']);
$router->post('/products/delete', [ProductController::class, 'delete']);
$router->get('/products/barcode', [ProductController::class, 'barcodeLabels']);

// Category Management
$router->get('/categories', [CategoryController::class, 'index']);
$router->post('/categories/store', [CategoryController::class, 'store']);
$router->post('/categories/update', [CategoryController::class, 'update']);
$router->post('/categories/delete', [CategoryController::class, 'delete']);

// Supplier Management
$router->get('/suppliers', [SupplierController::class, 'index']);
$router->post('/suppliers/store', [SupplierController::class, 'store']);
$router->post('/suppliers/update', [SupplierController::class, 'update']);
$router->get('/suppliers/show', [SupplierController::class, 'show']);
$router->post('/suppliers/delete', [SupplierController::class, 'delete']);

// Multi-Warehouse Management
$router->get('/warehouses', [WarehouseController::class, 'index']);
$router->post('/warehouses/store', [WarehouseController::class, 'store']);
$router->post('/warehouses/update', [WarehouseController::class, 'update']);
$router->post('/warehouses/delete', [WarehouseController::class, 'delete']);

// Purchase Orders (PO) Procurement
$router->get('/purchase-orders', [PurchaseOrderController::class, 'index']);
$router->get('/purchase-orders/create', [PurchaseOrderController::class, 'create']);
$router->post('/purchase-orders/store', [PurchaseOrderController::class, 'store']);
$router->get('/purchase-orders/show', [PurchaseOrderController::class, 'show']);
$router->get('/purchase-orders/print', [PurchaseOrderController::class, 'printPO']);
$router->post('/purchase-orders/receive', [PurchaseOrderController::class, 'markReceived']);

// Sales Orders & Receipts
$router->get('/sales-orders', [SalesOrderController::class, 'index']);
$router->get('/sales-orders/create', [SalesOrderController::class, 'create']);
$router->post('/sales-orders/store', [SalesOrderController::class, 'store']);
$router->get('/sales-orders/show', [SalesOrderController::class, 'show']);
$router->get('/sales-orders/print', [SalesOrderController::class, 'printReceipt']);

// Batch & Expiry Tracking Routes
$router->get('/batches', [BatchController::class, 'index']);
$router->post('/batches/store', [BatchController::class, 'store']);

// Inter-Warehouse Transfers
$router->get('/transfers', [TransferController::class, 'index']);
$router->post('/transfers/store', [TransferController::class, 'store']);

// Stock-Take Audits
$router->get('/stock-takes', [StockTakeController::class, 'index']);
$router->post('/stock-takes/store', [StockTakeController::class, 'store']);

// Bill of Materials (BOM) & Assemblies
$router->get('/assemblies', [AssemblyController::class, 'index']);
$router->post('/assemblies/store', [AssemblyController::class, 'store']);

// Customer CRM Directory
$router->get('/customers', [CustomerController::class, 'index']);
$router->post('/customers/store', [CustomerController::class, 'store']);

// Staff Stock Requisitions & Approvals
$router->get('/stock-requests', [StockRequestController::class, 'index']);
$router->post('/stock-requests/store', [StockRequestController::class, 'store']);
$router->post('/stock-requests/approve', [StockRequestController::class, 'approve']);
$router->post('/stock-requests/reject', [StockRequestController::class, 'reject']);

// Inventory Stock Control
$router->get('/inventory/stock-in', [InventoryController::class, 'showStockIn']);
$router->post('/inventory/stock-in', [InventoryController::class, 'processStockIn']);
$router->get('/inventory/stock-out', [InventoryController::class, 'showStockOut']);
$router->post('/inventory/stock-out', [InventoryController::class, 'processStockOut']);
$router->get('/inventory/adjust', [InventoryController::class, 'showAdjust']);
$router->post('/inventory/adjust', [InventoryController::class, 'processAdjust']);

// Movements & Audit Trail History
$router->get('/movements', [MovementController::class, 'index']);

// Reporting Module & CSV Exporters
$router->get('/reports', [ReportController::class, 'index']);
$router->get('/reports/print', [ReportController::class, 'printReport']);
$router->get('/reports/export-inventory-csv', [ReportController::class, 'exportInventoryCsv']);
$router->get('/reports/export-movements-csv', [ReportController::class, 'exportMovementsCsv']);
$router->get('/reports/export-sales-csv', [ReportController::class, 'exportSalesCsv']);

// System User Management (Admin only)
$router->get('/users', [UserController::class, 'index']);
$router->post('/users/store', [UserController::class, 'store']);
$router->post('/users/update', [UserController::class, 'update']);
$router->post('/users/delete', [UserController::class, 'delete']);

// System Notifications API
$router->get('/notifications/unread', [NotificationController::class, 'getUnread']);
$router->post('/notifications/mark-read', [NotificationController::class, 'markRead']);

// Resolve Request
$router->resolve($request, $response);
