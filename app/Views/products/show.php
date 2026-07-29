<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><?= htmlspecialchars($product->product_name) ?></h4>
            <p class="text-slate-400 fs-7 mb-0">Product ID: #<?= $product->product_id ?> | SKU: <span class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($product->sku) ?></span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="/products/edit?id=<?= $product->product_id ?>" class="btn btn-cyan btn-sm rounded-3 fw-semibold">
                <i class="fas fa-pen me-1.5"></i> Edit Details
            </a>
            <a href="/products" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-arrow-left me-1.5"></i> Back to Catalog
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 text-center">
                <?php if ($product->image && file_exists(UPLOAD_PATH . '/' . $product->image)): ?>
                    <img src="/uploads/products/<?= htmlspecialchars($product->image) ?>" alt="Product" class="img-fluid rounded-3 border border-slate-800 mb-3 object-fit-cover" style="max-height: 260px;">
                <?php else: ?>
                    <div class="rounded-3 bg-slate-950 border border-slate-800 p-5 text-slate-400 mb-3">
                        <i class="fas fa-box-open fs-1 mb-2 d-block text-slate-400"></i>
                        <span>No Product Image Uploaded</span>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <?= $product->getStockStatusHtml() ?>
                </div>

                <div class="d-grid gap-2">
                    <a href="/inventory/stock-in?product_id=<?= $product->product_id ?>" class="btn btn-emerald btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add Stock (Stock In)
                    </a>
                    <a href="/inventory/stock-out?product_id=<?= $product->product_id ?>" class="btn btn-rose btn-sm">
                        <i class="fas fa-minus-circle me-1"></i> Remove Stock (Stock Out)
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card border-0 rounded-4 bg-slate-900 p-4">
                <h6 class="fw-bold text-white border-bottom border-slate-800 pb-3 mb-3"><i class="fas fa-circle-info me-2 text-cyan"></i> Specification Breakdown</h6>

                <div class="row g-3 fs-7">
                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Category</span>
                        <span class="fw-semibold text-white"><?= htmlspecialchars($product->category_name ?? 'N/A') ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Supplier</span>
                        <span class="fw-semibold text-white"><?= htmlspecialchars($product->supplier_name ?? 'N/A') ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Barcode / EAN</span>
                        <span class="fw-mono fw-semibold text-white"><?= htmlspecialchars($product->barcode ?? 'N/A') ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Unit Cost Price</span>
                        <span class="fw-bold text-slate-300">$<?= number_format($product->unit_price, 2) ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Selling Retail Price</span>
                        <span class="fw-bold text-white">$<?= number_format($product->selling_price, 2) ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Margin per Unit</span>
                        <span class="fw-bold text-emerald">$<?= number_format($product->selling_price - $product->unit_price, 2) ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Available Stock Quantity</span>
                        <span class="fw-bold fs-5 text-white"><?= number_format($product->quantity) ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Minimum Alert Level</span>
                        <span class="fw-semibold text-warning"><?= number_format($product->min_stock_level) ?></span>
                    </div>

                    <div class="col-6 col-sm-4">
                        <span class="text-slate-400 d-block fs-8">Total Stock Retail Value</span>
                        <span class="fw-bold text-emerald">$<?= number_format($product->quantity * $product->selling_price, 2) ?></span>
                    </div>

                    <div class="col-12 border-top border-slate-800 pt-3 mt-3">
                        <span class="text-slate-400 d-block fs-8 mb-1">Description & Notes</span>
                        <p class="text-slate-300 bg-slate-950 p-3 rounded-3 border border-slate-800 mb-0"><?= nl2br(htmlspecialchars($product->description ?? 'No description provided.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
