<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - NEXUS Inventory ERP</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom Enterprise CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .login-wrapper {
            min-height: 100vh;
            background: radial-gradient(circle at 15% 15%, rgba(6, 182, 212, 0.12) 0%, transparent 45%),
                        radial-gradient(circle at 85% 85%, rgba(59, 130, 246, 0.12) 0%, transparent 45%),
                        #090d16;
        }
        .hero-panel {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.6) 100%);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .login-card {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid #1e293b;
        }
        .role-option-card {
            background: #020617;
            border: 1px solid #1e293b;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .role-option-card:hover, .role-option-card.active {
            border-color: #06b6d4;
            background: rgba(6, 182, 212, 0.08);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-dark-app text-white login-wrapper d-flex align-items-center justify-content-center p-3 p-md-4">

<div class="container" style="max-width: 1100px;">
    <div class="row g-0 rounded-4 overflow-hidden shadow-2xl login-card">
        
        <!-- Left Hero Showcase Panel -->
        <div class="col-12 col-lg-6 hero-panel p-4 p-md-5 d-flex flex-column justify-content-between position-relative overflow-hidden">
            <div>
                <!-- Brand Badge -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="brand-logo bg-gradient-cyan text-slate-950 rounded-3 d-flex align-items-center justify-content-center fw-bold fs-3 shadow-cyan" style="width: 48px; height: 48px;">
                        <i class="fas fa-cubes-stacked"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold tracking-tight text-white mb-0">NEXUS <span class="badge bg-cyan text-slate-950 fs-8 align-middle">ERP PRO</span></h4>
                        <small class="text-cyan text-uppercase fs-8 tracking-wider fw-bold">Enterprise Inventory Engine</small>
                    </div>
                </div>

                <div class="my-4">
                    <h2 class="fw-extrabold text-white mb-3 tracking-tight lh-sm">Smart Supply Chain & Multi-Warehouse Intelligence</h2>
                    <p class="text-slate-400 fs-7 leading-relaxed mb-4">
                        Automated stock management, real-time inventory telemetry, purchase order workflows, and executive business analytics for modern enterprises.
                    </p>
                </div>

                <!-- Feature Highlights Grid -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2.5 bg-slate-950/60 rounded-3 border border-slate-800 d-flex align-items-center gap-2">
                            <i class="fas fa-chart-line text-cyan fs-6"></i>
                            <span class="fs-8 fw-semibold text-slate-200">Real-Time Telemetry</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 bg-slate-950/60 rounded-3 border border-slate-800 d-flex align-items-center gap-2">
                            <i class="fas fa-warehouse text-emerald fs-6"></i>
                            <span class="fs-8 fw-semibold text-slate-200">Multi-Warehouse ERP</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 bg-slate-950/60 rounded-3 border border-slate-800 d-flex align-items-center gap-2">
                            <i class="fas fa-robot text-amber fs-6"></i>
                            <span class="fs-8 fw-semibold text-slate-200">Auto-Restock Engine</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 bg-slate-950/60 rounded-3 border border-slate-800 d-flex align-items-center gap-2">
                            <i class="fas fa-shield-halved text-blue fs-6"></i>
                            <span class="fs-8 fw-semibold text-slate-200">RBAC Security Guard</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-top border-slate-800/80 d-flex align-items-center justify-content-between">
                <span class="badge bg-emerald-subtle text-emerald fs-8 border border-emerald-subtle px-2.5 py-1">
                    <i class="fas fa-circle-check me-1"></i> System Operational v2.4
                </span>
                <small class="text-slate-400 fs-8">&copy; <?= date('Y') ?> NEXUS Systems</small>
            </div>
        </div>

        <!-- Right Authentication Console -->
        <div class="col-12 col-lg-6 p-4 p-md-5 bg-slate-900 d-flex flex-column justify-content-center">
            
            <div class="mb-4">
                <h4 class="fw-bold text-white mb-1">Sign In to Dashboard</h4>
                <p class="text-slate-400 fs-7 mb-0">Enter your credentials or choose a quick demo account below.</p>
            </div>

            <!-- Flash Alert Messages -->
            <?php foreach ($flashes as $flash): ?>
                <?php if (!empty($flash['value'])): ?>
                    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show fs-7 border-0 rounded-3 mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-circle-exclamation me-1"></i>
                            <div><?= $flash['value'] ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <form action="/login" method="POST" id="loginForm">
                <?= CSRF::field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-slate-300 fs-7">Email Address <span class="text-rose">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="email" name="email" value="admin@sims.com" placeholder="name@nexus.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label fw-semibold text-slate-300 fs-7 mb-0">Password <span class="text-rose">*</span></label>
                        <a href="/reset-password" class="text-cyan text-decoration-none fs-8 fw-semibold">Forgot Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-slate-950 border-slate-800 text-slate-400"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control bg-slate-950 border-slate-800 text-white fs-7" id="password" name="password" value="admin123" placeholder="Enter password" required>
                        <button class="btn btn-slate-800 border border-slate-800 text-slate-400" type="button" id="togglePasswordBtn">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-cyan btn-lg w-100 rounded-3 fs-6 fw-bold mb-4 shadow-cyan">
                    <i class="fas fa-right-to-bracket me-2"></i> Authenticate & Enter Hub
                </button>
            </form>

            <!-- 1-Click Role Quick Login Switcher -->
            <div>
                <small class="text-slate-400 fw-semibold fs-8 text-uppercase tracking-wider d-block mb-2.5">1-Click Quick Demo Accounts:</small>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center active demo-role-card" data-email="admin@sims.com" data-pass="admin123">
                            <div class="avatar bg-cyan-subtle text-cyan rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">A</div>
                            <span class="d-block fw-bold text-white fs-8">Admin</span>
                            <small class="text-cyan fs-9">Executive</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center demo-role-card" data-email="manager@sims.com" data-pass="manager123">
                            <div class="avatar bg-emerald-subtle text-emerald rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">M</div>
                            <span class="d-block fw-bold text-white fs-8">Manager</span>
                            <small class="text-emerald fs-9">Operations</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center demo-role-card" data-email="staff@sims.com" data-pass="staff123">
                            <div class="avatar bg-warning-subtle text-amber rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">S</div>
                            <span class="d-block fw-bold text-white fs-8">Staff</span>
                            <small class="text-amber fs-9">Terminal</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 pt-3 border-top border-slate-800">
                <span class="text-slate-400 fs-7">Don't have an enterprise account? <a href="/register" class="text-cyan text-decoration-none fw-bold ms-1">Register Account</a></span>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Password Visibility Toggle
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (togglePasswordBtn && passwordInput && toggleIcon) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.className = isPassword ? 'fas fa-eye-slash text-cyan' : 'fas fa-eye text-slate-400';
        });
    }

    // 2. Interactive Role Switcher
    const roleCards = document.querySelectorAll('.demo-role-card');
    const emailInput = document.getElementById('email');

    roleCards.forEach(card => {
        card.addEventListener('click', function () {
            roleCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            if (emailInput) emailInput.value = this.dataset.email;
            if (passwordInput) passwordInput.value = this.dataset.pass;
        });
    });
});
</script>

</body>
</html>
