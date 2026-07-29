<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-truck-ramp-box text-cyan me-2"></i> Inter-Warehouse Stock Transfer Orders</h4>
            <p class="text-slate-400 fs-7 mb-0">Issue stock transfers between physical branches, distribution hubs, and retail outlets.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newTransferModal">
                <i class="fas fa-plus me-1.5"></i> New Stock Transfer Order
            </button>
        <?php endif; ?>
    </div>

    <!-- Flash Alert Messages -->
    <?php foreach ($flashes as $flash): ?>
        <?php if (!empty($flash['value'])): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show fs-7 border-0 rounded-3 mb-4" role="alert">
                <?= $flash['value'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Transfer Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="transfersDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Transfer Code</th>
                        <th>Product Item</th>
                        <th>Source Location</th>
                        <th>Destination Location</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Transacted By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transfers as $t): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($t->transfer_code) ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($t->product_name) ?></div>
                                <small class="text-slate-400 font-mono fs-8">SKU: <?= htmlspecialchars($t->sku) ?></small>
                            </td>
                            <td class="fw-semibold text-slate-300"><i class="fas fa-warehouse text-amber me-1"></i> <?= htmlspecialchars($t->source_warehouse_name) ?></td>
                            <td class="fw-semibold text-emerald"><i class="fas fa-location-dot me-1"></i> <?= htmlspecialchars($t->dest_warehouse_name) ?></td>
                            <td class="fw-bold text-white fs-6"><?= number_format($t->quantity) ?></td>
                            <td><?= $t->getStatusBadgeHtml() ?></td>
                            <td class="text-slate-300"><?= htmlspecialchars($t->user_name) ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($t->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create Transfer -->
<div class="modal fade" id="newTransferModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-truck-ramp-box text-cyan me-2"></i> Issue Stock Transfer</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/transfers/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="trf_prod" class="form-label fw-semibold text-slate-300 fs-7">Select Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="trf_prod" name="product_id" required>
                            <option value="">Choose item to transfer...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (Available: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="src_wh" class="form-label fw-semibold text-slate-300 fs-7">Source Warehouse <span class="text-rose">*</span></label>
                            <select class="form-select bg-slate-950 text-white fs-7" id="src_wh" name="source_warehouse_id" required>
                                <option value="">From location...</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh->warehouse_id ?>"><?= htmlspecialchars($wh->warehouse_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="dest_wh" class="form-label fw-semibold text-slate-300 fs-7">Destination Branch <span class="text-rose">*</span></label>
                            <select class="form-select bg-slate-950 text-white fs-7" id="dest_wh" name="dest_warehouse_id" required>
                                <option value="">To location...</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh->warehouse_id ?>"><?= htmlspecialchars($wh->warehouse_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="trf_qty" class="form-label fw-semibold text-slate-300 fs-7">Transfer Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="1" class="form-control bg-slate-950 text-emerald fw-bold fs-7" id="trf_qty" name="quantity" placeholder="e.g. 20" required>
                    </div>

                    <div class="mb-3">
                        <label for="trf_notes" class="form-label fw-semibold text-slate-300 fs-7">Notes / Transport Order</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="trf_notes" name="notes" rows="2" placeholder="e.g. Dispatch via Logistics Truck #882"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-truck-fast me-1"></i> Issue Transfer Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
