<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1"><i class="fas fa-microchip text-cyan me-2"></i> Bill of Materials (BOM) & Kit Assemblies</h4>
            <p class="text-slate-400 fs-7 mb-0">Assemble bundled finished goods from raw inventory components with auto-deduction logic.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newAssemblyModal">
                <i class="fas fa-plus me-1.5"></i> Execute Kit Assembly
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

    <!-- Assembly Data Table -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 fs-7" id="assembliesDataTable">
                <thead>
                    <tr class="text-slate-400">
                        <th>Assembly Code</th>
                        <th>Finished Kit Product</th>
                        <th>Raw Component Used</th>
                        <th>Component Qty / Unit</th>
                        <th>Assembled Units</th>
                        <th>Status</th>
                        <th>Operator</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assemblies as $a): ?>
                        <tr>
                            <td class="fw-mono text-cyan fw-bold"><?= htmlspecialchars($a->assembly_code) ?></td>
                            <td>
                                <div class="fw-bold text-white"><?= htmlspecialchars($a->parent_product_name) ?></div>
                                <small class="text-slate-400 font-mono fs-8">SKU: <?= htmlspecialchars($a->parent_sku) ?></small>
                            </td>
                            <td>
                                <div class="fw-bold text-slate-300"><?= htmlspecialchars($a->component_product_name) ?></div>
                                <small class="text-slate-400 font-mono fs-8">SKU: <?= htmlspecialchars($a->component_sku) ?></small>
                            </td>
                            <td class="text-slate-400"><?= $a->required_qty ?> unit(s)</td>
                            <td class="fw-bold text-emerald fs-6">+<?= number_format($a->assembled_units) ?> Kit(s)</td>
                            <td><?= $a->getStatusBadgeHtml() ?></td>
                            <td class="text-slate-300"><?= htmlspecialchars($a->user_name) ?></td>
                            <td class="text-slate-400 fs-8"><?= date('Y-m-d H:i', strtotime($a->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Execute Assembly -->
<div class="modal fade" id="newAssemblyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-microchip text-cyan me-2"></i> Execute Kit Assembly</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/assemblies/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="parent_prod" class="form-label fw-semibold text-slate-300 fs-7">Finished Kit Product (Output) <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="parent_prod" name="parent_product_id" required>
                            <option value="">Select finished item to assemble...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (SKU: <?= htmlspecialchars($p->sku) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comp_prod" class="form-label fw-semibold text-slate-300 fs-7">Raw Component Product (Input) <span class="text-rose">*</span></label>
                        <select class="form-select bg-slate-950 text-white fs-7" id="comp_prod" name="component_product_id" required>
                            <option value="">Select raw component to deduct...</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p->product_id ?>"><?= htmlspecialchars($p->product_name) ?> (Available: <?= $p->quantity ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="req_qty" class="form-label fw-semibold text-slate-300 fs-7">Raw Components / Kit <span class="text-rose">*</span></label>
                            <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7" id="req_qty" name="required_qty" value="1" required>
                        </div>
                        <div class="col-6">
                            <label for="asm_units" class="form-label fw-semibold text-slate-300 fs-7">Units to Assemble <span class="text-rose">*</span></label>
                            <input type="number" min="1" class="form-control bg-slate-950 text-emerald fw-bold fs-7" id="asm_units" name="assembled_units" value="5" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-cubes me-1"></i> Assemble Kits</button>
                </div>
            </form>
        </div>
    </div>
</div>
