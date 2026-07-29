<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Stock Movement Audit Trail Log</h4>
            <p class="text-slate-400 fs-7 mb-0">Immutable transaction log tracking all Stock In, Stock Out, and Audit Adjustments.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/reports/export-movements-csv" class="btn btn-outline-cyan btn-sm rounded-3 fw-semibold">
                <i class="fas fa-file-csv me-1.5"></i> Export CSV
            </a>
            <a href="/inventory/stock-in" class="btn btn-emerald btn-sm rounded-3 fw-semibold">
                <i class="fas fa-plus me-1.5"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-rose btn-sm rounded-3 fw-semibold">
                <i class="fas fa-minus me-1.5"></i> Stock Out
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 rounded-4 bg-slate-900 p-3 mb-4">
        <form method="GET" action="/movements" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <select name="type" class="form-select form-select-sm bg-slate-950 text-white border-slate-800">
                    <option value="">All Movement Types</option>
                    <option value="Stock In" <?= ($filters['type'] ?? '') === 'Stock In' ? 'selected' : '' ?>>Stock In (Receiving)</option>
                    <option value="Stock Out" <?= ($filters['type'] ?? '') === 'Stock Out' ? 'selected' : '' ?>>Stock Out (Dispatch)</option>
                    <option value="Adjustment" <?= ($filters['type'] ?? '') === 'Adjustment' ? 'selected' : '' ?>>Adjustment (Audit)</option>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <input type="date" name="date_from" class="form-control form-control-sm bg-slate-950 text-white border-slate-800" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
            </div>

            <div class="col-6 col-md-3">
                <input type="date" name="date_to" class="form-control form-control-sm bg-slate-950 text-white border-slate-800" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-cyan w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="/movements" class="btn btn-sm btn-slate-800 text-white border border-slate-700"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Audit Log Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="movementsDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Log ID</th>
                        <th>Product Details</th>
                        <th>Movement Type</th>
                        <th>Quantity Transacted</th>
                        <th>Operator</th>
                        <th>Transaction Timestamp</th>
                        <th>Reference Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movements as $m): ?>
                        <tr>
                            <td class="fw-mono text-cyan fs-8">#LOG-<?= str_pad($m->movement_id, 5, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <a href="/products/show?id=<?= $m->product_id ?>" class="fw-bold text-white text-decoration-none">
                                    <?= htmlspecialchars($m->product_name) ?>
                                </a>
                                <small class="text-slate-400 font-mono d-block fs-8">SKU: <?= htmlspecialchars($m->sku) ?></small>
                            </td>
                            <td><?= $m->getTypeBadgeHtml() ?></td>
                            <td class="fw-bold text-white fs-6"><?= $m->quantity ?></td>
                            <td class="fw-semibold text-slate-300"><?= htmlspecialchars($m->user_name) ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i:s', strtotime($m->created_at)) ?></td>
                            <td class="text-slate-400 fs-8"><?= htmlspecialchars($m->reference_note ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
