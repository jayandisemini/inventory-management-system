<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-clipboard-check text-cyan me-2"></i> Inventory Stock-Take & Audit Reconciliation</h4>
            <p class="text-slate-400 fs-7 mb-0">Record physical count audits vs expected system stock levels with automated variance analysis.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newStockTakeModal">
                <i class="fas fa-plus me-1.5"></i> Log Physical Stock Count
            </button>
        <?php endif; ?>
    </div>

    <!-- Flash Alert Messages -->
    <?php foreach ($flashes as $flash): ?>
        <?php if (!empty($flash['value'])): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show fs-7 border-0 rounded-3 mb-4" role="alert">
                <?= $flash['value'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Stock-Take Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="stockTakesDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Audit Code</th>
                        <th>Product Item</th>
                        <th>Warehouse Location</th>
                        <th>Expected Qty</th>
                        <th>Actual Counted</th>
                        <th>Variance Status</th>
                        <th>Auditor Name</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stockTakes as $st): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($st->take_code) ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($st->product_name) ?></div>
                                <small class="text-slate-400 font-mono fs-8">SKU: <?= htmlspecialchars($st->sku) ?></small>
                            </td>
                            <td class="fw-semibold text-slate-300"><i class="fas fa-warehouse text-amber me-1"></i> <?= htmlspecialchars($st->warehouse_name) ?></td>
                            <td class="text-slate-400 fs-7"><?= number_format($st->expected_qty) ?></td>
                            <td class="fw-bold text-white fs-6"><?= number_format($st->counted_qty) ?></td>
                            <td><?= $st->getVarianceBadgeHtml() ?></td>
                            <td class="text-slate-300"><?= htmlspecialchars($st->user_name) ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($st->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Log Physical Stock Count -->
<div class="modal fade" id="newStockTakeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-clipboard-check text-cyan me-2"></i> Log Stock Count Audit</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/stock-takes/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="stk_wh" class="form-label fw-semibold text-slate-300 fs-7">Warehouse Location <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="stk_wh" name="warehouse_id" required>
                            <option value="">Choose warehouse...</option>
                            <?php foreach ($warehouses as $wh): ?>
                                <option value="<?= $wh->warehouse_id ?>"><?= htmlspecialchars($wh->warehouse_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="stk_prod" class="form-label fw-semibold text-slate-300 fs-7">Target Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="stk_prod" name="product_id" required>
                            <option value="">Choose product to count...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (System Stock: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="counted_qty" class="form-label fw-semibold text-slate-300 fs-7">Actual Physical Counted Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="0" class="form-control bg-slate-950 text-cyan fw-bold fs-6" id="counted_qty" name="counted_qty" placeholder="Enter physical shelf count" required>
                    </div>

                    <div class="mb-3">
                        <label for="stk_notes" class="form-label fw-semibold text-slate-300 fs-7">Audit Justification / Discrepancy Note</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="stk_notes" name="notes" rows="2" placeholder="e.g. Annual physical count audit reconciliation"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-save me-1"></i> Save Count Audit</button>
                </div>
            </form>
        </div>
    </div>
</div>
