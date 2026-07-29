<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Staff Requisitions & Request Approvals</h4>
            <p class="text-slate-400 fs-7 mb-0">Staff members log item requests; Managers and Admins review and approve stock dispatches.</p>
        </div>
        <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newRequestModal">
            <i class="fas fa-paper-plane me-1.5"></i> Submit New Stock Request
        </button>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="stockRequestsDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Req ID</th>
                        <th>Product Requested</th>
                        <th>Qty</th>
                        <th>Requested By</th>
                        <th>Reason / Notes</th>
                        <th>Status</th>
                        <th>Date</th>
                        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                            <th class="text-end">Approval Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold">#REQ-<?= str_pad($req->request_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($req->product_name) ?></div>
                                <small class="text-slate-400 fs-8"><?= htmlspecialchars($req->sku) ?></small>
                            </td>
                            <td class="fw-bold text-white fs-6"><?= $req->quantity ?></td>
                            <td><?= htmlspecialchars($req->user_name) ?></td>
                            <td class="text-slate-300"><?= htmlspecialchars($req->reason ?? 'Internal requisition') ?></td>
                            <td><?= $req->getStatusBadgeHtml() ?></td>
                            <td class="text-slate-400 fs-8"><?= date('M d, H:i', strtotime($req->created_at)) ?></td>

                            <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                                <td class="text-end">
                                    <?php if ($req->status === 'Pending'): ?>
                                        <div class="btn-group btn-group-sm">
                                            <form action="/stock-requests/approve" method="POST" class="d-inline">
                                                <?= CSRF::field() ?>
                                                <input type="hidden" name="id" value="<?= $req->request_id ?>">
                                                <button type="submit" class="btn btn-emerald me-1 rounded-2" title="Approve Request">
                                                    <i class="fas fa-check me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="/stock-requests/reject" method="POST" class="d-inline">
                                                <?= CSRF::field() ?>
                                                <input type="hidden" name="id" value="<?= $req->request_id ?>">
                                                <button type="submit" class="btn btn-rose rounded-2" title="Reject Request">
                                                    <i class="fas fa-xmark me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-slate-400 fs-8">Processed by <?= htmlspecialchars($req->action_by_name ?? 'System') ?></small>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Submit Request -->
<div class="modal fade" id="newRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-paper-plane text-cyan me-2"></i> Submit Stock Request</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/stock-requests/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="req_prod" class="form-label fw-semibold text-slate-300 fs-7">Select Product <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="req_prod" name="product_id" required>
                            <option value="">Select product...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (In Stock: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="req_qty" class="form-label fw-semibold text-slate-300 fs-7">Requested Quantity <span class="text-rose">*</span></label>
                        <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7" id="req_qty" name="quantity" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="req_reason" class="form-label fw-semibold text-slate-300 fs-7">Reason / Department</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="req_reason" name="reason" rows="2" placeholder="e.g. Assigned to engineering department"></textarea>
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
