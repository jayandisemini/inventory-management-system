<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">Edit Product - <?= htmlspecialchars($product->product_name) ?></h4>
            <p class="text-muted fs-7 mb-0">Update pricing, minimum levels, or specifications.</p>
        </div>
        <a href="/products" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to Catalog
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-4 bg-white p-4">
        <form action="/products/update" method="POST" enctype="multipart/form-data">
            <?= CSRF::field() ?>
            <input type="hidden" name="id" value="<?= $product->product_id ?>">

            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <label for="product_name" class="form-label fw-semibold text-slate-700 fs-7">Product Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-7" id="product_name" name="product_name" value="<?= htmlspecialchars($product->product_name) ?>" required>
                </div>

                <div class="col-12 col-md-4">
                    <label for="sku" class="form-label fw-semibold text-slate-700 fs-7">SKU Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-7 fw-mono text-uppercase" id="sku" name="sku" value="<?= htmlspecialchars($product->sku) ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="barcode" class="form-label fw-semibold text-slate-700 fs-7">Barcode / EAN</label>
                    <input type="text" class="form-control fs-7 fw-mono" id="barcode" name="barcode" value="<?= htmlspecialchars($product->barcode ?? '') ?>">
                </div>

                <div class="col-12 col-md-3">
                    <label for="category_id" class="form-label fw-semibold text-slate-700 fs-7">Category <span class="text-danger">*</span></label>
                    <select class="form-select fs-7" id="category_id" name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->category_id ?>" <?= $product->category_id == $cat->category_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->category_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label for="supplier_id" class="form-label fw-semibold text-slate-700 fs-7">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select fs-7" id="supplier_id" name="supplier_id" required>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= $sup->supplier_id ?>" <?= $product->supplier_id == $sup->supplier_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sup->supplier_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label for="unit_price" class="form-label fw-semibold text-slate-700 fs-7">Unit Cost Price ($)</label>
                    <input type="number" step="0.01" min="0" class="form-control fs-7" id="unit_price" name="unit_price" value="<?= $product->unit_price ?>" required>
                </div>

                <div class="col-12 col-md-3">
                    <label for="selling_price" class="form-label fw-semibold text-slate-700 fs-7">Selling Price ($)</label>
                    <input type="number" step="0.01" min="0" class="form-control fs-7" id="selling_price" name="selling_price" value="<?= $product->selling_price ?>" required>
                </div>

                <div class="col-12 col-md-3">
                    <label for="quantity" class="form-label fw-semibold text-slate-700 fs-7">Current Stock Quantity</label>
                    <input type="number" min="0" class="form-control fs-7 fw-bold" id="quantity" name="quantity" value="<?= $product->quantity ?>" required>
                </div>

                <div class="col-12 col-md-3">
                    <label for="min_stock_level" class="form-label fw-semibold text-slate-700 fs-7">Min Stock Alert Level</label>
                    <input type="number" min="1" class="form-control fs-7" id="min_stock_level" name="min_stock_level" value="<?= $product->min_stock_level ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="image" class="form-label fw-semibold text-slate-700 fs-7">Update Image</label>
                    <input type="file" class="form-control fs-7" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    <?php if ($product->image): ?>
                        <small class="text-muted d-block mt-1">Current file: <?= htmlspecialchars($product->image) ?></small>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-6">
                    <label for="description" class="form-label fw-semibold text-slate-700 fs-7">Product Description</label>
                    <textarea class="form-control fs-7" id="description" name="description" rows="3"><?= htmlspecialchars($product->description ?? '') ?></textarea>
                </div>

                <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="/products" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1.5"></i> Update Product Specifications</button>
                </div>
            </div>
        </form>
    </div>
</div>
