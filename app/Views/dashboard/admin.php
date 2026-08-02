<div class="container-fluid px-0">

    <!-- Simple Top Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold text-white mb-0">Admin Executive Command Center</h4>
                <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2.5 py-1 fs-8 fw-semibold"><i class="fas fa-signal me-1"></i> Live</span>
            </div>
            <p class="text-slate-400 fs-7 mb-0 mt-1">Real-time inventory telemetry, asset valuations, and executive quick commands.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="/inventory/stock-in" class="btn btn-emerald btn-sm rounded-3 fw-semibold">
                <i class="fas fa-arrow-down-left me-1.5"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-outline-danger btn-sm rounded-3 fw-semibold">
                <i class="fas fa-arrow-up-right me-1.5"></i> Stock Out
            </a>
            <a href="/products/create" class="btn btn-cyan btn-sm rounded-3 fw-semibold">
                <i class="fas fa-plus me-1.5"></i> Add Product
            </a>
            <a href="/purchase-orders/create" class="btn btn-outline-warning btn-sm rounded-3 fw-semibold">
                <i class="fas fa-file-signature me-1.5"></i> Create PO
            </a>
            <a href="/users" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-users-gear me-1.5"></i> Manage Users
            </a>
            <a href="/reports" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-file-invoice-dollar me-1.5"></i> View Reports
            </a>
        </div>
    </div>

    <!-- 4 Executive Telemetry KPI Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Inventory Asset Valuation -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/reports" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-slate-400 fs-7 fw-semibold">Retail Valuation</span>
                        <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-dollar-sign fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-emerald mb-2">$<?= number_format($metrics['retail_valuation'] ?? 0, 2) ?></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-emerald-subtle text-emerald rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-arrow-trend-up me-1"></i>+14.2% vs last mo
                        </span>
                        <span class="text-slate-400 fs-8">Profit: +$<?= number_format($metrics['potential_profit'] ?? 0, 2) ?></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. Total Catalog Products & Health Rating -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/products" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-slate-400 fs-7 fw-semibold">Catalog Products</span>
                        <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-boxes-stacked fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-white mb-2"><?= number_format($metrics['total_products'] ?? 0) ?> <span class="fs-7 text-slate-400 fw-normal">SKUs</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-cyan-subtle text-cyan rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-heart-pulse me-1"></i>Health: <?= $metrics['health_percentage'] ?? 100 ?>%
                        </span>
                        <span class="text-slate-400 fs-8"><?= $metrics['total_categories'] ?? 0 ?> Categories</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. Low Stock & Out-of-Stock Risk Alerts -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/reports" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-slate-400 fs-7 fw-semibold">Stock Reorder Risk</span>
                        <div class="metric-icon bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-triangle-exclamation fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-warning mb-2"><?= number_format(($metrics['low_stock_count'] ?? 0) + ($metrics['out_of_stock_count'] ?? 0)) ?> <span class="fs-7 text-slate-400 fw-normal">Items</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-warning-subtle text-warning rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-bell me-1"></i>Action Required
                        </span>
                        <span class="text-slate-400 fs-8"><?= $metrics['low_stock_count'] ?? 0 ?> Low | <?= $metrics['out_of_stock_count'] ?? 0 ?> Out</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 4. System Users & Active Roles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/users" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative overflow-hidden hover-shadow transition-all">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-slate-400 fs-7 fw-semibold">System Users</span>
                        <div class="metric-icon bg-primary-subtle text-blue rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-users-gear fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-white mb-2"><?= number_format($metrics['total_users'] ?? 0) ?> <span class="fs-7 text-slate-400 fw-normal">Operators</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-primary-subtle text-blue rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-shield-halved me-1"></i>Active RBAC
                        </span>
                        <span class="text-slate-400 fs-8">Admin / Mgr / Staff</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Analytics Row 1: Monthly Movements & Category Donut Chart -->
    <div class="row g-3 mb-4">
        <!-- Monthly Stock Activity Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-white mb-0"><i class="fas fa-chart-bar me-2 text-cyan"></i> Monthly Stock Activity Telemetry</h6>
                        <small class="text-slate-400 fs-8">Inflow vs Outflow stock volume history</small>
                    </div>
                    <span class="badge bg-slate-800 text-slate-300 fs-8">Monthly</span>
                </div>
                <div style="height: 280px;">
                    <canvas id="monthlyMovementsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Stock Distribution Donut Chart -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-white mb-0"><i class="fas fa-chart-pie me-2 text-emerald"></i> Category Distribution</h6>
                        <small class="text-slate-400 fs-8">Inventory units grouped by category</small>
                    </div>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Governance & Telemetry Row: Pending Approvals & Live Audit Stream -->
    <div class="row g-3">
        <!-- Pending Requisitions & Approvals Queue -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-white mb-0"><i class="fas fa-clipboard-check me-2 text-warning"></i> Pending Staff Requisitions</h6>
                        <small class="text-slate-400 fs-8">Stock requests waiting for Admin approval</small>
                    </div>
                    <a href="/stock-requests" class="text-cyan fs-8 text-decoration-none fw-semibold">View All (<?= count($pendingRequests ?? []) ?>) &rarr;</a>
                </div>

                <?php if (empty($pendingRequests)): ?>
                    <div class="text-center py-4 my-auto text-slate-400">
                        <i class="fas fa-check-circle fs-3 text-emerald mb-2"></i>
                        <p class="mb-0 fs-7">No pending requisitions. All caught up!</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach (array_slice($pendingRequests, 0, 4) as $req): ?>
                            <div class="p-3 bg-slate-800 rounded-3 border border-slate-700/50 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <div class="fw-semibold text-white fs-7"><?= htmlspecialchars($req->product_name) ?></div>
                                    <div class="text-slate-400 fs-8">
                                        Requested by <span class="text-slate-300"><?= htmlspecialchars($req->user_name) ?></span> &bull; Qty: <strong class="text-cyan"><?= $req->quantity ?></strong>
                                    </div>
                                    <?php if (!empty($req->reason)): ?>
                                        <div class="text-slate-400 fs-8 fst-italic mt-0.5">"<?= htmlspecialchars($req->reason) ?>"</div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-1.5 align-items-center">
                                    <form action="/stock-requests/approve" method="POST" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?= $req->request_id ?>">
                                        <button type="submit" class="btn btn-emerald btn-xs px-2.5 py-1 rounded-2" title="Approve Request">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="/stock-requests/reject" method="POST" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?= $req->request_id ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1 rounded-2" title="Reject Request">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Live System Audit Trail Telemetry Stream -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-white mb-0"><i class="fas fa-shield-halved me-2 text-cyan"></i> System Audit Stream Telemetry</h6>
                        <small class="text-slate-400 fs-8">Immutable stock movement log & operator activity</small>
                    </div>
                    <a href="/movements" class="text-cyan fs-8 text-decoration-none fw-semibold">Audit History &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7">
                        <thead>
                            <tr class="text-slate-400">
                                <th>Item</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Operator</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMovements as $mv): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-white"><?= htmlspecialchars($mv->product_name) ?></div>
                                        <small class="text-slate-400 fw-mono fs-8"><?= htmlspecialchars($mv->sku ?? '') ?></small>
                                    </td>
                                    <td><?= $mv->getTypeBadgeHtml() ?></td>
                                    <td class="fw-bold text-white"><?= $mv->quantity ?></td>
                                    <td class="text-slate-300 fs-8">
                                        <i class="fas fa-user-circle text-slate-500 me-1"></i><?= htmlspecialchars($mv->user_name ?? 'System') ?>
                                    </td>
                                    <td class="text-slate-400 fs-8"><?= date('M d, H:i', strtotime($mv->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
    window.dashboardChartsData = <?= json_encode($chartsData) ?>;
</script>
