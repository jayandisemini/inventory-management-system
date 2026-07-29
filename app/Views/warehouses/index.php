<?php
use App\Core\CSRF;
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Multi-Warehouse & Store Locations</h4>
            <p class="text-slate-400 fs-7 mb-0">Manage physical warehouse branches and distribution facilities.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                <i class="fas fa-warehouse me-1.5"></i> Add New Location
            </button>
        <?php endif; ?>
    </div>

    <!-- Warehouse Cards Grid -->
    <div class="row g-3 mb-4">
        <?php foreach ($warehouses as $wh): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-cyan-subtle text-cyan font-mono px-3 py-1.5 rounded-pill"><?= htmlspecialchars($wh->code) ?></span>
                        <?php if ($userRole === 'Admin'): ?>
                            <div class="dropdown">
                                <button class="btn btn-link text-slate-400 p-0" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border-slate-800 bg-slate-900 fs-7">
                                    <li>
                                        <button class="dropdown-item edit-wh-btn" 
                                                data-id="<?= $wh->warehouse_id ?>" 
                                                data-name="<?= htmlspecialchars($wh->warehouse_name) ?>" 
                                                data-code="<?= htmlspecialchars($wh->code) ?>"
                                                data-manager="<?= htmlspecialchars($wh->manager_name ?? '') ?>"
                                                data-phone="<?= htmlspecialchars($wh->phone ?? '') ?>"
                                                data-location="<?= htmlspecialchars($wh->location ?? '') ?>">
                                            <i class="fas fa-pen me-2 text-cyan"></i> Edit Details
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold text-white mb-2"><?= htmlspecialchars($wh->warehouse_name) ?></h5>
                    <p class="text-slate-400 fs-7 mb-3"><i class="fas fa-location-dot me-2 text-rose"></i><?= htmlspecialchars($wh->location ?? 'No address set') ?></p>
                    <div class="border-top border-slate-800 pt-3 mt-auto d-flex align-items-center justify-content-between fs-8 text-slate-300">
                        <span>Manager: <strong><?= htmlspecialchars($wh->manager_name ?? 'Unassigned') ?></strong></span>
                        <span class="text-slate-400"><i class="fas fa-phone me-1 text-emerald"></i><?= htmlspecialchars($wh->phone ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Add Warehouse -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-warehouse text-cyan me-2"></i> Add Storage Location</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/warehouses/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Warehouse Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" name="warehouse_name" required placeholder="e.g. West Coast Facility">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Location Code <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7 font-mono" name="code" required placeholder="e.g. WH-WEST">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-slate-300 fs-7">Manager Name</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" name="manager_name">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-slate-300 fs-7">Phone Number</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" name="phone">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Address / Location</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" name="location" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-plus me-1"></i> Add Warehouse</button>
                </div>
            </form>
        </div>
    </div>
</div>
