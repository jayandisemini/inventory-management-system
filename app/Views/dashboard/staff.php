<div class="container-fluid px-0">

    <!-- Staff Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Staff Product Terminal</h4>
            <p class="text-slate-400 fs-7 mb-0">Search products, view stock availability, and submit stock requests.</p>
        </div>
        <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#submitRequestModal">
            <i class="fas fa-paper-plane me-1.5"></i> Submit Stock Request
        </button>
    </div>

    <!-- Product Search Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-white mb-0"><i class="fas fa-search me-2 text-cyan"></i> Stock Availability Lookup</h6>
            <span class="badge bg-slate-800 text-slate-300 border border-slate-700"><?= count($products) ?> Total Items</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="productsDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Available</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td class="fw-bold text-white"><?= htmlspecialchars($p->product_name) ?></td>
                            <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($p->sku) ?></td>
                            <td><span class="badge bg-slate-800 text-slate-200 border border-slate-700"><?= htmlspecialchars($p->category_name) ?></span></td>
                            <td class="fw-semibold text-white">$<?= number_format($p->selling_price, 2) ?></td>
                            <td class="fw-bold fs-6"><?= number_format($p->quantity) ?></td>
                            <td><?= $p->getStockStatusHtml() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Submit Request Modal -->
<div class="modal fade" id="submitRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-paper-plane text-cyan me-2"></i> Submit Item Stock Request</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Stock request submitted successfully!'); bootstrap.Modal.getInstance(document.getElementById('submitRequestModal')).hide();">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Select Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" required>
                            <option value="">Choose item...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (Available: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Requested Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7" required placeholder="e.g. 2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Reason / Department</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" rows="2" placeholder="Enter department or request reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-paper-plane me-1"></i> Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
