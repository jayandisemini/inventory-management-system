<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-building me-2 text-cyan"></i><?= htmlspecialchars($supplier->supplier_name) ?></h4>
            <p class="text-slate-400 fs-7 mb-0">Supplier Profile & Associated Inventory Items</p>
        </div>
        <a href="/suppliers" class="btn btn-outline-light btn-sm rounded-3">
            <i class="fas fa-arrow-left me-1.5"></i> Back to Directory
        </a>
    </div>

    <div class="row g-4 mb-4">
        <!-- Supplier Contact Card -->
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <h6 class="fw-bold text-white border-bottom border-slate-800 pb-3 mb-3"><i class="fas fa-address-card me-2 text-cyan"></i> Contact Profile</h6>

                <div class="fs-7 mb-3">
                    <span class="text-slate-400 d-block fs-8">Contact Representative</span>
                    <span class="fw-semibold text-white fs-6"><?= htmlspecialchars($supplier->contact_person ?? 'N/A') ?></span>
                </div>

                <div class="fs-7 mb-3">
                    <span class="text-slate-400 d-block fs-8">Phone Contact</span>
                    <span class="fw-mono text-white"><?= htmlspecialchars($supplier->phone ?? 'N/A') ?></span>
                </div>

                <div class="fs-7 mb-3">
                    <span class="text-slate-400 d-block fs-8">Email Address</span>
                    <a href="mailto:<?= htmlspecialchars($supplier->email ?? '') ?>" class="text-cyan text-decoration-none fw-semibold"><?= htmlspecialchars($supplier->email ?? 'N/A') ?></a>
                </div>

                <div class="fs-7">
                    <span class="text-slate-400 d-block fs-8">Corporate Address</span>
                    <span class="text-slate-300"><?= nl2br(htmlspecialchars($supplier->address ?? 'No physical address provided.')) ?></span>
                </div>
            </div>
        </div>

        <!-- Supplier Products List -->
        <div class="col-12 col-md-8">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between border-bottom border-slate-800 pb-3 mb-3">
                    <h6 class="fw-bold text-white mb-0"><i class="fas fa-boxes-packing me-2 text-cyan"></i> Supplied Inventory Products</h6>
                    <span class="badge bg-cyan text-slate-950 rounded-pill"><?= count($products) ?> Item(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7">
                        <thead>
                            <tr class="text-slate-400">
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
                                    <td colspan="6" class="text-center text-slate-400 py-4">No active products associated with this supplier.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($p['sku']) ?></td>
                                        <td class="fw-bold text-white"><?= htmlspecialchars($p['product_name']) ?></td>
                                        <td><span class="badge bg-slate-800 text-slate-300 border border-slate-700"><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></span></td>
                                        <td class="text-slate-300">Rs. <?= number_format($p['unit_price'], 2) ?></td>
                                        <td class="fw-bold text-white"><?= $p['quantity'] ?></td>
                                        <td>
                                            <a href="/products/show?id=<?= $p['product_id'] ?>" class="btn btn-xs btn-slate-800 text-white border border-slate-700">
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
