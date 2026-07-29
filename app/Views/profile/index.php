<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">My Account & Security Profile</h4>
            <p class="text-slate-400 fs-7 mb-0">Update your personal account details, email address, and account password.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Account Profile Info -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <h6 class="fw-bold text-white mb-3"><i class="fas fa-user-gear text-cyan me-2"></i> Profile Information</h6>

                <form action="/profile/update" method="POST">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-slate-300 fs-7">Full Name <span class="text-rose">*</span></label>
                        <input type="text" class="form-control bg-slate-950 text-white fs-7" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Email Address <span class="text-rose">*</span></label>
                        <input type="email" class="form-control bg-slate-950 text-white fs-7" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-slate-300 fs-7">Assigned Role</label>
                        <input type="text" class="form-control bg-slate-950 text-slate-400 fs-7" value="<?= htmlspecialchars($user->role_name ?? 'User') ?>" readonly disabled>
                    </div>

                    <button type="submit" class="btn btn-cyan w-100"><i class="fas fa-save me-1.5"></i> Update Profile Details</button>
                </form>
            </div>
        </div>

        <!-- Security & Password Reset -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100">
                <h6 class="fw-bold text-white mb-3"><i class="fas fa-shield-halved text-emerald me-2"></i> Password & Security</h6>

                <form action="/profile/password" method="POST">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold text-slate-300 fs-7">Current Password <span class="text-rose">*</span></label>
                        <input type="password" class="form-control bg-slate-950 text-white fs-7" id="current_password" name="current_password" required placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold text-slate-300 fs-7">New Password <span class="text-rose">*</span></label>
                        <input type="password" class="form-control bg-slate-950 text-white fs-7" id="new_password" name="new_password" required minlength="6" placeholder="Min. 6 characters">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label fw-semibold text-slate-300 fs-7">Confirm New Password <span class="text-rose">*</span></label>
                        <input type="password" class="form-control bg-slate-950 text-white fs-7" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Repeat new password">
                    </div>

                    <button type="submit" class="btn btn-emerald w-100"><i class="fas fa-key me-1.5"></i> Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
