<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Purchase Order (PO) Procurement</h4>
            <p class="text-slate-400 fs-7 mb-0">Generate, track, and restock purchase orders sent to suppliers.</p>
        </div>
        <a href="/purchase-orders/create" class="btn btn-cyan btn-sm rounded-3 fw-semibold">
            <i class="fas fa-file-circle-plus me-1.5"></i> Create New PO
        </a>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="movementsDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>PO Number</th>
                        <th>Supplier Company</th>
                        <th>Total Amount</th>
                        <th>Created By</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-4">No Purchase Orders created yet. Click "Create New PO" to start.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $po): ?>
                            <tr>
                                <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($po->po_number) ?></td>
                                <td class="fw-bold text-white"><?= htmlspecialchars($po->supplier_name) ?></td>
                                <td class="fw-bold text-emerald">$<?= number_format($po->total_amount, 2) ?></td>
                                <td><?= htmlspecialchars($po->user_name) ?></td>
                                <td><?= $po->getStatusBadgeHtml() ?></td>
                                <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($po->created_at)) ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/purchase-orders/show?id=<?= $po->po_id ?>" class="btn btn-slate-800 text-white border border-slate-700" title="View PO Details">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="/purchase-orders/print?id=<?= $po->po_id ?>" target="_blank" class="btn btn-outline-cyan" title="Print Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
