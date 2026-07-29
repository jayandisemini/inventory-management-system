<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Smart Inventory ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-dark-app min-vh-100 d-flex align-items-center justify-content-center p-3">

<div class="auth-card card shadow-2xl border-slate-800 rounded-4 overflow-hidden w-100" style="max-width: 440px;">
    <div class="card-header bg-slate-900 text-center p-4 border-bottom border-slate-800">
        <div class="brand-logo bg-gradient-cyan text-slate-950 rounded-3 d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-cyan" style="width: 54px; height: 54px;">
            <i class="fas fa-boxes-packing"></i>
        </div>
        <h4 class="fw-bold text-white mb-1 tracking-tight">Smart Inventory ERP</h4>
        <p class="text-slate-400 fs-7 mb-0">Sign in to your enterprise account</p>
    </div>
    
    <div class="card-body p-4 bg-slate-900">
        <?php foreach ($flashes as $flash): ?>
            <?php if (!empty($flash['value'])): ?>
                <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show fs-7 border-0 shadow-xs mb-3" role="alert">
                    <?= $flash['value'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <form action="/login" method="POST" class="needs-validation" novalidate>
            <?= CSRF::field() ?>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-envelope fs-7"></i></span>
                    <input type="email" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="email" name="email" value="admin@sims.com" placeholder="name@company.com" required>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label fw-semibold text-slate-300 fs-7 mb-0">Password</label>
                    <a href="/reset-password" class="text-cyan text-decoration-none fs-8 fw-semibold">Forgot Password?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-lock fs-7"></i></span>
                    <input type="password" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="password" name="password" value="admin123" placeholder="Enter password" required>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-cyan btn-lg rounded-3 fs-6 fw-semibold">
                    <i class="fas fa-right-to-bracket me-2"></i> Sign In to Dashboard
                </button>
            </div>
        </form>

        <div class="bg-slate-950 rounded-3 p-3 text-center border border-slate-800">
            <small class="text-slate-400 fw-semibold d-block mb-2">Demo Credentials Quick Login:</small>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <button class="btn btn-xs btn-outline-cyan demo-fill-btn" data-email="admin@sims.com" data-pass="admin123">Admin</button>
                <button class="btn btn-xs btn-outline-light demo-fill-btn" data-email="manager@sims.com" data-pass="manager123">Manager</button>
                <button class="btn btn-xs btn-outline-rose demo-fill-btn" data-email="staff@sims.com" data-pass="staff123">Staff</button>
            </div>
        </div>
    </div>

    <div class="card-footer bg-slate-900 text-center p-3 border-top border-slate-800">
        <span class="text-slate-400 fs-7">Don't have an account? <a href="/register" class="text-cyan text-decoration-none fw-semibold ms-1">Create Account</a></span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.demo-fill-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('email').value = btn.dataset.email;
        document.getElementById('password').value = btn.dataset.pass;
    });
});
</script>
</body>
</html>
