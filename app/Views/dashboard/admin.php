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

    <!-- 4 Clean Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Inventory Value -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Total Inventory Value</span>
                    <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-dollar-sign fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-emerald mb-0">$<?= number_format($metrics['retail_valuation'] ?? 0, 2) ?></h2>
            </div>
        </div>

        <!-- 2. Total Products -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Total Products</span>
                    <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-box fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-white mb-0"><?= number_format($metrics['total_products'] ?? 0) ?></h2>
            </div>
        </div>

        <!-- 3. Low Stock Alerts -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Low Stock Alerts</span>
                    <div class="metric-icon bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-warning mb-0"><?= number_format($metrics['low_stock_count'] + $metrics['out_of_stock_count']) ?></h2>
            </div>
        </div>

        <!-- 4. System Users -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">System Users</span>
                    <div class="metric-icon bg-primary-subtle text-blue rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-white mb-0"><?= number_format($metrics['total_users'] ?? 0) ?></h2>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Chart + Activity Table -->
    <div class="row g-3">
        <!-- Monthly Stock Activity Chart -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-white mb-0"><i class="fas fa-chart-bar me-2 text-cyan"></i> Monthly Stock Activity</h6>
                </div>
                <div style="height: 300px;">
                    <canvas id="monthlyMovementsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-white mb-0"><i class="fas fa-history me-2 text-cyan"></i> Recent Activity</h6>
                    <a href="/movements" class="text-cyan fs-8 text-decoration-none fw-semibold">View All &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7">
                        <thead>
                            <tr class="text-slate-400">
                                <th>Product</th>
                                <th>Action</th>
                                <th>Qty</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMovements as $mv): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-white"><?= htmlspecialchars($mv->product_name) ?></div>
                                    </td>
                                    <td><?= $mv->getTypeBadgeHtml() ?></td>
                                    <td class="fw-bold text-white"><?= $mv->quantity ?></td>
                                    <td class="text-slate-400 fs-8"><?= date('M d, H:i', strtotime($mv->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.dashboardChartsData = <?= json_encode($chartsData) ?>;
</script>
