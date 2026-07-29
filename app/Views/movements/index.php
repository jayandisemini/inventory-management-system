<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">Stock Movement Audit History</h4>
            <p class="text-muted fs-7 mb-0">Immutable compliance log of every stock transaction, receipt, and adjustment.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/inventory/stock-in" class="btn btn-success btn-sm rounded-3">
                <i class="fas fa-plus me-1"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-danger btn-sm rounded-3">
                <i class="fas fa-minus me-1"></i> Stock Out
            </a>
            <a href="/inventory/adjust" class="btn btn-info btn-sm text-dark rounded-3">
                <i class="fas fa-sliders me-1"></i> Adjust
            </a>
        </div>
    </div>

    <!-- Movement History Filter -->
    <div class="card border-0 shadow-xs rounded-4 bg-white p-3 mb-4">
        <form method="GET" action="/movements" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <select name="product_id" class="form-select form-select-sm bg-light">
                    <option value="">All Products</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p->product_id ?>" <?= ($filters['product_id'] ?? '') == $p->product_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p->product_name) ?> (<?= htmlspecialchars($p->sku) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <select name="type" class="form-select form-select-sm bg-light">
                    <option value="">All Movement Types</option>
                    <option value="Stock In" <?= ($filters['type'] ?? '') === 'Stock In' ? 'selected' : '' ?>>Stock In</option>
                    <option value="Stock Out" <?= ($filters['type'] ?? '') === 'Stock Out' ? 'selected' : '' ?>>Stock Out</option>
                    <option value="Adjustment" <?= ($filters['type'] ?? '') === 'Adjustment' ? 'selected' : '' ?>>Adjustment</option>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted">From</span>
                    <input type="date" name="start_date" class="form-control bg-light" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>">
                </div>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="/movements" class="btn btn-sm btn-outline-secondary"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Movement Table -->
    <div class="card border-0 shadow-xs rounded-4 bg-white p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="movementsDataTable">
                <thead class="table-light">
                    <tr>
                        <th>Movement ID</th>
                        <th>Product Details</th>
                        <th>SKU</th>
                        <th>Type</th>
                        <th>Qty Changed</th>
                        <th>Reference / Notes</th>
                        <th>Operator User</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movements as $m): ?>
                        <tr>
                            <td class="fw-mono text-muted fs-8">#LOG-<?= str_pad($m->movement_id, 5, '0', STR_PAD_LEFT) ?></td>
                            <td class="fw-bold text-slate-900"><?= htmlspecialchars($m->product_name) ?></td>
                            <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($m->sku) ?></td>
                            <td><?= $m->getTypeBadgeHtml() ?></td>
                            <td class="fw-bold fs-6 <?= $m->movement_type === 'Stock In' ? 'text-success' : ($m->movement_type === 'Stock Out' ? 'text-danger' : 'text-info') ?>">
                                <?= $m->movement_type === 'Stock Out' ? '-' : '+' ?><?= number_format($m->quantity) ?>
                            </td>
                            <td class="fs-7 text-slate-700"><?= htmlspecialchars($m->reference_note ?? 'No reference note') ?></td>
                            <td class="fs-7"><i class="fas fa-user-circle text-secondary me-1"></i><?= htmlspecialchars($m->user_name) ?></td>
                            <td class="text-muted fs-8"><?= date('Y-m-d H:i:s', strtotime($m->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
