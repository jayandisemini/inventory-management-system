<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Generate Supplier Purchase Order (PO)</h4>
            <p class="text-slate-400 fs-7 mb-0">Specify products, order quantities, and unit costs to issue an official procurement order.</p>
        </div>
        <a href="/purchase-orders" class="btn btn-outline-light btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to PO List
        </a>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <form action="/purchase-orders/store" method="POST" id="poForm">
            <?= CSRF::field() ?>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="supplier_id" class="form-label fw-semibold text-slate-300 fs-7">Select Supplier <span class="text-rose">*</span></label>
                    <select class="form-select bg-slate-950 text-white fs-7" id="supplier_id" name="supplier_id" required>
                        <option value="">Choose supplier...</option>
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= $s->supplier_id ?>"><?= htmlspecialchars($s->supplier_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="notes" class="form-label fw-semibold text-slate-300 fs-7">PO Notes / Special Instructions</label>
                    <input type="text" class="form-control bg-slate-950 text-white fs-7" id="notes" name="notes" placeholder="e.g. Rush delivery requested by Friday">
                </div>
            </div>

            <!-- Dynamic Line Items Table -->
            <h6 class="fw-bold text-white mb-3"><i class="fas fa-list me-2 text-cyan"></i> Order Line Items</h6>
            
            <div class="table-responsive mb-3">
                <table class="table align-middle mb-0 fs-7" id="poItemsTable">
                    <thead>
                        <tr class="text-slate-400">
                            <th style="width: 45%;">Product</th>
                            <th style="width: 20%;">Quantity</th>
                            <th style="width: 25%;">Unit Cost ($)</th>
                            <th style="width: 10%;" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="poItemsBody">
                        <tr class="po-item-row">
                            <td>
                                <select class="form-select bg-slate-950 text-white fs-7 product-select" name="product_id[]" required>
                                    <option value="">Select product...</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p->product_id ?>" data-cost="<?= $p->unit_price ?>">
                                            <?= htmlspecialchars($p->product_name) ?> (SKU: <?= htmlspecialchars($p->sku) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7 qty-input" name="quantity[]" value="10" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control bg-slate-950 text-white fs-7 cost-input" name="unit_cost[]" value="0.00" required>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-xs btn-outline-rose remove-row-btn"><i class="fas fa-trash-can"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-sm btn-slate-800 text-white border border-slate-700" id="addRowBtn">
                    <i class="fas fa-plus me-1 text-emerald"></i> Add Another Product Line Item
                </button>
            </div>

            <div class="border-top border-slate-800 pt-3 d-flex justify-content-end gap-2">
                <a href="/purchase-orders" class="btn btn-slate-800 text-white">Cancel</a>
                <button type="submit" class="btn btn-cyan px-4"><i class="fas fa-paper-plane me-1.5"></i> Issue Purchase Order</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const poItemsBody = document.getElementById('poItemsBody');
    const addRowBtn = document.getElementById('addRowBtn');

    // Auto update unit cost when selecting product
    poItemsBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const cost = selectedOpt.dataset.cost || 0;
            const row = e.target.closest('tr');
            row.querySelector('.cost-input').value = parseFloat(cost).toFixed(2);
        }
    });

    // Add Row
    addRowBtn.addEventListener('click', function () {
        const firstRow = poItemsBody.querySelector('.po-item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('.qty-input').value = 10;
        newRow.querySelector('.cost-input').value = '0.00';
        poItemsBody.appendChild(newRow);
    });

    // Remove Row
    poItemsBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row-btn')) {
            const rows = poItemsBody.querySelectorAll('.po-item-row');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Purchase Order must contain at least one line item.');
            }
        }
    });
});
</script>
