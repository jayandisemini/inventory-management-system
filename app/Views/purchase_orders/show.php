<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Purchase Order <?= htmlspecialchars($po->po_number) ?></h4>
            <p class="text-slate-400 fs-7 mb-0">Supplier: <span class="text-white fw-bold"><?= htmlspecialchars($po->supplier_name) ?></span> | Status: <?= $po->getStatusBadgeHtml() ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="/purchase-orders/print?id=<?= $po->po_id ?>" target="_blank" class="btn btn-outline-cyan btn-sm rounded-3">
                <i class="fas fa-print me-1.5"></i> Print Official PO
            </a>
            <a href="/purchase-orders" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-arrow-left me-1.5"></i> Back to PO List
            </a>
        </div>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between border-bottom border-slate-800 pb-3 mb-4">
            <div>
                <h6 class="fw-bold text-white mb-0">Supplier Procurement Breakdown</h6>
                <small class="text-slate-400">Issued by <?= htmlspecialchars($po->user_name) ?> on <?= date('F j, Y', strtotime($po->created_at)) ?></small>
            </div>
            <?php if ($po->status !== 'Received'): ?>
                <form action="/purchase-orders/receive" method="POST">
                    <?= CSRF::field() ?>
                    <input type="hidden" name="id" value="<?= $po->po_id ?>">
                    <button type="submit" class="btn btn-emerald btn-sm rounded-3 fw-semibold">
                        <i class="fas fa-boxes-packing me-1.5"></i> Mark as Received & Auto-Restock
                    </button>
                </form>
            <?php else: ?>
                <span class="badge bg-emerald-subtle text-emerald fs-7 px-3 py-2 border border-emerald-subtle">
                    <i class="fas fa-check-circle me-1"></i> Inventory Restocked
                </span>
            <?php endif; ?>
        </div>

        <div class="table-responsive mb-4">
            <table class="table align-middle fs-7 mb-0">
                <thead>
                    <tr class="text-slate-400">
                        <th>SKU</th>
                        <th>Product Description</th>
                        <th>Quantity Ordered</th>
                        <th>Unit Cost (Rs.)</th>
                        <th class="text-end">Total Line Cost (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($po->items as $item): ?>
                        <tr>
                            <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($item['sku']) ?></td>
                            <td class="fw-bold text-white"><?= htmlspecialchars($item['product_name']) ?></td>
                            <td class="fw-bold"><?= number_format($item['quantity']) ?></td>
                            <td>Rs. <?= number_format($item['unit_cost'], 2) ?></td>
                            <td class="text-end fw-bold text-emerald">Rs. <?= number_format($item['total_cost'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="border-top border-slate-800 fs-6 fw-bold">
                        <td colspan="4" class="text-end text-white">Grand Total PO Cost:</td>
                        <td class="text-end text-emerald fs-5">Rs. <?= number_format($po->total_amount, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if ($po->notes): ?>
            <div class="bg-slate-950 p-3 rounded-3 border border-slate-800">
                <small class="text-slate-400 d-block mb-1 fw-bold">Supplier Notes:</small>
                <p class="text-white mb-0 fs-7"><?= htmlspecialchars($po->notes) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
