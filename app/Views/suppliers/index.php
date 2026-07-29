<?php
use App\Core\CSRF;
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Supplier Directory</h4>
            <p class="text-slate-400 fs-7 mb-0">Manage vendor contact details, contracts, and supplied items.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                <i class="fas fa-plus me-1.5"></i> Add New Supplier
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="suppliersDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Supplier Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Products Supplied</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td class="fw-bold text-white">
                                <a href="/suppliers/show?id=<?= $s->supplier_id ?>" class="text-decoration-none text-white">
                                    <i class="fas fa-truck text-cyan me-2"></i><?= htmlspecialchars($s->supplier_name) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($s->contact_person ?? 'N/A') ?></td>
                            <td class="fw-mono fs-7"><i class="fas fa-phone me-1 text-slate-400"></i><?= htmlspecialchars($s->phone ?? 'N/A') ?></td>
                            <td class="fs-7"><i class="fas fa-envelope me-1 text-slate-400"></i><?= htmlspecialchars($s->email ?? 'N/A') ?></td>
                            <td class="fs-7 text-slate-400"><?= htmlspecialchars($s->address ?? 'N/A') ?></td>
                            <td><span class="badge bg-slate-800 text-slate-300 border border-slate-700"><?= $s->product_count ?> Product(s)</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="/suppliers/show?id=<?= $s->supplier_id ?>" class="btn btn-slate-800 text-white border border-slate-700" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                                        <button class="btn btn-outline-cyan edit-sup-btn" 
                                                data-id="<?= $s->supplier_id ?>" 
                                                data-name="<?= htmlspecialchars($s->supplier_name) ?>"
                                                data-person="<?= htmlspecialchars($s->contact_person ?? '') ?>"
                                                data-phone="<?= htmlspecialchars($s->phone ?? '') ?>"
                                                data-email="<?= htmlspecialchars($s->email ?? '') ?>"
                                                data-address="<?= htmlspecialchars($s->address ?? '') ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn btn-outline-rose delete-sup-btn" data-id="<?= $s->supplier_id ?>" data-name="<?= htmlspecialchars($s->supplier_name) ?>" data-count="<?= $s->product_count ?>">
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

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-truck-field text-cyan me-2"></i> Add Supplier Profile</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/suppliers/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label fw-semibold text-slate-300 fs-7">Supplier / Company Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="supplier_name" name="supplier_name" required placeholder="e.g. Global Tech Supplies Ltd">
                    </div>
                    <div class="mb-3">
                        <label for="contact_person" class="form-label fw-semibold text-slate-300 fs-7">Contact Person</label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="contact_person" name="contact_person" placeholder="e.g. Sarah Jenkins">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="phone" class="form-label fw-semibold text-slate-300 fs-7">Phone Number</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" id="phone" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-6">
                            <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Email Address</label>
                            <input type="email" class="form-control bg-slate-950 text-white fs-7" id="email" name="email" placeholder="sales@supplier.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold text-slate-300 fs-7">Physical Address</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="address" name="address" rows="2" placeholder="Street, City, State, ZIP..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-check me-1"></i> Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-pen text-cyan me-2"></i> Edit Supplier Profile</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/suppliers/update" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="editSupId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="editSupName" class="form-label fw-semibold text-slate-300 fs-7">Supplier Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="editSupName" name="supplier_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSupPerson" class="form-label fw-semibold text-slate-300 fs-7">Contact Person</label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="editSupPerson" name="contact_person">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="editSupPhone" class="form-label fw-semibold text-slate-300 fs-7">Phone Number</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" id="editSupPhone" name="phone">
                        </div>
                        <div class="col-6">
                            <label for="editSupEmail" class="form-label fw-semibold text-slate-300 fs-7">Email</label>
                            <input type="email" class="form-control bg-slate-950 text-white fs-7" id="editSupEmail" name="email">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editSupAddress" class="form-label fw-semibold text-slate-300 fs-7">Address</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="editSupAddress" name="address" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-save me-1"></i> Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Supplier Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-rose"><i class="fas fa-triangle-exclamation me-2"></i> Delete Supplier</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/suppliers/delete" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="deleteSupId">
                <div class="modal-body p-4 text-center">
                    <p class="mb-1 text-slate-300">Delete supplier <strong id="deleteSupName" class="text-rose"></strong>?</p>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-rose"><i class="fas fa-trash-can me-1"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
