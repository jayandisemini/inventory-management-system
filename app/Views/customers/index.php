<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-users text-cyan me-2"></i> Customer Directory & Client CRM</h4>
            <p class="text-slate-400 fs-7 mb-0">Manage customer profiles, billing contacts, corporate accounts, and sales invoice history.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                <i class="fas fa-user-plus me-1.5"></i> Add New Customer
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

    <!-- Customer Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="customersDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Cust ID</th>
                        <th>Customer Name</th>
                        <th>Company Name</th>
                        <th>Email Contact</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold">#CUST-<?= str_pad($c->customer_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td class="fw-bold text-white"><?= htmlspecialchars($c->customer_name) ?></td>
                            <td class="fw-semibold text-slate-300"><?= htmlspecialchars($c->company_name ?? 'Individual') ?></td>
                            <td>
                                <?php if ($c->email): ?>
                                    <a href="mailto:<?= htmlspecialchars($c->email) ?>" class="text-cyan text-decoration-none"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($c->email) ?></a>
                                <?php else: ?>
                                    <span class="text-slate-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-mono text-slate-300"><?= htmlspecialchars($c->phone ?? 'N/A') ?></td>
                            <td><?= $c->getStatusBadgeHtml() ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($c->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Customer -->
<div class="modal fade" id="newCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-user-plus text-cyan me-2"></i> Register New Customer</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/customers/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="cust_name" class="form-label fw-semibold text-slate-300 fs-7">Customer / Contact Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="cust_name" name="customer_name" placeholder="e.g. Sarah Jenkins" required>
                    </div>

                    <div class="mb-3">
                        <label for="cust_company" class="form-label fw-semibold text-slate-300 fs-7">Company / Organization</label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="cust_company" name="company_name" placeholder="e.g. Apex Global Solutions">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="cust_email" class="form-label fw-semibold text-slate-300 fs-7">Email Address</label>
                            <input type="email" class="form-control bg-slate-950 text-white fs-7" id="cust_email" name="email" placeholder="sarah@apex.com">
                        </div>
                        <div class="col-6">
                            <label for="cust_phone" class="form-label fw-semibold text-slate-300 fs-7">Phone Number</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" id="cust_phone" name="phone" placeholder="+1 (555) 019-283">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cust_address" class="form-label fw-semibold text-slate-300 fs-7">Billing / Delivery Address</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="cust_address" name="address" rows="2" placeholder="Enter corporate or shipping address"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-check me-1"></i> Register Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
