<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NEXUS Inventory ERP</title>
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

<div class="auth-card card shadow-2xl border-slate-800 rounded-4 overflow-hidden w-100" style="max-width: 440px;">
    <div class="card-header bg-slate-900 text-center p-4 border-bottom border-slate-800">
        <div class="brand-logo bg-gradient-cyan text-slate-950 rounded-3 d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-2 shadow-cyan" style="width: 54px; height: 54px;">
            <i class="fas fa-key"></i>
        </div>
        <h4 class="fw-bold text-white mb-1 tracking-tight">NEXUS Password Reset</h4>
        <p class="text-slate-400 fs-7 mb-0">Enter your registered email address</p>
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

        <form action="/reset-password" method="POST">
            <?= CSRF::field() ?>

            <div class="mb-4">
                <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Account Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-envelope fs-7"></i></span>
                    <input type="email" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="email" name="email" placeholder="name@nexus.com" required>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-cyan btn-lg rounded-3 fs-6 fw-semibold">
                    <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer bg-slate-900 text-center p-3 border-top border-slate-800">
        <a href="/login" class="text-slate-400 text-decoration-none fs-7"><i class="fas fa-arrow-left me-1"></i> Back to Sign In</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
