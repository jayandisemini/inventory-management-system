<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Sales Invoice <?= htmlspecialchars($so->order_number) ?></h4>
            <p class="text-slate-400 fs-7 mb-0">Customer: <span class="text-white fw-bold"><?= htmlspecialchars($so->customer_name) ?></span> | Status: <?= $so->getStatusBadgeHtml() ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="/sales-orders/print?id=<?= $so->so_id ?>" target="_blank" class="btn btn-outline-cyan btn-sm rounded-3">
                <i class="fas fa-print me-1.5"></i> Print Official Customer Receipt
            </a>
            <a href="/sales-orders" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-arrow-left me-1.5"></i> Back to Sales Orders
            </a>
        </div>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between border-bottom border-slate-800 pb-3 mb-4">
            <div>
                <h6 class="fw-bold text-white mb-0">Customer Sales Invoice Details</h6>
                <small class="text-slate-400">Issued by <?= htmlspecialchars($so->user_name) ?> on <?= date('F j, Y, H:i', strtotime($so->created_at)) ?></small>
            </div>
            <span class="badge bg-emerald-subtle text-emerald fs-7 px-3 py-2 border border-emerald-subtle">
                <i class="fas fa-check-circle me-1"></i> Stock Deducted & Payment Received
            </span>
        </div>

        <div class="table-responsive mb-4">
            <table class="table align-middle fs-7 mb-0">
                <thead>
                    <tr class="text-slate-400">
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Unit Price (Rs.)</th>
                        <th class="text-end">Line Total (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($so->items as $item): ?>
                        <tr>
                            <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($item['sku']) ?></td>
                            <td class="fw-bold text-white"><?= htmlspecialchars($item['product_name']) ?></td>
                            <td class="fw-bold"><?= number_format($item['quantity']) ?></td>
                            <td>Rs. <?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-end fw-bold text-emerald">Rs. <?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="border-top border-slate-800 fs-6 fw-bold">
                        <td colspan="4" class="text-end text-white">Grand Total Billed:</td>
                        <td class="text-end text-emerald fs-5">Rs. <?= number_format($so->total_amount, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
