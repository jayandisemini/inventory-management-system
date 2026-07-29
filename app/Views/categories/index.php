<?php
use App\Core\CSRF;
$userRole = $_SESSION['user']['role_name'] ?? 'Staff';
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">Product Categories</h4>
            <p class="text-slate-400 fs-7 mb-0">Organize catalog items by industrial categories.</p>
        </div>
        <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
            <button class="btn btn-cyan btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="fas fa-plus me-1.5"></i> Create New Category
            </button>
        <?php endif; ?>
    </div>

    <div class="row g-3">
        <?php foreach ($categories as $cat): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-cyan-subtle text-cyan rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="fas fa-folder fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-white mb-0"><?= htmlspecialchars($cat->category_name) ?></h6>
                        </div>
                        <span class="badge bg-slate-800 text-slate-300 border border-slate-700"><?= $cat->product_count ?> Item(s)</span>
                    </div>

                    <p class="text-slate-400 fs-7 mb-3 flex-grow-1"><?= htmlspecialchars($cat->description ?? 'No description set.') ?></p>

                    <?php if (in_array($userRole, ['Admin', 'Inventory Manager'])): ?>
                        <div class="d-flex justify-content-end gap-2 border-top border-slate-800 pt-2 mt-auto">
                            <button class="btn btn-xs btn-outline-cyan edit-cat-btn" data-id="<?= $cat->category_id ?>" data-name="<?= htmlspecialchars($cat->category_name) ?>" data-desc="<?= htmlspecialchars($cat->description ?? '') ?>">
                                <i class="fas fa-pen me-1"></i> Edit
                            </button>
                            <button class="btn btn-xs btn-outline-rose delete-cat-btn" data-id="<?= $cat->category_id ?>" data-name="<?= htmlspecialchars($cat->category_name) ?>" data-count="<?= $cat->product_count ?>">
                                <i class="fas fa-trash-can me-1"></i> Delete
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-folder-plus text-cyan me-2"></i> Create Category</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/categories/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-semibold text-slate-300 fs-7">Category Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="category_name" name="category_name" required placeholder="e.g. Networking & Servers">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold text-slate-300 fs-7">Description</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="description" name="description" rows="3" placeholder="Category purpose and scope..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-check me-1"></i> Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-pen text-cyan me-2"></i> Edit Category</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/categories/update" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="editCatId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="editCatName" class="form-label fw-semibold text-slate-300 fs-7">Category Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="editCatName" name="category_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCatDesc" class="form-label fw-semibold text-slate-300 fs-7">Description</label>
                        <textarea class="form-control bg-slate-950 text-white fs-7" id="editCatDesc" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan"><i class="fas fa-save me-1"></i> Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white rounded-4 border border-slate-800 shadow-2xl">
            <div class="modal-header border-bottom border-slate-800">
                <h6 class="modal-title fw-bold text-rose"><i class="fas fa-triangle-exclamation me-2"></i> Delete Category</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/categories/delete" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="deleteCatId">
                <div class="modal-body p-4 text-center">
                    <p class="mb-1 text-slate-300">Are you sure you want to delete category <strong id="deleteCatName" class="text-rose"></strong>?</p>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-slate-800 text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-rose"><i class="fas fa-trash-can me-1"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
