<?php
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
$userName = $_SESSION['user']['name'] ?? 'User';
$activeNav = $activeNav ?? 'dashboard';
?>
<!-- Sidebar Navigation -->
<nav id="sidebar" class="bg-slate-900 border-end border-slate-800 text-white flex-shrink-0 p-3 shadow-2xl d-flex flex-column">
    <div class="sidebar-header d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-slate-800">
        <a href="/dashboard" class="text-decoration-none d-flex align-items-center gap-2 text-white">
            <div class="brand-logo bg-gradient-cyan text-slate-950 rounded-3 d-flex align-items-center justify-content-center fw-bold fs-4 shadow-cyan" style="width: 42px; height: 42px;">
                <i class="fas fa-cubes-stacked"></i>
            </div>
            <div>
                <span class="fs-5 fw-bold tracking-tight text-white d-block lh-1">NEXUS <span class="badge bg-cyan text-slate-950 fs-8 align-middle ms-1">PRO</span></span>
                <small class="text-cyan text-uppercase fs-8 tracking-wider fw-bold">Inventory Engine</small>
            </div>
        </a>
        <button class="btn btn-link text-white-50 d-md-none p-0" id="sidebarToggleClose">
            <i class="fas fa-xmark fs-5"></i>
        </button>
    </div>

    <!-- User Profile Badge Card -->
    <a href="/profile" class="text-decoration-none user-card bg-slate-800/80 p-2.5 rounded-3 mb-4 d-flex align-items-center gap-3 border border-slate-700 hover-border-cyan">
        <div class="avatar bg-cyan-glow text-cyan rounded-circle d-flex align-items-center justify-content-center fw-bold fs-6 shadow-xs" style="width: 38px; height: 38px; min-width: 38px;">
            <?= strtoupper(substr($userName, 0, 1)) ?>
        </div>
        <div class="overflow-hidden">
            <h6 class="text-white mb-0 text-truncate fw-semibold fs-7"><?= htmlspecialchars($userName) ?></h6>
            <span class="badge bg-cyan-subtle text-cyan fs-8 px-2 py-0.5 rounded-pill border border-cyan-subtle">
                <i class="fas fa-user-shield me-1"></i><?= htmlspecialchars($userRole) ?>
            </span>
        </div>
    </a>

    <!-- Navigation Menu -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-header text-uppercase text-slate-400 fs-8 fw-bold px-3 py-1 mt-1">Telemetry & Hub</li>
        
        <li class="nav-item">
            <a href="/dashboard" class="nav-link <?= $activeNav === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie me-2.5 text-cyan"></i> Executive Hub
            </a>
        </li>

        <li class="nav-header text-uppercase text-slate-400 fs-8 fw-bold px-3 py-1 mt-3">Catalog & Storage</li>

        <li class="nav-item">
            <a href="/products" class="nav-link <?= $activeNav === 'products' ? 'active' : '' ?>">
                <i class="fas fa-box-archive me-2.5"></i> Products Catalog
            </a>
        </li>

        <li class="nav-item">
            <a href="/categories" class="nav-link <?= $activeNav === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-tags me-2.5"></i> Categories
            </a>
        </li>

        <li class="nav-item">
            <a href="/warehouses" class="nav-link <?= $activeNav === 'warehouses' ? 'active' : '' ?>">
                <i class="fas fa-warehouse me-2.5 text-cyan"></i> Warehouse Locations
            </a>
        </li>

        <li class="nav-item">
            <a href="/suppliers" class="nav-link <?= $activeNav === 'suppliers' ? 'active' : '' ?>">
                <i class="fas fa-truck-field me-2.5"></i> Suppliers Directory
            </a>
        </li>

        <li class="nav-item">
            <a href="/batches" class="nav-link <?= $activeNav === 'batches' ? 'active' : '' ?>">
                <i class="fas fa-boxes-stacked me-2.5 text-amber"></i> Batch & Expiry
            </a>
        </li>

        <li class="nav-item">
            <a href="/assemblies" class="nav-link <?= $activeNav === 'assemblies' ? 'active' : '' ?>">
                <i class="fas fa-microchip me-2.5 text-cyan"></i> Bill of Materials (BOM)
            </a>
        </li>

        <li class="nav-header text-uppercase text-slate-400 fs-8 fw-bold px-3 py-1 mt-3">Orders & Sales</li>

        <li class="nav-item">
            <a href="/sales-orders" class="nav-link <?= $activeNav === 'sales_orders' ? 'active' : '' ?>">
                <i class="fas fa-receipt me-2.5 text-emerald"></i> Customer Sales Orders
            </a>
        </li>

        <li class="nav-item">
            <a href="/customers" class="nav-link <?= $activeNav === 'customers' ? 'active' : '' ?>">
                <i class="fas fa-users me-2.5 text-cyan"></i> Customer CRM Directory
            </a>
        </li>

        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <li class="nav-item">
                <a href="/purchase-orders" class="nav-link <?= $activeNav === 'purchase_orders' ? 'active' : '' ?>">
                    <i class="fas fa-file-invoice me-2.5 text-amber"></i> Purchase Orders (PO)
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="/stock-requests" class="nav-link <?= $activeNav === 'stock_requests' ? 'active' : '' ?>">
                <i class="fas fa-paper-plane me-2.5 text-cyan"></i> Staff Requisitions
            </a>
        </li>

        <li class="nav-header text-uppercase text-slate-400 fs-8 fw-bold px-3 py-1 mt-3">Stock Operations</li>

        <li class="nav-item">
            <a href="/transfers" class="nav-link <?= $activeNav === 'transfers' ? 'active' : '' ?>">
                <i class="fas fa-truck-ramp-box me-2.5 text-cyan"></i> Inter-Warehouse Transfers
            </a>
        </li>

        <li class="nav-item">
            <a href="/stock-takes" class="nav-link <?= $activeNav === 'stock_takes' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-check me-2.5 text-emerald"></i> Stock-Take Audits
            </a>
        </li>

        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <li class="nav-item">
                <a href="/inventory/stock-in" class="nav-link <?= $activeNav === 'stock_in' ? 'active' : '' ?>">
                    <i class="fas fa-circle-arrow-down text-emerald me-2.5"></i> Stock In (Receiving)
                </a>
            </li>

            <li class="nav-item">
                <a href="/inventory/stock-out" class="nav-link <?= $activeNav === 'stock_out' ? 'active' : '' ?>">
                    <i class="fas fa-circle-arrow-up text-rose me-2.5"></i> Stock Out (Dispatch)
                </a>
            </li>

            <li class="nav-item">
                <a href="/inventory/adjust" class="nav-link <?= $activeNav === 'stock_adjust' ? 'active' : '' ?>">
                    <i class="fas fa-sliders me-2.5"></i> Stock Audit Adjust
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="/movements" class="nav-link <?= $activeNav === 'movements' ? 'active' : '' ?>">
                <i class="fas fa-list-check me-2.5"></i> Audit Trail Log
            </a>
        </li>

        <li class="nav-header text-uppercase text-slate-400 fs-8 fw-bold px-3 py-1 mt-3">Analytics & Admin</li>

        <li class="nav-item">
            <a href="/reports" class="nav-link <?= $activeNav === 'reports' ? 'active' : '' ?>">
                <i class="fas fa-file-invoice-dollar me-2.5"></i> Business Intelligence
            </a>
        </li>

        <?php if ($userRole === 'Admin'): ?>
            <li class="nav-item">
                <a href="/users" class="nav-link <?= $activeNav === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users-gear me-2.5"></i> System Users
                </a>
            </li>

            <li class="nav-item">
                <a href="/settings" class="nav-link <?= $activeNav === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-gear me-2.5"></i> System Settings
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-footer border-top border-slate-800 pt-3 mt-auto">
        <a href="/logout" class="btn btn-outline-rose w-100 btn-sm d-flex align-items-center justify-content-center gap-2 rounded-2">
            <i class="fas fa-right-from-bracket"></i> Sign Out
        </a>
    </div>
</nav>
