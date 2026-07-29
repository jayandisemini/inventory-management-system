<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-boxes-stacked text-cyan me-2"></i> Batch & Expiry Date Tracking</h4>
            <p class="text-slate-400 fs-7 mb-0">Monitor product lot numbers, manufacturing dates, and automated expiration risk alerts.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newBatchModal">
                <i class="fas fa-plus me-1.5"></i> Register New Batch
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

    <!-- Batch Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="batchesDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Batch #</th>
                        <th>Product Details</th>
                        <th>Batch Qty</th>
                        <th>Manufactured Date</th>
                        <th>Expiration Date</th>
                        <th>Expiry Status</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($batches as $b): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($b->batch_number) ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($b->product_name) ?></div>
                                <small class="text-slate-400 font-mono fs-8">SKU: <?= htmlspecialchars($b->sku) ?></small>
                            </td>
                            <td class="fw-bold text-white fs-6"><?= number_format($b->quantity) ?></td>
                            <td class="text-slate-300"><?= $b->mfd_date ? date('Y-m-d', strtotime($b->mfd_date)) : 'N/A' ?></td>
                            <td class="fw-bold text-white"><?= date('Y-m-d', strtotime($b->expiry_date)) ?></td>
                            <td><?= $b->getStatusBadgeHtml() ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($b->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Register New Batch -->
<div class="modal fade" id="newBatchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-boxes-stacked text-cyan me-2"></i> Register Product Batch</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/batches/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="batch_prod" class="form-label fw-semibold text-slate-300 fs-7">Target Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="batch_prod" name="product_id" required>
                            <option value="">Choose product...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (SKU: <?= htmlspecialchars($p->sku) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="batch_num" class="form-label fw-semibold text-slate-300 fs-7">Batch / Lot Number <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7 font-mono text-uppercase" id="batch_num" name="batch_number" placeholder="e.g. BATCH-2026-081" required>
                    </div>

                    <div class="mb-3">
                        <label for="batch_qty" class="form-label fw-semibold text-slate-300 fs-7">Batch Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="1" class="form-control bg-slate-950 text-emerald fw-bold fs-7" id="batch_qty" name="quantity" placeholder="e.g. 50" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="mfd_date" class="form-label fw-semibold text-slate-300 fs-7">Mfg Date (Optional)</label>
                            <input type="date" class="form-control bg-slate-950 text-white fs-7" id="mfd_date" name="mfd_date">
                        </div>
                        <div class="col-6">
                            <label for="expiry_date" class="form-label fw-semibold text-slate-300 fs-7">Expiry Date <span class="text-rose">*</span></label>
                            <input type="date" class="form-control bg-slate-950 text-white fs-7" id="expiry_date" name="expiry_date" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-check me-1"></i> Register Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
