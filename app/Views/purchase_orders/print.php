<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order Invoice - <?= htmlspecialchars($po->po_number) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #fff; color: #0f172a; font-size: 13px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
    </style>
</head>
<body onload="window.print()">

<div class="no-print mb-4 d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3" style="max-width: 800px; margin: 20px auto 0;">
    <span>Official Supplier Purchase Order Invoice - Click Print or Save PDF.</span>
    <button onclick="window.print()" class="btn btn-primary btn-sm">Print / Save PDF</button>
</div>

<div class="invoice-box my-4">
    <table class="w-100 mb-4">
        <tr>
            <td>
                <h2 class="fw-bold text-dark mb-0">PURCHASE ORDER</h2>
                <span class="text-primary fw-bold fs-5"><?= htmlspecialchars($po->po_number) ?></span>
            </td>
            <td class="text-end">
                <h4 class="fw-bold text-dark mb-0">Smart Inventory System</h4>
                <small class="text-muted">100 Enterprise Way, Logistics Hub</small><br>
                <small class="text-muted">Date Issued: <?= date('Y-m-d', strtotime($po->created_at)) ?></small>
            </td>
        </tr>
    </table>

    <hr>

    <div class="row my-4">
        <div class="col-6">
            <h6 class="fw-bold text-uppercase text-muted fs-8">VENDOR / SUPPLIER:</h6>
            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($supplier->supplier_name ?? $po->supplier_name) ?></h5>
            <p class="mb-0 text-secondary">
                Attn: <?= htmlspecialchars($supplier->contact_person ?? 'N/A') ?><br>
                Phone: <?= htmlspecialchars($supplier->phone ?? 'N/A') ?><br>
                Email: <?= htmlspecialchars($supplier->email ?? 'N/A') ?><br>
                <?= nl2br(htmlspecialchars($supplier->address ?? '')) ?>
            </p>
        </div>
        <div class="col-6 text-end">
            <h6 class="fw-bold text-uppercase text-muted fs-8">SHIP TO / WAREHOUSE:</h6>
            <h5 class="fw-bold text-dark mb-1">SIMS Central Logistics Center</h5>
            <p class="mb-0 text-secondary">
                Receiving Department Gate 4<br>
                Attn: Inventory Manager<br>
                Status: <strong><?= htmlspecialchars($po->status) ?></strong>
            </p>
        </div>
    </div>

    <table class="table table-bordered align-middle mb-4">
        <thead class="table-dark">
            <tr>
                <th>SKU</th>
                <th>Product Description</th>
                <th>Qty</th>
                <th>Unit Cost ($)</th>
                <th class="text-end">Total Amount ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($po->items as $item): ?>
                <tr>
                    <td class="font-monospace"><?= htmlspecialchars($item['sku']) ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rs. <?= number_format($item['unit_cost'], 2) ?></td>
                    <td class="text-end fw-bold">Rs. <?= number_format($item['total_cost'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end fw-bold">TOTAL PURCHASE ORDER VALUE:</td>
                <td class="text-end fw-bold text-primary fs-5">Rs. <?= number_format($po->total_amount, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if ($po->notes): ?>
        <div class="p-3 bg-light border rounded">
            <strong>Notes & Terms:</strong> <?= htmlspecialchars($po->notes) ?>
        </div>
    <?php endif; ?>

    <div class="mt-5 pt-4 border-top d-flex justify-content-between text-muted fs-8">
        <span>Authorized Signature: _______________________</span>
        <span>Sims ERP System Generated Document</span>
    </div>
</div>
</body>
</html>
