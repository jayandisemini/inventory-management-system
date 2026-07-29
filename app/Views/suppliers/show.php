<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1"><i class="fas fa-building me-2 text-primary"></i><?= htmlspecialchars($supplier->supplier_name) ?></h4>
            <p class="text-muted fs-7 mb-0">Supplier Profile & Associated Inventory Items</p>
        </div>
        <a href="/suppliers" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to Directory
        </a>
    </div>

    <div class="row g-4 mb-4">
        <!-- Supplier Contact Card -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <h6 class="fw-bold text-slate-900 border-bottom pb-3 mb-3"><i class="fas fa-address-card me-2 text-primary"></i> Contact Profile</h6>

                <div class="fs-7 mb-3">
                    <span class="text-muted d-block fs-8">Contact Representative</span>
                    <span class="fw-semibold text-slate-800 fs-6"><?= htmlspecialchars($supplier->contact_person ?? 'N/A') ?></span>
                </div>

                <div class="fs-7 mb-3">
                    <span class="text-muted d-block fs-8">Phone Contact</span>
                    <span class="fw-mono text-dark"><?= htmlspecialchars($supplier->phone ?? 'N/A') ?></span>
                </div>

                <div class="fs-7 mb-3">
                    <span class="text-muted d-block fs-8">Email Address</span>
                    <a href="mailto:<?= htmlspecialchars($supplier->email ?? '') ?>" class="text-primary text-decoration-none fw-semibold"><?= htmlspecialchars($supplier->email ?? 'N/A') ?></a>
                </div>

                <div class="fs-7">
                    <span class="text-muted d-block fs-8">Corporate Address</span>
                    <span class="text-slate-700"><?= nl2br(htmlspecialchars($supplier->address ?? 'No physical address provided.')) ?></span>
                </div>
            </div>
        </div>

        <!-- Supplier Products List -->
        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-xs rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h6 class="fw-bold text-slate-900 mb-0"><i class="fas fa-boxes-packing me-2 text-primary"></i> Supplied Inventory Products</h6>
                    <span class="badge bg-primary rounded-pill"><?= count($products) ?> Item(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 fs-7">
                        <thead class="bg-light">
                            <tr>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Cost Price</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No active products associated with this supplier.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($p['sku']) ?></td>
                                        <td class="fw-bold text-slate-900"><?= htmlspecialchars($p['product_name']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></span></td>
                                        <td>$<?= number_format($p['unit_price'], 2) ?></td>
                                        <td class="fw-bold"><?= $p['quantity'] ?></td>
                                        <td>
                                            <a href="/products/show?id=<?= $p['product_id'] ?>" class="btn btn-xs btn-outline-secondary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
