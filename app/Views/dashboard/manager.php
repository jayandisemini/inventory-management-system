<?php
use App\Core\CSRF;

$totalProds = max(1, $metrics['total_products'] ?? 1);
$healthyCount = $metrics['healthy_count'] ?? 0;
$lowCount = $metrics['low_stock_count'] ?? 0;
$outCount = $metrics['out_of_stock_count'] ?? 0;

$healthyPct = round(($healthyCount / $totalProds) * 100);
$lowPct = round(($lowCount / $totalProds) * 100);
$outPct = min(100 - ($healthyPct + $lowPct), round(($outCount / $totalProds) * 100));
?>
<div class="container-fluid px-0">

    <!-- Manager Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 role-banner-manager p-4 rounded-4 shadow-sm text-white">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0"><i class="fas fa-boxes-stacked me-2 text-cyan"></i> Manager Operations Hub & Restock Engine</h4>
                <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2.5 py-1 fs-8 fw-bold border border-emerald">
                    <i class="fas fa-truck-ramp-box me-1"></i> WAREHOUSE RESTOCK OPERATIONAL MODE
                </span>
            </div>
            <p class="text-slate-400 fs-7 mb-0">Monitor catalog health, calculate automated reorder quantities, and manage purchase orders.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="/inventory/stock-in" class="btn btn-emerald btn-sm rounded-3">
                <i class="fas fa-plus me-1.5"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-rose btn-sm rounded-3">
                <i class="fas fa-minus me-1.5"></i> Stock Out
            </a>
            <a href="/purchase-orders/create" class="btn btn-cyan btn-sm rounded-3">
                <i class="fas fa-file-signature me-1.5"></i> Create PO
            </a>
            <a href="/transfers" class="btn btn-theme-outline btn-sm rounded-3">
                <i class="fas fa-right-left me-1.5"></i> Inter-Warehouse Transfer
            </a>
            <a href="/stock-takes" class="btn btn-theme-outline btn-sm rounded-3">
                <i class="fas fa-clipboard-list me-1.5"></i> Stock Audit
            </a>
        </div>
    </div>

    <!-- 5 Key Operational Metric Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4">
        <!-- 1. Catalog Products -->
        <div class="col">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="text-theme-muted fs-8 fw-bold text-uppercase mb-1">Total Catalog</div>
                <h3 class="fw-bold text-theme-main mb-1"><?= number_format($metrics['total_products'] ?? 0) ?></h3>
                <small class="text-cyan fs-8"><i class="fas fa-layer-group me-1"></i><?= $metrics['total_categories'] ?? 0 ?> Categories</small>
            </div>
        </div>

        <!-- 2. Health Meter -->
        <div class="col">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="text-theme-muted fs-8 fw-bold text-uppercase mb-1">Catalog Health</div>
                <h3 class="fw-bold text-emerald mb-1"><?= $metrics['health_percentage'] ?? 100 ?>%</h3>
                <small class="text-emerald fs-8"><i class="fas fa-check-circle me-1"></i><?= $healthyCount ?> Healthy Items</small>
            </div>
        </div>

        <!-- 3. Low Stock Alerts -->
        <div class="col">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="text-theme-muted fs-8 fw-bold text-uppercase mb-1">Low Stock Alerts</div>
                <h3 class="fw-bold text-amber mb-1"><?= number_format($metrics['low_stock_count'] ?? 0) ?></h3>
                <small class="text-amber fs-8"><i class="fas fa-triangle-exclamation me-1"></i>Reorder Recommended</small>
            </div>
        </div>

        <!-- 4. Out of Stock -->
        <div class="col">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="text-theme-muted fs-8 fw-bold text-uppercase mb-1">Out of Stock</div>
                <h3 class="fw-bold text-rose mb-1"><?= number_format($metrics['out_of_stock_count'] ?? 0) ?></h3>
                <small class="text-rose fs-8"><i class="fas fa-ban me-1"></i>Zero Inventory</small>
            </div>
        </div>

        <!-- 5. Reorder Commitment Cost -->
        <div class="col">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="text-theme-muted fs-8 fw-bold text-uppercase mb-1">Est. Reorder Cost</div>
                <h3 class="fw-bold text-cyan mb-1">Rs. <?= number_format($metrics['total_reorder_cost'] ?? 0, 2) ?></h3>
                <small class="text-cyan fs-8"><i class="fas fa-cart-shopping me-1"></i>Suggested PO Budget</small>
            </div>
        </div>
    </div>

    <!-- Health Visual Gauge Bar -->
    <div class="card border-0 rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-heart-pulse me-2 text-emerald"></i> Warehouse Stock Health Ratio</h6>
            <span class="fs-8 text-theme-muted"><?= $healthyPct ?>% Healthy | <?= $lowPct ?>% Low | <?= $outPct ?>% Out</span>
        </div>
        <div class="progress rounded-pill bg-slate-800" style="height: 12px;">
            <div class="progress-bar bg-emerald" role="progressbar" style="width: <?= $healthyPct ?>%" title="Healthy Stock"></div>
            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $lowPct ?>%" title="Low Stock"></div>
            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $outPct ?>%" title="Out of Stock"></div>
        </div>
    </div>

    <!-- Smart Restock Queue & Procurement Stream -->
    <div class="row g-3 mb-4">
        <!-- Smart Restock Queue Table -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-triangle-exclamation text-amber me-2"></i> Inventory Restock Queue</h6>
                        <small class="text-theme-muted fs-8">Items below min stock with automated reorder calculations</small>
                    </div>
                    <a href="/purchase-orders/create" class="btn btn-cyan btn-xs rounded-2 px-2.5 py-1">
                        <i class="fas fa-plus me-1"></i> Create PO
                    </a>
                </div>

                <?php if (empty($restockQueue)): ?>
                    <div class="text-center py-4 my-auto text-theme-muted">
                        <i class="fas fa-circle-check fs-3 text-emerald mb-2"></i>
                        <p class="mb-0 fs-7">All warehouse stock levels are healthy! No restock required.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 fs-7">
                            <thead>
                                <tr>
                                    <th>SKU / Product</th>
                                    <th>Current</th>
                                    <th>Min Stock</th>
                                    <th>Suggested Reorder</th>
                                    <th>Est Cost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($restockQueue, 0, 6) as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-theme-main"><?= htmlspecialchars($item->product_name) ?></div>
                                            <small class="text-theme-muted fw-mono fs-8"><?= htmlspecialchars($item->sku) ?></small>
                                        </td>
                                        <td class="fw-bold <?= $item->quantity == 0 ? 'text-rose' : 'text-amber' ?>"><?= $item->quantity ?></td>
                                        <td><?= $item->min_stock_level ?></td>
                                        <td><span class="badge bg-cyan-subtle text-cyan fw-bold px-2 py-1">+<?= $item->suggested_reorder_qty ?> units</span></td>
                                        <td class="fw-semibold text-theme-main">Rs. <?= number_format($item->suggested_reorder_qty * (float)($item->cost_price ?? 0), 2) ?></td>
                                        <td>
                                            <a href="/inventory/stock-in?product_id=<?= $item->product_id ?>" class="btn btn-emerald btn-xs rounded-2 px-2 py-1">
                                                <i class="fas fa-plus me-1"></i> Stock In
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pending Purchase Orders Tracker -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-file-contract me-2 text-cyan"></i> Active PO Orders</h6>
                        <small class="text-theme-muted fs-8">Supplier purchase orders in transit</small>
                    </div>
                    <a href="/purchase-orders" class="text-cyan fs-8 text-decoration-none fw-semibold">View All &rarr;</a>
                </div>

                <?php if (empty($pendingPOs)): ?>
                    <div class="text-center py-4 my-auto text-theme-muted">
                        <i class="fas fa-inbox fs-3 text-theme-muted mb-2"></i>
                        <p class="mb-0 fs-7">No active purchase orders in transit.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach (array_slice($pendingPOs, 0, 4) as $po): ?>
                            <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-theme-main fs-7"><?= htmlspecialchars($po->po_number ?? "PO-#{$po->po_id}") ?></div>
                                    <small class="text-theme-muted fs-8"><i class="fas fa-truck me-1"></i><?= htmlspecialchars($po->supplier_name ?? 'Vendor') ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-cyan fs-7">Rs. <?= number_format($po->total_amount ?? 0, 2) ?></div>
                                    <span class="badge bg-warning-subtle text-amber fs-8"><?= htmlspecialchars($po->status ?? 'Sent') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Movement Stream -->
    <div class="card border-0 rounded-4 p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-clock-rotate-left me-2 text-cyan"></i> Warehouse Movement Log Stream</h6>
            <a href="/movements" class="text-cyan fs-8 text-decoration-none fw-semibold">Full Audit Logs &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Operator</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($recentMovements, 0, 5) as $mv): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold text-theme-main"><?= htmlspecialchars($mv->product_name) ?></div>
                                <small class="text-theme-muted fw-mono fs-8"><?= htmlspecialchars($mv->sku) ?></small>
                            </td>
                            <td><?= $mv->getTypeBadgeHtml() ?></td>
                            <td class="fw-bold text-theme-main"><?= $mv->quantity ?></td>
                            <td><?= htmlspecialchars($mv->user_name) ?></td>
                            <td class="text-theme-muted fs-8"><?= date('M d, H:i', strtotime($mv->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    window.dashboardChartsData = <?= json_encode($chartsData) ?>;
</script>
