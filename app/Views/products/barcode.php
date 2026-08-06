<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Printable Barcode Labels - SIMS Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f8fafc; color: #0f172a; padding: 20px; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; padding: 0; margin: 0; }
        }
        .barcode-card {
            background: #fff;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 240px;
            margin: 10px;
            display: inline-block;
            vertical-align: top;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .barcode-lines {
            font-family: 'Libre Barcode 128', 'Courier New', monospace;
            font-size: 38px;
            letter-spacing: 2px;
            line-height: 1;
            margin: 8px 0;
            display: block;
        }
    </style>
    <!-- Barcode Font -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
</head>
<body onload="window.print()">

<div class="no-print mb-4 d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3" style="max-width: 900px; margin: auto;">
    <div>
        <h6 class="fw-bold mb-0">Printable Barcode & QR Label Sheet Preview</h6>
        <small class="text-white-50">Standard warehouse sticker size (240px x 140px)</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print me-1"></i> Print Label Sheet</button>
</div>

<div class="text-center" style="max-width: 900px; margin: auto;">
    <?php foreach ($products as $p): ?>
        <?php 
            $barcodeVal = !empty($p->barcode) ? $p->barcode : $p->sku;
        ?>
        <div class="barcode-card">
            <small class="fw-bold text-uppercase d-block text-truncate" style="max-width: 210px;"><?= htmlspecialchars($p->product_name) ?></small>
            <span class="barcode-lines">*<?= htmlspecialchars($barcodeVal) ?>*</span>
            <div class="fw-bold font-monospace fs-7"><?= htmlspecialchars($barcodeVal) ?></div>
            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top fs-8">
                <span class="fw-bold">SKU: <?= htmlspecialchars($p->sku) ?></span>
                <span class="badge bg-dark fs-8">Rs. <?= number_format($p->selling_price, 2) ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
