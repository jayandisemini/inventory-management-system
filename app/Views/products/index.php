<?php
use App\Core\CSRF;
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Product Inventory Catalog</h4>
            <p class="text-slate-400 fs-7 mb-0">Browse, filter, and manage enterprise inventory items.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/products/barcode" target="_blank" class="btn btn-outline-cyan btn-sm rounded-3">
                <i class="fas fa-barcode me-1.5"></i> Print Barcode Labels
            </a>
            <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                <a href="/products/create" class="btn btn-cyan btn-sm rounded-3 fw-semibold">
                    <i class="fas fa-box-open me-1.5"></i> Add New Product
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Advanced Filter Bar -->
    <div class="card border-0 rounded-4 bg-slate-900 p-3 mb-4">
        <form method="GET" action="/products" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-search"></i></span>
                    <input type="text" name="keyword" class="form-control bg-slate-950 border-slate-800 text-white" placeholder="Search SKU, Barcode, Product Name..." value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-6 col-md-2">
                <select name="category_id" class="form-select form-select-sm bg-slate-950 text-white border-slate-800">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->category_id ?>" <?= ($filters['category_id'] ?? '') == $cat->category_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->category_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select name="supplier_id" class="form-select form-select-sm bg-slate-950 text-white border-slate-800">
                    <option value="">All Suppliers</option>
                    <?php foreach ($suppliers as $sup): ?>
                        <option value="<?= $sup->supplier_id ?>" <?= ($filters['supplier_id'] ?? '') == $sup->supplier_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sup->supplier_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select name="stock_status" class="form-select form-select-sm bg-slate-950 text-white border-slate-800">
                    <option value="">All Stock Statuses</option>
                    <option value="in" <?= ($filters['stock_status'] ?? '') === 'in' ? 'selected' : '' ?>>In Stock</option>
                    <option value="low" <?= ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' ?>>Low Stock</option>
                    <option value="out" <?= ($filters['stock_status'] ?? '') === 'out' ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>

            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-cyan w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="/products" class="btn btn-sm btn-slate-800 text-white border border-slate-700"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Products Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="productsDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th style="width: 60px;">Image</th>
                        <th>Product Info</th>
                        <th>SKU / Barcode</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Cost</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p->image && file_exists(UPLOAD_PATH . '/' . $p->image)): ?>
                                    <img src="/uploads/products/<?= htmlspecialchars($p->image) ?>" alt="Product" class="rounded-3 border border-slate-800 object-fit-cover" style="width: 44px; height: 44px;">
                                <?php else: ?>
                                    <div class="rounded-3 bg-slate-950 border border-slate-800 text-slate-400 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        <i class="fas fa-image fs-6"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/products/show?id=<?= $p->product_id ?>" class="fw-bold text-white text-decoration-none view-product-detail" data-id="<?= $p->product_id ?>">
                                    <?= htmlspecialchars($p->product_name) ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-mono text-cyan fs-7"><?= htmlspecialchars($p->sku) ?></div>
                                <small class="text-slate-400 fs-8"><i class="fas fa-barcode me-1"></i><?= htmlspecialchars($p->barcode ?? 'N/A') ?></small>
                            </td>
                            <td><span class="badge bg-slate-800 text-slate-200 border border-slate-700"><?= htmlspecialchars($p->category_name ?? 'Unassigned') ?></span></td>
                            <td class="fs-7 text-slate-300"><?= htmlspecialchars($p->supplier_name ?? 'N/A') ?></td>
                            <td class="text-slate-400 fs-7">$<?= number_format($p->unit_price, 2) ?></td>
                            <td class="fw-semibold text-white">$<?= number_format($p->selling_price, 2) ?></td>
                            <td class="fw-bold fs-6"><?= number_format($p->quantity) ?></td>
                            <td><?= $p->getStockStatusHtml() ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="/products/barcode?id=<?= $p->product_id ?>" target="_blank" class="btn btn-slate-800 text-white border border-slate-700" title="Print Barcode">
                                        <i class="fas fa-barcode"></i>
                                    </a>
                                    <button class="btn btn-slate-800 text-white border border-slate-700 view-product-modal-btn" data-id="<?= $p->product_id ?>" title="Quick View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                                        <a href="/products/edit?id=<?= $p->product_id ?>" class="btn btn-outline-cyan" title="Edit Product">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button class="btn btn-outline-rose delete-product-btn" data-id="<?= $p->product_id ?>" data-name="<?= htmlspecialchars($p->product_name) ?>" title="Delete Product">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-rose"><i class="fas fa-triangle-exclamation me-2"></i> Confirm Product Deletion</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/products/delete" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="deleteProductId">
                <div class="modal-body p-4 text-center">
                    <p class="mb-1 text-slate-300">Are you sure you want to permanently delete this product?</p>
                    <h6 class="fw-bold text-rose" id="deleteProductName">Product Name</h6>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-rose"><i class="fas fa-trash-can me-1"></i> Delete Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-box-open me-2 text-cyan"></i> Product Information</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="quickViewContent">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin fs-3 text-slate-400"></i></div>
            </div>
        </div>
    </div>
</div>
