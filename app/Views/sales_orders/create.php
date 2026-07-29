<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Create Customer Sales Invoice</h4>
            <p class="text-slate-400 fs-7 mb-0">Select products, set selling prices, and generate an official customer sales order with automatic stock deduction.</p>
        </div>
        <a href="/sales-orders" class="btn btn-outline-light btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to Sales Orders
        </a>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <form action="/sales-orders/store" method="POST" id="soForm">
            <?= CSRF::field() ?>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <label for="customer_name" class="form-label fw-semibold text-slate-300 fs-7">Customer Name <span class="text-rose">*</span></label>
                    <input type="text" class="form-control bg-slate-950 text-white fs-7" id="customer_name" name="customer_name" required placeholder="e.g. Acme Corporation or John Doe">
                </div>

                <div class="col-12 col-md-6">
                    <label for="customer_email" class="form-label fw-semibold text-slate-300 fs-7">Customer Email Address</label>
                    <input type="email" class="form-control bg-slate-950 text-white fs-7" id="customer_email" name="customer_email" placeholder="customer@example.com">
                </div>
            </div>

            <!-- Dynamic Line Items Table -->
            <h6 class="fw-bold text-white mb-3"><i class="fas fa-cart-shopping me-2 text-emerald"></i> Customer Order Items</h6>

            <div class="table-responsive mb-3">
                <table class="table align-middle mb-0 fs-7" id="soItemsTable">
                    <thead>
                        <tr class="text-slate-400">
                            <th style="width: 45%;">Product</th>
                            <th style="width: 20%;">Quantity Sold</th>
                            <th style="width: 25%;">Selling Price ($)</th>
                            <th style="width: 10%;" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="soItemsBody">
                        <tr class="so-item-row">
                            <td>
                                <select class="form-select bg-slate-950 text-white fs-7 product-select" name="product_id[]" required>
                                    <option value="">Select product...</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p->product_id ?>" data-price="<?= $p->selling_price ?>" data-qty="<?= $p->quantity ?>">
                                            <?= htmlspecialchars($p->product_name) ?> (Available: <?= $p->quantity ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7 qty-input" name="quantity[]" value="1" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control bg-slate-950 text-white fs-7 price-input" name="unit_price[]" value="0.00" required>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-xs btn-outline-rose remove-row-btn"><i class="fas fa-trash-can"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-sm btn-slate-800 text-white border border-slate-700" id="addSOItemBtn">
                    <i class="fas fa-plus me-1 text-emerald"></i> Add Another Product
                </button>
            </div>

            <div class="border-top border-slate-800 pt-3 d-flex justify-content-end gap-2">
                <a href="/sales-orders" class="btn btn-slate-800 text-white">Cancel</a>
                <button type="submit" class="btn btn-emerald px-4"><i class="fas fa-check-circle me-1.5"></i> Confirm & Issue Invoice</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const soItemsBody = document.getElementById('soItemsBody');
    const addSOItemBtn = document.getElementById('addSOItemBtn');

    soItemsBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const price = selectedOpt.dataset.price || 0;
            const row = e.target.closest('tr');
            row.querySelector('.price-input').value = parseFloat(price).toFixed(2);
        }
    });

    addSOItemBtn.addEventListener('click', function () {
        const firstRow = soItemsBody.querySelector('.so-item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.price-input').value = '0.00';
        soItemsBody.appendChild(newRow);
    });

    soItemsBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row-btn')) {
            const rows = soItemsBody.querySelectorAll('.so-item-row');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Sales invoice must contain at least one product.');
            }
        }
    });
});
</script>
