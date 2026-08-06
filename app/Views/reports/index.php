<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 card border-0 rounded-4 p-4">
        <div>
            <h4 class="fw-bold text-theme-main mb-1">Business Intelligence & Executive Reports</h4>
            <p class="text-theme-muted fs-7 mb-0">Generate inventory valuations, stock movement logs, sales revenue, batch risk, and supplier procurement analytics.</p>
        </div>
        <div class="d-flex gap-2">
            <?php
                $csvExportUrl = '/reports/export-inventory-csv';
                if ($currentType === 'movements') $csvExportUrl = '/reports/export-movements-csv';
                elseif ($currentType === 'sales_revenue') $csvExportUrl = '/reports/export-sales-csv';
                elseif ($currentType === 'batch_expiry') $csvExportUrl = '/reports/export-batch-expiry-csv';
                elseif ($currentType === 'supplier_procurement') $csvExportUrl = '/reports/export-procurement-csv';
            ?>
            <a href="<?= $csvExportUrl ?>" class="btn btn-emerald btn-sm rounded-3 fw-semibold">
                <i class="fas fa-file-csv me-1.5"></i> Export CSV
            </a>
            <a href="/reports/print?type=<?= $currentType ?>&<?= http_build_query($filters) ?>" target="_blank" class="btn btn-outline-cyan btn-sm rounded-3">
                <i class="fas fa-print me-1.5"></i> Print / PDF Export
            </a>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <ul class="nav nav-pills card flex-row p-2 rounded-4 mb-4 gap-1 flex-wrap">
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'inventory_value' ? 'active' : '' ?>" href="/reports?type=inventory_value">
                <i class="fas fa-sack-dollar me-1.5"></i> Inventory Valuation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'sales_revenue' ? 'active' : '' ?>" href="/reports?type=sales_revenue">
                <i class="fas fa-chart-line me-1.5"></i> Sales & Revenue
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'batch_expiry' ? 'active' : '' ?>" href="/reports?type=batch_expiry">
                <i class="fas fa-hourglass-half me-1.5"></i> Batch Expiry Risk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'supplier_procurement' ? 'active' : '' ?>" href="/reports?type=supplier_procurement">
                <i class="fas fa-cart-shopping me-1.5"></i> Supplier Procurement
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'low_stock' ? 'active' : '' ?>" href="/reports?type=low_stock">
                <i class="fas fa-triangle-exclamation me-1.5"></i> Low & Out of Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'movements' ? 'active' : '' ?>" href="/reports?type=movements">
                <i class="fas fa-list-check me-1.5"></i> Movement Log
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fs-7 <?= $currentType === 'suppliers' ? 'active' : '' ?>" href="/reports?type=suppliers">
                <i class="fas fa-truck me-1.5"></i> Supplier Directory
            </a>
        </li>
    </ul>

    <!-- Report Card Content -->
    <div class="card border-0 rounded-4 p-4" id="reportPrintArea">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <div>
                <h5 class="fw-bold text-theme-main mb-0"><?= htmlspecialchars($reportData['title'] ?? 'Report') ?></h5>
                <small class="text-theme-muted">Generated on <?= date('F j, Y - H:i:s T') ?></small>
            </div>
            <div class="text-end">
                <span class="badge bg-body-secondary text-theme-muted border px-3 py-2 fs-7">Official SIMS Audit Document</span>
            </div>
        </div>

        <?php if ($currentType === 'inventory_value'): ?>
            <!-- Inventory Valuation Summary Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Stock Cost</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1">Rs. <?= number_format($reportData['total_cost_valuation'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Retail Market Value</span>
                        <h4 class="fw-bold text-emerald mb-0 mt-1">Rs. <?= number_format($reportData['total_retail_valuation'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Unrealized Gross Margin</span>
                        <h4 class="fw-bold text-cyan mb-0 mt-1">Rs. <?= number_format($reportData['potential_profit'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Units In Stock</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1"><?= number_format($reportData['total_items_count'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
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
                                <td class="fw-mono text-theme-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($p->product_name) ?></td>
                                <td><?= htmlspecialchars($p->category_name) ?></td>
                                <td class="fw-bold text-theme-main"><?= $p->quantity ?></td>
                                <td>Rs. <?= number_format($p->unit_price, 2) ?></td>
                                <td class="fw-semibold text-theme-main">Rs. <?= number_format($p->quantity * $p->unit_price, 2) ?></td>
                                <td>Rs. <?= number_format($p->selling_price, 2) ?></td>
                                <td class="fw-bold text-emerald">Rs. <?= number_format($p->quantity * $p->selling_price, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'sales_revenue'): ?>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Sales Revenue</span>
                        <h4 class="fw-bold text-emerald mb-0 mt-1">Rs. <?= number_format($reportData['total_revenue'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Invoices</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1"><?= $reportData['total_orders'] ?? 0 ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Average Order Value</span>
                        <h4 class="fw-bold text-cyan mb-0 mt-1">Rs. <?= number_format($reportData['avg_order_value'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Paid Orders Ratio</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1"><?= $reportData['paid_count'] ?? 0 ?> / <?= $reportData['total_orders'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
                            <th>SO Number</th>
                            <th>Customer</th>
                            <th>Total Billed</th>
                            <th>Payment Status</th>
                            <th>Sales Agent</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['orders'] as $so): ?>
                            <tr>
                                <td class="fw-mono text-cyan fs-8"><?= htmlspecialchars($so->order_number) ?></td>
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($so->customer_name) ?></td>
                                <td class="fw-bold text-emerald">Rs. <?= number_format($so->total_amount, 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= strtolower($so->payment_status ?? '') === 'paid' ? 'success' : 'warning' ?>">
                                        <?= htmlspecialchars($so->payment_status) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($so->user_name) ?></td>
                                <td class="text-theme-muted fs-8"><?= date('Y-m-d H:i', strtotime($so->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'batch_expiry'): ?>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Batches Expiring Soon</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1"><?= $reportData['expiring_soon_count'] ?? 0 ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Expired Batches</span>
                        <h4 class="fw-bold text-rose mb-0 mt-1"><?= $reportData['expired_count'] ?? 0 ?></h4>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">At-Risk Quantity (Units)</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1"><?= number_format($reportData['at_risk_qty'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
                            <th>Batch Number</th>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['batches'] as $b): ?>
                            <tr>
                                <td class="fw-mono text-cyan fs-8"><?= htmlspecialchars($b->batch_number) ?></td>
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($b->product_name) ?></td>
                                <td class="fw-mono text-theme-muted fs-8"><?= htmlspecialchars($b->sku) ?></td>
                                <td class="fw-bold text-theme-main"><?= $b->quantity ?></td>
                                <td class="fw-mono"><?= $b->expiry_date ?></td>
                                <td>
                                    <span class="badge bg-<?= $b->status === 'Expired' ? 'danger' : ($b->status === 'Expiring Soon' ? 'warning' : 'success') ?>">
                                        <?= $b->status ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'supplier_procurement'): ?>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Procurement Spend</span>
                        <h4 class="fw-bold text-emerald mb-0 mt-1">Rs. <?= number_format($reportData['total_spend'] ?? 0, 2) ?></h4>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Total Purchase Orders</span>
                        <h4 class="fw-bold text-theme-main mb-0 mt-1"><?= $reportData['total_pos'] ?? 0 ?></h4>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="bg-surface-elevated p-3 rounded-3 border text-center">
                        <span class="text-theme-muted fs-8 text-uppercase fw-bold">Received Orders</span>
                        <h4 class="fw-bold text-cyan mb-0 mt-1"><?= $reportData['received_count'] ?? 0 ?> / <?= $reportData['total_pos'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
                            <th>PO Number</th>
                            <th>Supplier Name</th>
                            <th>Total Spend</th>
                            <th>Status</th>
                            <th>Procurement Officer</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['purchase_orders'] as $po): ?>
                            <tr>
                                <td class="fw-mono text-cyan fs-8"><?= htmlspecialchars($po->po_number) ?></td>
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($po->supplier_name) ?></td>
                                <td class="fw-bold text-emerald">Rs. <?= number_format($po->total_amount, 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= strtolower($po->status ?? '') === 'received' ? 'success' : 'info' ?>">
                                        <?= htmlspecialchars($po->status) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($po->user_name) ?></td>
                                <td class="text-theme-muted fs-8"><?= date('Y-m-d H:i', strtotime($po->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'low_stock'): ?>
            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
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
                            <tr><td colspan="7" class="text-center py-4 text-emerald"><i class="fas fa-check-circle me-1"></i> All products are sufficiently stocked.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reportData['products'] as $p): ?>
                                <tr>
                                    <td class="fw-mono text-theme-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                    <td class="fw-bold text-theme-main"><?= htmlspecialchars($p->product_name) ?></td>
                                    <td><?= htmlspecialchars($p->category_name) ?></td>
                                    <td><?= htmlspecialchars($p->supplier_name) ?></td>
                                    <td class="fw-bold fs-6 <?= $p->quantity <= 0 ? 'text-rose' : 'text-warning' ?>"><?= $p->quantity ?></td>
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
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
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
                                <td class="fw-mono text-cyan fs-8">#LOG-<?= str_pad($m->movement_id, 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($m->product_name) ?></td>
                                <td class="fw-mono text-theme-muted fs-8"><?= htmlspecialchars($m->sku) ?></td>
                                <td><?= $m->getTypeBadgeHtml() ?></td>
                                <td class="fw-bold text-theme-main"><?= $m->quantity ?></td>
                                <td><?= htmlspecialchars($m->user_name) ?></td>
                                <td class="text-theme-muted fs-8"><?= date('Y-m-d H:i', strtotime($m->created_at)) ?></td>
                                <td class="text-theme-muted fs-8"><?= htmlspecialchars($m->reference_note ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($currentType === 'suppliers'): ?>
            <div class="table-responsive">
                <table class="table align-middle fs-7 mb-0">
                    <thead>
                        <tr class="text-theme-muted">
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
                                <td class="fw-bold text-theme-main"><?= htmlspecialchars($s->supplier_name) ?></td>
                                <td><?= htmlspecialchars($s->contact_person ?? 'N/A') ?></td>
                                <td class="fw-mono"><?= htmlspecialchars($s->phone ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s->email ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s->address ?? 'N/A') ?></td>
                                <td class="fw-bold text-theme-main"><?= $s->product_count ?> Item(s)</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
