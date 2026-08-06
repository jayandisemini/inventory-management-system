<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Sales Receipt - <?= htmlspecialchars($so->order_number) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #fff; color: #0f172a; font-size: 13px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
        .receipt-box { max-width: 700px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .1); }
    </style>
</head>
<body onload="window.print()">

<div class="no-print mb-4 d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3" style="max-width: 700px; margin: 20px auto 0;">
    <span>Customer Sales Receipt - Click Print or Save PDF.</span>
    <button onclick="window.print()" class="btn btn-primary btn-sm">Print Receipt</button>
</div>

<div class="receipt-box my-4">
    <div class="text-center mb-4 border-bottom pb-3">
        <h3 class="fw-bold text-dark mb-0">OFFICIAL SALES RECEIPT</h3>
        <span class="text-primary font-monospace fw-bold"><?= htmlspecialchars($so->order_number) ?></span>
        <div class="text-muted fs-8 mt-1">Date: <?= date('Y-m-d H:i', strtotime($so->created_at)) ?></div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <small class="text-muted fw-bold d-block text-uppercase">BILLED TO:</small>
            <strong class="fs-6"><?= htmlspecialchars($so->customer_name) ?></strong><br>
            <span class="text-secondary"><?= htmlspecialchars($so->customer_email ?? 'N/A') ?></span>
        </div>
        <div class="col-6 text-end">
            <small class="text-muted fw-bold d-block text-uppercase">ISSUED BY:</small>
            <strong>Smart Inventory Systems</strong><br>
            <span class="text-secondary">Cashier: <?= htmlspecialchars($so->user_name) ?></span>
        </div>
    </div>

    <table class="table table-bordered align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Price ($)</th>
                <th class="text-end">Total ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($so->items as $item): ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rs. <?= number_format($item['unit_price'], 2) ?></td>
                    <td class="text-end fw-bold">Rs. <?= number_format($item['total_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">TOTAL AMOUNT PAID:</td>
                <td class="text-end fw-bold text-success fs-5">Rs. <?= number_format($so->total_amount, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center text-muted fs-8 mt-4 pt-3 border-top">
        <p class="mb-0">Thank you for your business!</p>
        <small>Sims ERP System Generated Receipt</small>
    </div>
</div>
</body>
</html>
