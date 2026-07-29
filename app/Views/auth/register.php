<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Smart Inventory ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-slate-900 min-vh-100 d-flex align-items-center justify-content-center p-3">

<div class="auth-card card shadow-2xl border-0 rounded-4 overflow-hidden w-100" style="max-width: 480px;">
    <div class="card-header bg-slate-800 text-center p-4 border-bottom border-secondary border-opacity-25">
        <div class="brand-logo bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-md" style="width: 54px; height: 54px;">
            <i class="fas fa-user-plus"></i>
        </div>
        <h4 class="fw-bold text-white mb-1 tracking-tight">Create SIMS Account</h4>
        <p class="text-white-50 fs-7 mb-0">Register a new team member or manager profile</p>
    </div>
    
    <div class="card-body p-4 bg-white">
        <?php foreach ($flashes as $flash): ?>
            <?php if (!empty($flash['value'])): ?>
                <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show fs-7 border-0 shadow-xs mb-3" role="alert">
                    <?= $flash['value'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <form action="/register" method="POST">
            <?= CSRF::field() ?>

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold text-slate-700 fs-7">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user fs-7"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 ps-0 fs-7" id="name" name="name" placeholder="John Doe" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-slate-700 fs-7">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope fs-7"></i></span>
                    <input type="email" class="form-control bg-light border-start-0 ps-0 fs-7" id="email" name="email" placeholder="john@company.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-slate-700 fs-7">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock fs-7"></i></span>
                    <input type="password" class="form-control bg-light border-start-0 ps-0 fs-7" id="password" name="password" placeholder="At least 6 characters" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label fw-semibold text-slate-700 fs-7">Requested System Role</label>
                <select class="form-select bg-light fs-7" id="role_id" name="role_id">
                    <option value="3">Staff (View Only & Stock Requests)</option>
                    <option value="2">Inventory Manager (Stock & Catalog)</option>
                    <option value="1">System Administrator (Full Access)</option>
                </select>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg rounded-3 fs-6 fw-semibold shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> Register Account
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer bg-slate-800 text-center p-3 border-top border-secondary border-opacity-25">
        <span class="text-white-50 fs-7">Already registered? <a href="/login" class="text-primary text-decoration-none fw-semibold ms-1">Sign In</a></span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
