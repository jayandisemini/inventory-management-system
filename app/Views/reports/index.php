<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">Business Intelligence & Executive Reports</h4>
            <p class="text-muted fs-7 mb-0">Generate inventory valuations, stock movement logs, low stock alerts, and supplier reports.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/reports/print?type=<?= $currentType ?>&<?= http_build_query($filters) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-3">
                <i class="fas fa-print me-1.5"></i> Print / PDF Export
            </a>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <ul class="nav nav-pills bg-white p-2 rounded-4 border shadow-xs mb-4 gap-1">
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'inventory_value' ? 'active' : '' ?>" href="/reports?type=inventory_value">
                <i class="fas fa-sack-dollar me-1.5"></i> Inventory Valuation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'low_stock' ? 'active' : '' ?>" href="/reports?type=low_stock">
                <i class="fas fa-triangle-exclamation me-1.5"></i> Low & Out of Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'movements' ? 'active' : '' ?>" href="/reports?type=movements">
                <i class="fas fa-list-check me-1.5"></i> Stock Movement History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'suppliers' ? 'active' : '' ?>" href="/reports?type=suppliers">
                <i class="fas fa-truck me-1.5"></i> Supplier Directory Report
            </a>
        </li>
    </ul>

    <!-- Report Card Content -->
    <div class="card border-0 shadow-xs rounded-4 bg-white p-4" id="reportPrintArea">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <div>
                <h5 class="fw-bold text-slate-900 mb-0"><?= htmlspecialchars($reportData['title'] ?? 'Report') ?></h5>
                <small class="text-muted">Generated on <?= date('F j, Y - H:i:s T') ?></small>
            </div>
            <div class="text-end">
                <span class="badge bg-slate-900 text-white px-3 py-2 fs-7">Official SIMS Audit Document</span>
            </div>
        </div>

        <?php if ($currentType === 'inventory_value'): ?>
            <!-- Inventory Valuation Summary Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-light p-3 rounded-3 border text-center">
                        <span class="text-muted fs-8 text-uppercase fw-bold">Total Stock Cost</span>
                        <h4 class="fw-bold text-slate-900 mb-0 mt-1">$<?= number_format($reportData['total_cost_valuation'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light p-3 rounded-3 border text-center">
                        <span class="text-muted fs-8 text-uppercase fw-bold">Retail Market Value</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">$<?= number_format($reportData['total_retail_valuation'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light p-3 rounded-3 border text-center">
                        <span class="text-muted fs-8 text-uppercase fw-bold">Unrealized Gross Margin</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">$<?= number_format($reportData['potential_profit'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light p-3 rounded-3 border text-center">
                        <span class="text-muted fs-8 text-uppercase fw-bold">Total Units In Stock</span>
                        <h4 class="fw-bold text-slate-900 mb-0 mt-1"><?= number_format($reportData['total_items_count'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle fs-7 mb-0">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                            <th>Selling Price</th>
                            <th>Total Retail Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['products'] as $p): ?>
                            <tr>
                                <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                <td class="fw-bold text-slate-900"><?= htmlspecialchars($p->product_name) ?></td>
                                <td><?= htmlspecialchars($p->category_name) ?></td>
                                <td class="fw-bold"><?= $p->quantity ?></td>
                                <td>$<?= number_format($p->unit_price, 2) ?></td>
                                <td class="fw-semibold">$<?= number_format($p->quantity * $p->unit_price, 2) ?></td>
                                <td>$<?= number_format($p->selling_price, 2) ?></td>
                                <td class="fw-bold text-success">$<?= number_format($p->quantity * $p->selling_price, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'low_stock'): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle fs-7 mb-0">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Current Stock</th>
                            <th>Min Stock Threshold</th>
                            <th>Stock Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reportData['products'])): ?>
                            <tr><td colspan="7" class="text-center py-4 text-success"><i class="fas fa-check-circle me-1"></i> All products are sufficiently stocked.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reportData['products'] as $p): ?>
                                <tr>
                                    <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                    <td class="fw-bold text-slate-900"><?= htmlspecialchars($p->product_name) ?></td>
                                    <td><?= htmlspecialchars($p->category_name) ?></td>
                                    <td><?= htmlspecialchars($p->supplier_name) ?></td>
                                    <td class="fw-bold fs-6 <?= $p->quantity <= 0 ? 'text-danger' : 'text-warning' ?>"><?= $p->quantity ?></td>
                                    <td><?= $p->min_stock_level ?></td>
                                    <td><?= $p->getStockStatusHtml() ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'movements'): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle fs-7 mb-0">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th>Movement ID</th>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Movement Type</th>
                            <th>Qty</th>
                            <th>Operator</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['movements'] as $m): ?>
                            <tr>
                                <td class="fw-mono text-muted fs-8">#LOG-<?= str_pad($m->movement_id, 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="fw-bold text-slate-900"><?= htmlspecialchars($m->product_name) ?></td>
                                <td class="fw-mono text-muted fs-8"><?= htmlspecialchars($m->sku) ?></td>
                                <td><?= $m->getTypeBadgeHtml() ?></td>
                                <td class="fw-bold"><?= $m->quantity ?></td>
                                <td><?= htmlspecialchars($m->user_name) ?></td>
                                <td class="text-muted fs-8"><?= date('Y-m-d H:i', strtotime($m->created_at)) ?></td>
                                <td class="text-muted fs-8"><?= htmlspecialchars($m->reference_note ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'suppliers'): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle fs-7 mb-0">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th>Supplier Company</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Assigned Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['suppliers'] as $s): ?>
                            <tr>
                                <td class="fw-bold text-slate-900"><?= htmlspecialchars($s->supplier_name) ?></td>
                                <td><?= htmlspecialchars($s->contact_person ?? 'N/A') ?></td>
                                <td class="fw-mono"><?= htmlspecialchars($s->phone ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s->email ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s->address ?? 'N/A') ?></td>
                                <td class="fw-bold"><?= $s->product_count ?> Item(s)</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
