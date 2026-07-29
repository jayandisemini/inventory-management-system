<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">Add New Inventory Product</h4>
            <p class="text-muted fs-7 mb-0">Fill in the specifications below to add an item to catalog.</p>
        </div>
        <a href="/products" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to Catalog
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-4 bg-white p-4">
        <form action="/products/store" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?= CSRF::field() ?>

            <div class="row g-3">
                <!-- Product Name -->
                <div class="col-12 col-md-8">
                    <label for="product_name" class="form-label fw-semibold text-slate-700 fs-7">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-7" id="product_name" name="product_name" placeholder="e.g. Dell UltraSharp 27 Monitor" required>
                </div>

                <!-- SKU Code -->
                <div class="col-12 col-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="sku" class="form-label fw-semibold text-slate-700 fs-7 mb-1">SKU Code <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-link btn-xs p-0 text-decoration-none fw-semibold" id="generateSkuBtn"><i class="fas fa-wand-magic-sparkles me-1"></i> Auto Generate</button>
                    </div>
                    <input type="text" class="form-control fs-7 fw-mono text-uppercase" id="sku" name="sku" placeholder="e.g. ELE-MON-001" required>
                </div>

                <!-- Barcode -->
                <div class="col-12 col-md-6">
                    <label for="barcode" class="form-label fw-semibold text-slate-700 fs-7">Barcode / EAN (Optional)</label>
                    <input type="text" class="form-control fs-7 fw-mono" id="barcode" name="barcode" placeholder="e.g. 884116382901">
                </div>

                <!-- Category -->
                <div class="col-12 col-md-3">
                    <label for="category_id" class="form-label fw-semibold text-slate-700 fs-7">Category <span class="text-danger">*</span></label>
                    <select class="form-select fs-7" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->category_id ?>"><?= htmlspecialchars($cat->category_name) ?></option>
                        <?php foreach ($categories as $cat): ?>
                            
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Supplier -->
                <div class="col-12 col-md-3">
                    <label for="supplier_id" class="form-label fw-semibold text-slate-700 fs-7">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select fs-7" id="supplier_id" name="supplier_id" required>
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= $sup->supplier_id ?>"><?= htmlspecialchars($sup->supplier_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Unit Cost Price -->
                <div class="col-12 col-md-3">
                    <label for="unit_price" class="form-label fw-semibold text-slate-700 fs-7">Unit Cost Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control fs-7" id="unit_price" name="unit_price" value="0.00" required>
                </div>

                <!-- Selling Price -->
                <div class="col-12 col-md-3">
                    <label for="selling_price" class="form-label fw-semibold text-slate-700 fs-7">Selling Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control fs-7" id="selling_price" name="selling_price" value="0.00" required>
                </div>

                <!-- Initial Quantity -->
                <div class="col-12 col-md-3">
                    <label for="quantity" class="form-label fw-semibold text-slate-700 fs-7">Initial Stock Quantity <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control fs-7 fw-bold text-success" id="quantity" name="quantity" value="10" required>
                </div>

                <!-- Minimum Stock Level -->
                <div class="col-12 col-md-3">
                    <label for="min_stock_level" class="form-label fw-semibold text-slate-700 fs-7">Min Stock Alert Level <span class="text-danger">*</span></label>
                    <input type="number" min="1" class="form-control fs-7" id="min_stock_level" name="min_stock_level" value="5" required>
                </div>

                <!-- Product Image Upload -->
                <div class="col-12 col-md-6">
                    <label for="image" class="form-label fw-semibold text-slate-700 fs-7">Product Image (JPG, PNG, WEBP max 2MB)</label>
                    <input type="file" class="form-control fs-7" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                </div>

                <!-- Product Description -->
                <div class="col-12 col-md-6">
                    <label for="description" class="form-label fw-semibold text-slate-700 fs-7">Product Description</label>
                    <textarea class="form-control fs-7" id="description" name="description" rows="3" placeholder="Enter detailed product description or specifications..."></textarea>
                </div>

                <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="/products" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-check me-1.5"></i> Save & Publish Product</button>
                </div>
            </div>
        </form>
    </div>
</div>
