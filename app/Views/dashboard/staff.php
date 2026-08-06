<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <!-- Staff Header Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 role-banner-staff p-4 rounded-4 shadow-sm text-white">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0"><i class="fas fa-terminal me-2 text-cyan"></i> Staff Inventory Request & Search Terminal</h4>
                <span class="badge bg-primary-subtle text-blue rounded-pill px-2.5 py-1 fs-8 fw-bold border border-blue">
                    <i class="fas fa-eye me-1"></i> STAFF WORKSTATION MODE
                </span>
            </div>
            <p class="text-slate-400 fs-7 mb-0">Browse live product availability, check stock quantities, and submit stock requisitions for approval.</p>
        </div>
        <div>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#submitRequestModal">
                <i class="fas fa-paper-plane me-1.5"></i> Submit Stock Request
            </button>
        </div>
    </div>

    <!-- Staff Quick Stat Widgets -->
    <div class="row g-3 mb-4">
        <!-- Available Products -->
        <div class="col-12 col-sm-4">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-theme-muted fs-8 fw-bold text-uppercase">Catalog Items</span>
                        <h3 class="fw-bold text-theme-main mb-0 mt-1"><?= count($products ?? []) ?></h3>
                    </div>
                    <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-boxes-stacked fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Requisitions Count -->
        <div class="col-12 col-sm-4">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-theme-muted fs-8 fw-bold text-uppercase">My Requisitions</span>
                        <h3 class="fw-bold text-blue mb-0 mt-1"><?= count($myRequests ?? []) ?></h3>
                    </div>
                    <div class="metric-icon bg-primary-subtle text-blue rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-paper-plane fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Count -->
        <div class="col-12 col-sm-4">
            <div class="card card-metric border-0 rounded-4 p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-theme-muted fs-8 fw-bold text-uppercase">Approved Requests</span>
                        <?php
                            $approvedCount = count(array_filter($myRequests ?? [], fn($r) => $r->status === 'Approved'));
                        ?>
                        <h3 class="fw-bold text-emerald mb-0 mt-1"><?= $approvedCount ?></h3>
                    </div>
                    <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-circle-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Recent Requisitions Tracker & Stock Search Grid -->
    <div class="row g-3 mb-4">
        <!-- My Requisitions Widget -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-list-check me-2 text-cyan"></i> My Requisitions Status</h6>
                        <small class="text-theme-muted fs-8">Track status of your submitted stock requests</small>
                    </div>
                    <button class="btn btn-cyan btn-xs rounded-2" data-bs-toggle="modal" data-bs-target="#submitRequestModal">
                        <i class="fas fa-plus me-1"></i> New Request
                    </button>
                </div>

                <?php if (empty($myRequests)): ?>
                    <div class="text-center py-4 my-auto text-theme-muted">
                        <i class="fas fa-paper-plane fs-3 text-cyan mb-2"></i>
                        <p class="mb-0 fs-7">You haven't submitted any requisitions yet.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach (array_slice($myRequests, 0, 5) as $req): ?>
                            <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-theme-main fs-7"><?= htmlspecialchars($req->product_name) ?></div>
                                    <small class="text-theme-muted fs-8">Requested Qty: <strong class="text-cyan"><?= $req->quantity ?></strong> &bull; <?= date('M d, H:i', strtotime($req->created_at)) ?></small>
                                </div>
                                <div>
                                    <?php if ($req->status === 'Approved'): ?>
                                        <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2.5 py-1 fs-8"><i class="fas fa-check me-1"></i> Approved</span>
                                    <?php elseif ($req->status === 'Rejected'): ?>
                                        <span class="badge bg-rose-subtle text-rose rounded-pill px-2.5 py-1 fs-8"><i class="fas fa-times me-1"></i> Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-amber rounded-pill px-2.5 py-1 fs-8"><i class="fas fa-clock me-1"></i> Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Availability Search Table -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-theme-main mb-0"><i class="fas fa-boxes-stacked me-2 text-blue"></i> Live Stock Availability Directory</h6>
                        <small class="text-theme-muted fs-8">Instant stock level lookup & item specifications</small>
                    </div>
                    <span class="badge bg-cyan-subtle text-cyan border border-cyan-subtle"><?= count($products ?? []) ?> Items</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7" id="productsDataTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="fw-bold text-theme-main"><?= htmlspecialchars($p->product_name) ?></td>
                                    <td class="fw-mono text-theme-muted fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                    <td><span class="badge bg-primary-subtle text-blue"><?= htmlspecialchars($p->category_name) ?></span></td>
                                    <td class="fw-bold fs-6"><?= number_format($p->quantity) ?></td>
                                    <td><?= $p->getStockStatusHtml() ?></td>
                                    <td>
                                        <button class="btn btn-cyan btn-xs rounded-2 select-req-btn" data-id="<?= $p->product_id ?>" data-name="<?= htmlspecialchars($p->product_name) ?>" data-bs-toggle="modal" data-bs-target="#submitRequestModal">
                                            <i class="fas fa-paper-plane me-1"></i> Request
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Submit Request Modal -->
<div class="modal fade" id="submitRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-2xl">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-theme-main"><i class="fas fa-paper-plane text-cyan me-2"></i> Submit Item Stock Requisition</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/stock-requests/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-theme-main fs-7">Select Product <span class="text-rose">*</span></label>
                        <select name="product_id" id="modalProductSelect" class="form-select fs-7" required>
                            <option value="">Choose item...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (Available: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-theme-main fs-7">Requested Quantity <span class="text-rose">*</span></label>
                        <input type="number" name="quantity" min="1" class="form-control fs-7" required placeholder="e.g. 2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-theme-main fs-7">Reason / Department Notes</label>
                        <textarea name="reason" class="form-control fs-7" rows="2" placeholder="Enter department or requisition reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-paper-plane me-1"></i> Submit Requisition</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-req-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const select = document.getElementById('modalProductSelect');
            if (select) select.value = id;
        });
    });
});
</script>
