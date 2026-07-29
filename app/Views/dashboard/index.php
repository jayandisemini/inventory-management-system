<div class="container-fluid px-0">

    <!-- Header Greeting Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">Executive Inventory Overview</h4>
            <p class="text-muted fs-7 mb-0">Real-time telemetry, stock distribution metrics, and audit logs.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="/inventory/stock-in" class="btn btn-success btn-sm rounded-3 shadow-xs">
                <i class="fas fa-plus-circle me-1.5"></i> Process Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-danger btn-sm rounded-3 shadow-xs">
                <i class="fas fa-minus-circle me-1.5"></i> Process Stock Out
            </a>
            <a href="/reports" class="btn btn-outline-primary btn-sm rounded-3 shadow-xs">
                <i class="fas fa-file-invoice-dollar me-1.5"></i> View Reports
            </a>
        </div>
    </div>

    <!-- Executive Metric KPI Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Products -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 shadow-xs rounded-4 bg-white h-100 p-3 overflow-hidden position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-7 text-uppercase fw-bold tracking-wider d-block mb-1">Total Products</span>
                        <h3 class="fw-bold text-slate-900 mb-0"><?= number_format($metrics['total_products'] ?? 0) ?></h3>
                    </div>
                    <div class="metric-icon bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-box-archive fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top fs-8 text-muted d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-folder text-primary me-1"></i> <?= $metrics['total_categories'] ?? 0 ?> Categories</span>
                    <a href="/products" class="text-primary text-decoration-none fw-semibold">View All &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Valuation -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 shadow-xs rounded-4 bg-white h-100 p-3 overflow-hidden position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-7 text-uppercase fw-bold tracking-wider d-block mb-1">Inventory Valuation</span>
                        <h3 class="fw-bold text-success mb-0">$<?= number_format($metrics['total_valuation'] ?? 0, 2) ?></h3>
                    </div>
                    <div class="metric-icon bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sack-dollar fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top fs-8 text-muted d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-truck text-success me-1"></i> <?= $metrics['total_suppliers'] ?? 0 ?> Suppliers</span>
                    <a href="/reports?type=inventory_value" class="text-success text-decoration-none fw-semibold">Breakdown &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Low Stock Warning -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 shadow-xs rounded-4 bg-white h-100 p-3 overflow-hidden position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-7 text-uppercase fw-bold tracking-wider d-block mb-1">Low Stock Items</span>
                        <h3 class="fw-bold text-warning mb-0"><?= number_format($metrics['low_stock_count'] ?? 0) ?></h3>
                    </div>
                    <div class="metric-icon bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-triangle-exclamation fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top fs-8 text-muted d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-bell text-warning me-1"></i> Reorder Recommended</span>
                    <a href="/reports?type=low_stock" class="text-warning text-decoration-none fw-semibold">Inspect &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 4: Out of Stock -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 shadow-xs rounded-4 bg-white h-100 p-3 overflow-hidden position-relative">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-7 text-uppercase fw-bold tracking-wider d-block mb-1">Out of Stock</span>
                        <h3 class="fw-bold text-danger mb-0"><?= number_format($metrics['out_of_stock_count'] ?? 0) ?></h3>
                    </div>
                    <div class="metric-icon bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-ban fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top fs-8 text-muted d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-circle-xmark text-danger me-1"></i> Critical Action</span>
                    <a href="/inventory/stock-in" class="text-danger text-decoration-none fw-semibold">Restock &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section Row -->
    <div class="row g-3 mb-4">
        <!-- Monthly Stock Movements Bar Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0"><i class="fas fa-chart-column me-2 text-primary"></i> Monthly Stock Movement Trends</h6>
                    <span class="badge bg-light text-muted border">Last 12 Months</span>
                </div>
                <div style="height: 280px;">
                    <canvas id="monthlyMovementsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution Doughnut Chart -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Stock by Category</h6>
                </div>
                <div style="height: 280px;" class="d-flex align-items-center justify-content-center">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts & Tables Row -->
    <div class="row g-3 mb-4">
        <!-- Top Moving Products Chart -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0"><i class="fas fa-fire me-2 text-danger"></i> Top Moving Products</h6>
                </div>
                <div style="height: 260px;">
                    <canvas id="topMovingProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity Audit Trail Table -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Recent Stock Activities</h6>
                    <a href="/movements" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold">View Full History &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-7">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentMovements)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No recent activities logged.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentMovements as $mv): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-slate-800"><?= htmlspecialchars($mv->product_name) ?></div>
                                            <small class="text-muted fs-8"><?= htmlspecialchars($mv->sku) ?></small>
                                        </td>
                                        <td><?= $mv->getTypeBadgeHtml() ?></td>
                                        <td class="fw-bold"><?= $mv->quantity ?></td>
                                        <td><?= htmlspecialchars($mv->user_name) ?></td>
                                        <td class="text-muted fs-8"><?= date('M d, H:i', strtotime($mv->created_at)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low & Out of Stock Alert Table Section -->
    <?php if (!empty($lowStockProducts)): ?>
        <div class="card border-0 shadow-xs rounded-4 bg-white p-4 mb-4 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-slate-900 mb-0">
                    <i class="fas fa-triangle-exclamation text-warning me-2 fs-5"></i> Immediate Inventory Action Required
                </h6>
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill"><?= count($lowStockProducts) ?> Alert(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0 fs-7" id="dashboardLowStockTable">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Current Stock</th>
                            <th>Min Stock Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockProducts as $p): ?>
                            <tr>
                                <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                <td class="fw-bold text-slate-900"><?= htmlspecialchars($p->product_name) ?></td>
                                <td><?= htmlspecialchars($p->category_name) ?></td>
                                <td><?= htmlspecialchars($p->supplier_name) ?></td>
                                <td class="fw-bold fs-6 <?= $p->quantity == 0 ? 'text-danger' : 'text-warning' ?>"><?= $p->quantity ?></td>
                                <td><?= $p->min_stock_level ?></td>
                                <td><?= $p->getStockStatusHtml() ?></td>
                                <td>
                                    <a href="/inventory/stock-in?product_id=<?= $p->product_id ?>" class="btn btn-xs btn-success rounded-2">
                                        <i class="fas fa-plus me-1"></i> Restock
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Pass Backend Chart Data to JS -->
<script>
    window.dashboardChartsData = <?= json_encode($chartsData) ?>;
</script>
