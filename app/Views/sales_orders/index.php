<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Customer Sales Orders & Invoices</h4>
            <p class="text-slate-400 fs-7 mb-0">Issue sales invoices, track customer purchases, and print official sales receipts.</p>
        </div>
        <a href="/sales-orders/create" class="btn btn-emerald btn-sm rounded-3 fw-semibold">
            <i class="fas fa-cart-plus me-1.5"></i> New Sales Invoice
        </a>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="salesOrdersDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Invoice #</th>
                        <th>Customer Name</th>
                        <th>Total Billed</th>
                        <th>Payment Status</th>
                        <th>Issued By</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $so): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($so->order_number) ?></td>
                            <td class="fw-bold text-white">
                                <?= htmlspecialchars($so->customer_name) ?>
                                <?php if ($so->customer_email): ?>
                                    <small class="text-slate-400 d-block fs-8"><?= htmlspecialchars($so->customer_email) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-emerald fs-6">$<?= number_format($so->total_amount, 2) ?></td>
                            <td><?= $so->getStatusBadgeHtml() ?></td>
                            <td><?= htmlspecialchars($so->user_name) ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($so->created_at)) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="/sales-orders/show?id=<?= $so->so_id ?>" class="btn btn-slate-800 text-white border border-slate-700" title="View Details">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="/sales-orders/print?id=<?= $so->so_id ?>" target="_blank" class="btn btn-outline-cyan" title="Print Receipt">
                                        <i class="fas fa-print"></i> Receipt
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
