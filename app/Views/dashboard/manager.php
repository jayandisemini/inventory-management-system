<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <!-- Manager Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Manager Operations Hub</h4>
            <p class="text-slate-400 fs-7 mb-0">Manage stock inventory, restock low items, and process shipments.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="/inventory/stock-in" class="btn btn-emerald btn-sm rounded-3">
                <i class="fas fa-plus me-1.5"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-rose btn-sm rounded-3">
                <i class="fas fa-minus me-1.5"></i> Stock Out
            </a>
            <a href="/inventory/adjust" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-sliders me-1.5"></i> Adjust Count
            </a>
        </div>
    </div>

    <!-- 4 Clear Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Total Products</span>
                    <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-boxes-stacked fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-white mb-0"><?= number_format($metrics['total_products'] ?? 0) ?></h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Healthy Stock Items</span>
                    <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-circle-check fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-emerald mb-0"><?= number_format($metrics['healthy_count'] ?? 0) ?></h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Low Stock Items</span>
                    <div class="metric-icon bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-warning mb-0"><?= number_format($metrics['low_stock_count'] ?? 0) ?></h2>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-slate-400 fs-7 fw-semibold">Out of Stock</span>
                    <div class="metric-icon bg-rose-subtle text-rose rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-times-circle fs-5"></i>
                    </div>
                </div>
                <h2 class="fw-bold text-rose mb-0"><?= number_format($metrics['out_of_stock_count'] ?? 0) ?></h2>
            </div>
        </div>
    </div>

    <!-- Restock Queue Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-white mb-0">
                <i class="fas fa-truck-ramp-box text-warning me-2"></i> Items Needing Restock
            </h6>
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill"><?= count($restockQueue) ?> Item(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7">
                <thead>
                    <tr class="text-slate-400">
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Current Stock</th>
                        <th>Min Threshold</th>
                        <th>Suggested Restock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($restockQueue)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-emerald py-4">
                                <i class="fas fa-check-circle fs-5 me-2"></i> All products are adequately stocked!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($restockQueue as $p): ?>
                            <tr>
                                <td class="fw-bold text-white"><?= htmlspecialchars($p->product_name) ?></td>
                                <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                <td class="fw-bold <?= $p->quantity <= 0 ? 'text-rose' : 'text-warning' ?>"><?= $p->quantity ?></td>
                                <td><?= $p->min_stock_level ?></td>
                                <td>
                                    <span class="badge bg-emerald-subtle text-emerald px-2.5 py-1 font-mono">
                                        +<?= $p->suggested_reorder_qty ?> units
                                    </span>
                                </td>
                                <td>
                                    <a href="/inventory/stock-in?product_id=<?= $p->product_id ?>&quantity=<?= $p->suggested_reorder_qty ?>" class="btn btn-xs btn-emerald rounded-2">
                                        <i class="fas fa-plus me-1"></i> Restock
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
