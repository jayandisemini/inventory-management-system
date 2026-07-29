<?php
use App\Core\CSRF;
$currentUser = $_SESSION['user'] ?? [];
?>
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-white p-4 rounded-4 border shadow-xs">
        <div>
            <h4 class="fw-bold text-slate-900 mb-1">System User Management</h4>
            <p class="text-muted fs-7 mb-0">Manage RBAC roles, team access, and administrator credentials.</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-user-plus me-1.5"></i> Add New User
        </button>
    </div>

    <div class="card border-0 shadow-xs rounded-4 bg-white p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="usersDataTable">
                <thead class="table-light">
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Assigned Role</th>
                        <th>Created Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="fw-mono text-muted fs-8">#USR-<?= str_pad($u->user_id, 4, '0', STR_PAD_LEFT) ?></td>
                            <td class="fw-bold text-slate-900">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 32px; height: 32px;">
                                        <?= strtoupper(substr($u->name, 0, 1)) ?>
                                    </div>
                                    <?= htmlspecialchars($u->name) ?>
                                    <?php if ($u->user_id === (int)$currentUser['user_id']): ?>
                                        <span class="badge bg-soft-primary text-primary fs-8">You</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="fs-7 text-slate-700"><?= htmlspecialchars($u->email) ?></td>
                            <td>
                                <span class="badge bg-<?= $u->role_id == 1 ? 'danger' : ($u->role_id == 2 ? 'primary' : 'secondary') ?>-subtle text-<?= $u->role_id == 1 ? 'danger' : ($u->role_id == 2 ? 'primary' : 'secondary') ?> border px-2.5 py-1">
                                    <i class="fas fa-shield me-1"></i><?= htmlspecialchars($u->role_name ?? 'Staff') ?>
                                </span>
                            </td>
                            <td class="text-muted fs-8"><?= date('Y-m-d', strtotime($u->created_at)) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary edit-user-btn" 
                                            data-id="<?= $u->user_id ?>" 
                                            data-name="<?= htmlspecialchars($u->name) ?>" 
                                            data-email="<?= htmlspecialchars($u->email) ?>" 
                                            data-role="<?= $u->role_id ?>">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <?php if ($u->user_id !== (int)$currentUser['user_id']): ?>
                                        <button class="btn btn-outline-danger delete-user-btn" data-id="<?= $u->user_id ?>" data-name="<?= htmlspecialchars($u->name) ?>">
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

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-slate-900 text-white border-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Add System User</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/users/store" method="POST">
                <?= CSRF::field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-slate-700 fs-7">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fs-7" id="name" name="name" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-slate-700 fs-7">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control fs-7" id="email" name="email" required placeholder="john@sims.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-slate-700 fs-7">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control fs-7" id="password" name="password" required placeholder="At least 6 characters">
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label fw-semibold text-slate-700 fs-7">System Role <span class="text-danger">*</span></label>
                        <select class="form-select fs-7" id="role_id" name="role_id" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['role_id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Save User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-slate-900 text-white border-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-user-pen me-2"></i> Edit System User</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/users/update" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="editUserId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label fw-semibold text-slate-700 fs-7">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fs-7" id="editUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label fw-semibold text-slate-700 fs-7">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control fs-7" id="editUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserPassword" class="form-label fw-semibold text-slate-700 fs-7">New Password (Leave blank to keep current)</label>
                        <input type="password" class="form-control fs-7" id="editUserPassword" name="password" placeholder="Enter new password if updating">
                    </div>
                    <div class="mb-3">
                        <label for="editUserRole" class="form-label fw-semibold text-slate-700 fs-7">System Role <span class="text-danger">*</span></label>
                        <select class="form-select fs-7" id="editUserRole" name="role_id" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['role_id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-triangle-exclamation me-2"></i> Delete User Account</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/users/delete" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" id="deleteUserId">
                <div class="modal-body p-4 text-center">
                    <p class="mb-1 text-slate-700">Delete user account <strong id="deleteUserName" class="text-danger"></strong>?</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-can me-1"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
