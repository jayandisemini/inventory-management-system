<?php
use App\Core\CSRF;
$preselectedId = (int)($_GET['product_id'] ?? 0);
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1"><i class="fas fa-circle-arrow-up text-danger me-2"></i> Process Stock Out (Dispatch)</h4>
            <p class="text-muted fs-7 mb-0">Record items dispatched for sales orders or internal allocations.</p>
        </div>
        <a href="/movements" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fas fa-list-check me-1.5"></i> Audit Trail Log
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4">
                <form action="/inventory/stock-out" method="POST" class="needs-validation" novalidate>
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold text-slate-700 fs-7">Select Target Product <span class="text-danger">*</span></label>
                        <select class="form-select fs-7" id="product_id" name="product_id" required>
                            <option value="">Choose product to dispatch...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>" <?= $preselectedId == $p->product_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->product_name) ?> (SKU: <?= htmlspecialchars($p->sku) ?>) - Available: <?= $p->quantity ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold text-slate-700 fs-7">Quantity to Deduct <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger-subtle text-danger border-end-0"><i class="fas fa-minus"></i></span>
                            <input type="number" min="1" class="form-control fs-7 fw-bold text-danger border-start-0 ps-0" id="quantity" name="quantity" placeholder="e.g. 5" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="reference_note" class="form-label fw-semibold text-slate-700 fs-7">Sales Order / Dispatch Reason</label>
                        <textarea class="form-control fs-7" id="reference_note" name="reference_note" rows="3" placeholder="e.g. Sales Order #SO-7092, Dispatched to Customer ACME Corp"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg rounded-3 fs-6 fw-semibold shadow-xs">
                            <i class="fas fa-circle-check me-2"></i> Confirm Stock Out Dispatch
                        </button>
                        <a href="/products" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
