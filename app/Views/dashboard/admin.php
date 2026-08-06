<div class="container-fluid px-0">

    <!-- Admin Executive Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 role-banner-admin p-4 rounded-4 shadow-sm text-white">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0"><i class="fas fa-crown me-2 text-warning"></i> Admin Executive Command Center</h4>
                <span class="badge bg-purple-subtle text-purple rounded-pill px-2.5 py-1 fs-8 fw-bold border border-purple">
                    <i class="fas fa-shield-cat me-1"></i> FULL SYSTEM GOVERNANCE
                </span>
            </div>
            <p class="text-slate-400 fs-7 mb-0">High-level enterprise telemetry, catalog health ratings, asset valuations, and audit stream.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="/users" class="btn btn-purple btn-sm rounded-3">
                <i class="fas fa-users-gear me-1.5"></i> Manage Users
            </a>
            <a href="/settings" class="btn btn-theme-outline btn-sm rounded-3">
                <i class="fas fa-sliders me-1.5"></i> System Settings
            </a>
            <a href="/reports" class="btn btn-cyan btn-sm rounded-3">
                <i class="fas fa-chart-line me-1.5"></i> Executive Reports
            </a>
            <a href="/purchase-orders/create" class="btn btn-theme-outline btn-sm rounded-3">
                <i class="fas fa-file-signature me-1.5"></i> Create PO
            </a>
        </div>
    </div>

    <!-- 4 Executive KPI Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Retail Valuation -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/reports" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-theme-muted fs-7 fw-semibold">Retail Valuation</span>
                        <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-dollar-sign fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-emerald mb-2">Rs. <?= number_format($metrics['retail_valuation'] ?? 0, 2) ?></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-emerald-subtle text-emerald rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-arrow-trend-up me-1"></i>+14.2% vs last mo
                        </span>
                        <span class="text-theme-muted fs-8">Margin: +Rs. <?= number_format($metrics['potential_profit'] ?? 0, 2) ?></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 2. Catalog Products & Health -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/products" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-theme-muted fs-7 fw-semibold">Catalog Products</span>
                        <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-boxes-stacked fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-theme-main mb-2"><?= number_format($metrics['total_products'] ?? 0) ?> <span class="fs-7 text-theme-muted fw-normal">SKUs</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-cyan-subtle text-cyan rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-heart-pulse me-1"></i>Health: <?= $metrics['health_percentage'] ?? 100 ?>%
                        </span>
                        <span class="text-theme-muted fs-8"><?= $metrics['total_categories'] ?? 0 ?> Categories</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. System Users Count -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/users" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-theme-muted fs-7 fw-semibold">System Operators</span>
                        <div class="metric-icon bg-purple-subtle text-purple rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-users-gear fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-purple mb-2"><?= number_format($metrics['total_users'] ?? 0) ?> <span class="fs-7 text-theme-muted fw-normal">Users</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-purple-subtle text-purple rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-user-shield me-1"></i>RBAC Roles Active
                        </span>
                        <span class="text-theme-muted fs-8">Admin / Mgr / Staff</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- 4. Pending Requisitions Risk Alert -->
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="/stock-requests" class="text-decoration-none">
                <div class="card card-metric border-0 rounded-4 p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-theme-muted fs-7 fw-semibold">Pending Staff Requests</span>
                        <div class="metric-icon bg-warning-subtle text-amber rounded-3 d-flex align-items-center justify-content-center p-2.5">
                            <i class="fas fa-clipboard-question fs-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-amber mb-2"><?= count($pendingRequests ?? []) ?> <span class="fs-7 text-theme-muted fw-normal">Pending</span></h2>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-warning-subtle text-amber rounded-pill fs-8 px-2 py-0.5">
                            <i class="fas fa-bell me-1"></i>Moderation Queue
                        </span>
                        <span class="text-theme-muted fs-8">Review & Approve</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Analytics Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Monthly Movement Bar Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-chart-bar me-2 text-cyan"></i> Monthly Stock Activity Telemetry</h6>
                        <small class="text-theme-muted fs-8">Inflow vs Outflow inventory movements</small>
                    </div>
                    <span class="badge bg-cyan-subtle text-cyan fs-8">Live Data</span>
                </div>
                <div style="height: 280px;">
                    <canvas id="monthlyMovementsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Donut Chart -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-chart-pie me-2 text-emerald"></i> Category Distribution</h6>
                        <small class="text-theme-muted fs-8">Stock breakdown by category</small>
                    </div>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Governance Queue & Audit Telemetry -->
    <div class="row g-3">
        <!-- Pending Staff Requisitions Moderation Queue -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-gavel me-2 text-amber"></i> Admin Moderation Queue</h6>
                        <small class="text-theme-muted fs-8">Staff requisitions waiting for approval</small>
                    </div>
                    <a href="/stock-requests" class="text-cyan fs-8 text-decoration-none fw-semibold">View All &rarr;</a>
                </div>

                <?php if (empty($pendingRequests)): ?>
                    <div class="text-center py-4 my-auto text-theme-muted">
                        <i class="fas fa-circle-check fs-3 text-emerald mb-2"></i>
                        <p class="mb-0 fs-7">No pending requests! All requisitions moderated.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach (array_slice($pendingRequests, 0, 5) as $req): ?>
                            <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <div class="fw-bold text-theme-main fs-7"><?= htmlspecialchars($req->product_name) ?></div>
                                    <div class="text-theme-muted fs-8">
                                        Requested by <span class="fw-semibold text-theme-main"><?= htmlspecialchars($req->user_name) ?></span> &bull; Qty: <strong class="text-cyan"><?= $req->quantity ?></strong>
                                    </div>
                                    <?php if (!empty($req->reason)): ?>
                                        <div class="text-theme-muted fs-8 fst-italic mt-0.5">"<?= htmlspecialchars($req->reason) ?>"</div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-1.5 align-items-center">
                                    <form action="/stock-requests/approve" method="POST" class="d-inline">
                                        <?= \App\Core\CSRF::field() ?>
                                        <input type="hidden" name="id" value="<?= $req->request_id ?>">
                                        <button type="submit" class="btn btn-emerald btn-xs px-2.5 py-1 rounded-2" title="Approve Request">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="/stock-requests/reject" method="POST" class="d-inline">
                                        <?= \App\Core\CSRF::field() ?>
                                        <input type="hidden" name="id" value="<?= $req->request_id ?>">
                                        <button type="submit" class="btn btn-rose btn-xs px-2 py-1 rounded-2" title="Reject Request">
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

        <!-- Live Audit Trail Feed -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-shield-halved me-2 text-purple"></i> Immutable System Audit Trail</h6>
                        <small class="text-theme-muted fs-8">Operator actions & live movement history</small>
                    </div>
                    <a href="/movements" class="text-cyan fs-8 text-decoration-none fw-semibold">Audit Stream &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7">
                        <thead>
                            <tr>
                                <th>Item</th>
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
                                        <small class="text-theme-muted fw-mono fs-8"><?= htmlspecialchars($mv->sku ?? '') ?></small>
                                    </td>
                                    <td><?= $mv->getTypeBadgeHtml() ?></td>
                                    <td class="fw-bold text-theme-main"><?= $mv->quantity ?></td>
                                    <td class="text-theme-muted fs-8">
                                        <i class="fas fa-user-circle me-1 text-purple"></i><?= htmlspecialchars($mv->user_name ?? 'System') ?>
                                    </td>
                                    <td class="text-theme-muted fs-8"><?= date('M d, H:i', strtotime($mv->created_at)) ?></td>
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
