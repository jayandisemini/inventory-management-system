<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - NEXUS Inventory ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .auth-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(9, 13, 22, 0.88) 0%, rgba(15, 23, 42, 0.92) 100%),
                        url('/assets/images/login_bg.png') no-repeat center center / cover;
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-dark-app text-white auth-wrapper min-vh-100 d-flex align-items-center justify-content-center p-3">

<div class="auth-card card shadow-2xl border-slate-800 rounded-4 overflow-hidden w-100" style="max-width: 480px;">
    <div class="card-header bg-slate-900 text-center p-4 border-bottom border-slate-800">
        <div class="brand-logo bg-gradient-cyan text-slate-950 rounded-3 d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-cyan" style="width: 54px; height: 54px;">
            <i class="fas fa-user-plus"></i>
        </div>
        <h4 class="fw-bold text-white mb-1 tracking-tight">Create NEXUS ERP Account</h4>
        <p class="text-slate-400 fs-7 mb-0">Register a new team member or manager profile</p>
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

        <form action="/register" method="POST">
            <?= CSRF::field() ?>

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold text-slate-300 fs-7">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-user fs-7"></i></span>
                    <input type="text" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="name" name="name" placeholder="John Doe" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-envelope fs-7"></i></span>
                    <input type="email" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="email" name="email" placeholder="john@nexus.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-slate-300 fs-7">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-lock fs-7"></i></span>
                    <input type="password" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="password" name="password" placeholder="At least 6 characters" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label fw-semibold text-slate-300 fs-7">Requested System Role</label>
                <select class="form-select bg-slate-950 border-slate-800 text-white fs-7" id="role_id" name="role_id">
                    <option value="3">Staff (View Only & Stock Requests)</option>
                    <option value="2">Inventory Manager (Stock & Catalog)</option>
                    <option value="1">System Administrator (Full Access)</option>
                </select>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-cyan btn-lg rounded-3 fs-6 fw-semibold">
                    <i class="fas fa-check-circle me-2"></i> Register Account
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer bg-slate-900 text-center p-3 border-top border-slate-800">
        <span class="text-slate-400 fs-7">Already registered? <a href="/login" class="text-cyan text-decoration-none fw-semibold ms-1">Sign In</a></span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
