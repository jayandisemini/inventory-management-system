<?php
use App\Core\CSRF;
$preselectedId = (int)($_GET['product_id'] ?? 0);
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-sliders text-cyan me-2"></i> Stock Audit Count Adjustment</h4>
            <p class="text-slate-400 fs-7 mb-0">Override current product stock quantity following physical warehouse audit counts.</p>
        </div>
        <a href="/movements" class="btn btn-outline-light btn-sm rounded-3">
            <i class="fas fa-list-check me-1.5"></i> Audit Trail Log
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 rounded-4 bg-slate-900 p-4">
                <form action="/inventory/adjust" method="POST" class="needs-validation" novalidate>
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold text-slate-300 fs-7">Select Target Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="product_id" name="product_id" required>
                            <option value="">Choose product to adjust...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>" <?= $preselectedId == $p->product_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->product_name) ?> (SKU: <?= htmlspecialchars($p->sku) ?>) - System Qty: <?= $p->quantity ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="new_quantity" class="form-label fw-semibold text-slate-300 fs-7">New Actual Audited Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="0" class="form-control bg-slate-950 text-cyan fw-bold fs-6" id="new_quantity" name="new_quantity" placeholder="Enter true counted quantity" required>
                    </div>

                    <div class="mb-4">
                        <label for="reference_note" class="form-label fw-semibold text-slate-300 fs-7">Audit Adjustment Justification / Reason <span class="text-rose">*</span></label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="reference_note" name="reference_note" rows="3" required placeholder="e.g. Quarterly physical warehouse stock audit reconciliation"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-cyan btn-lg rounded-3 fs-6 fw-semibold">
                            <i class="fas fa-save me-2"></i> Save Audited Stock Adjustment
                        </button>
                        <a href="/products" class="btn btn-slate-800 text-white border border-slate-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
