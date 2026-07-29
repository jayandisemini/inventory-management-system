<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'SIMS Official Report') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #fff; color: #1e293b; font-size: 13px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
            .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: #f8fafc !important; }
        }
        .header-brand { border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
    </style>
</head>
<body class="p-4" onload="window.print()">

<div class="no-print mb-4 d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3">
    <span>Print Preview Mode - Click Print or Save as PDF in your browser dialog.</span>
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print me-1"></i> Print / Save PDF</button>
</div>

<div class="header-brand d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-0">Smart Inventory Management System (SIMS)</h3>
        <p class="text-muted mb-0">Enterprise Inventory Telemetry & Audit Report</p>
    </div>
    <div class="text-end">
        <h5 class="fw-bold text-primary mb-0"><?= htmlspecialchars($reportData['title']) ?></h5>
        <small class="text-muted">Generated: <?= date('Y-m-d H:i:s T') ?></small>
    </div>
</div>

<?php if ($currentType === 'inventory_value'): ?>
    <div class="row text-center mb-4">
        <div class="col-3"><div class="border p-2 bg-light"><strong>Total Stock Cost:</strong> $<?= number_format($reportData['total_cost_valuation'] ?? 0, 2) ?></div></div>
        <div class="col-3"><div class="border p-2 bg-light"><strong>Total Retail Value:</strong> $<?= number_format($reportData['total_retail_valuation'] ?? 0, 2) ?></div></div>
        <div class="col-3"><div class="border p-2 bg-light"><strong>Unrealized Margin:</strong> $<?= number_format($reportData['potential_profit'] ?? 0, 2) ?></div></div>
        <div class="col-3"><div class="border p-2 bg-light"><strong>Total Items:</strong> <?= number_format($reportData['total_items_count'] ?? 0) ?></div></div>
    </div>

    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Selling Price</th>
                <th>Total Retail Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportData['products'] as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p->sku) ?></td>
                    <td><?= htmlspecialchars($p->product_name) ?></td>
                    <td><?= htmlspecialchars($p->category_name) ?></td>
                    <td><?= $p->quantity ?></td>
                    <td>$<?= number_format($p->unit_price, 2) ?></td>
                    <td>$<?= number_format($p->quantity * $p->unit_price, 2) ?></td>
                    <td>$<?= number_format($p->selling_price, 2) ?></td>
                    <td>$<?= number_format($p->quantity * $p->selling_price, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php elseif ($currentType === 'low_stock'): ?>
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Current Stock</th>
                <th>Min Threshold</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportData['products'] as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p->sku) ?></td>
                    <td><?= htmlspecialchars($p->product_name) ?></td>
                    <td><?= htmlspecialchars($p->category_name) ?></td>
                    <td><?= htmlspecialchars($p->supplier_name) ?></td>
                    <td><?= $p->quantity ?></td>
                    <td><?= $p->min_stock_level ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php elseif ($currentType === 'movements'): ?>
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>Log ID</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Type</th>
                <th>Qty</th>
                <th>User</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportData['movements'] as $m): ?>
                <tr>
                    <td>#LOG-<?= str_pad($m->movement_id, 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($m->product_name) ?></td>
                    <td><?= htmlspecialchars($m->sku) ?></td>
                    <td><?= htmlspecialchars($m->movement_type) ?></td>
                    <td><?= $m->quantity ?></td>
                    <td><?= htmlspecialchars($m->user_name) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($m->created_at)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php elseif ($currentType === 'suppliers'): ?>
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>Supplier Company</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Products Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportData['suppliers'] as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s->supplier_name) ?></td>
                    <td><?= htmlspecialchars($s->contact_person ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($s->phone ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($s->email ?? 'N/A') ?></td>
                    <td><?= $s->product_count ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="mt-5 pt-4 border-top d-flex justify-content-between fs-8 text-muted">
    <span>Smart Inventory Management System (SIMS)</span>
    <span>Authorized Compliance Report</span>
</div>
</body>
</html>
