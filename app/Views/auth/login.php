<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('sims_theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>
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
</head>
<body class="login-wrapper d-flex align-items-center justify-content-center p-3 p-md-4">

<!-- Floating Theme Toggle Button -->
<div class="position-absolute top-0 end-0 p-3 p-md-4 z-3">
    <button class="btn btn-theme-outline rounded-circle p-2 shadow-sm" id="themeToggleBtn" title="Toggle Light / Dark Theme" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-sun text-amber" id="themeToggleIcon"></i>
    </button>
</div>

<div class="container" style="max-width: 1060px;">
    <div class="row g-0 rounded-4 overflow-hidden shadow-2xl login-card">
        
        <!-- Left Hero Showcase Panel -->
        <div class="col-12 col-lg-6 hero-panel p-4 p-md-5 d-flex flex-column justify-content-between position-relative overflow-hidden">
            <div>
                <!-- Brand Badge -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="brand-logo bg-cyan text-slate-950 rounded-3 d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 48px; height: 48px;">
                        <i class="fas fa-cubes-stacked"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold tracking-tight text-white mb-0">NEXUS <span class="badge bg-cyan text-slate-950 fs-8 align-middle">ERP PRO</span></h4>
                        <small class="text-cyan text-uppercase fs-8 tracking-wider fw-bold">Enterprise Inventory Engine</small>
                    </div>
                </div>

                <div class="my-4">
                    <h2 class="fw-extrabold text-white mb-3 tracking-tight lh-sm">Smart Supply Chain & Multi-Warehouse Intelligence</h2>
                    <p class="hero-desc fs-7 leading-relaxed mb-4">
                        Automated stock management, real-time inventory telemetry, purchase order workflows, and executive business analytics for modern enterprises.
                    </p>
                </div>

                <!-- Feature Highlights Grid -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2.5 feature-badge rounded-3 d-flex align-items-center gap-2">
                            <i class="fas fa-chart-line text-cyan fs-6"></i>
                            <span class="fs-8 fw-semibold">Real-Time Telemetry</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 feature-badge rounded-3 d-flex align-items-center gap-2">
                            <i class="fas fa-warehouse text-emerald fs-6"></i>
                            <span class="fs-8 fw-semibold">Multi-Warehouse ERP</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 feature-badge rounded-3 d-flex align-items-center gap-2">
                            <i class="fas fa-robot text-amber fs-6"></i>
                            <span class="fs-8 fw-semibold">Auto-Restock Engine</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2.5 feature-badge rounded-3 d-flex align-items-center gap-2">
                            <i class="fas fa-shield-halved text-blue fs-6"></i>
                            <span class="fs-8 fw-semibold">RBAC Security Guard</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-top border-slate-700/60 d-flex align-items-center justify-content-between">
                <span class="badge bg-emerald-subtle text-emerald fs-8 border border-emerald-subtle px-2.5 py-1">
                    <i class="fas fa-circle-check me-1"></i> System Operational v2.4
                </span>
                <small class="text-slate-300 fs-8">&copy; <?= date('Y') ?> NEXUS Systems</small>
            </div>
        </div>

        <!-- Right Authentication Console -->
        <div class="col-12 col-lg-6 p-4 p-md-5 auth-console d-flex flex-column justify-content-center">
            
            <div class="mb-4">
                <h4 class="fw-bold text-theme-main mb-1">Sign In to Dashboard</h4>
                <p class="text-theme-muted fs-7 mb-0">Enter your credentials or choose a quick demo account below.</p>
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
                    <label for="email" class="form-label fw-semibold text-theme-main fs-7">Email Address <span class="text-rose">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control fs-7" id="email" name="email" value="admin@sims.com" placeholder="name@nexus.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label fw-semibold text-theme-main fs-7 mb-0">Password <span class="text-rose">*</span></label>
                        <a href="/reset-password" class="text-cyan text-decoration-none fs-8 fw-semibold">Forgot Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control fs-7" id="password" name="password" value="admin123" placeholder="Enter password" required>
                        <button class="btn btn-theme-outline input-group-text" type="button" id="togglePasswordBtn">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-cyan btn-lg w-100 rounded-3 fs-6 fw-bold mb-4 shadow-sm">
                    <i class="fas fa-right-to-bracket me-2"></i> Authenticate & Enter Hub
                </button>
            </form>

            <!-- 1-Click Role Quick Login Switcher -->
            <div>
                <small class="text-theme-muted fw-bold fs-8 text-uppercase tracking-wider d-block mb-2.5">1-Click Quick Demo Accounts:</small>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center active demo-role-card" data-email="admin@sims.com" data-pass="admin123">
                            <div class="avatar bg-cyan-subtle text-cyan rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">A</div>
                            <span class="d-block role-title fs-8">Admin</span>
                            <small class="text-cyan fs-9 fw-semibold">Executive</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center demo-role-card" data-email="manager@sims.com" data-pass="manager123">
                            <div class="avatar bg-emerald-subtle text-emerald rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">M</div>
                            <span class="d-block role-title fs-8">Manager</span>
                            <small class="text-emerald fs-9 fw-semibold">Operations</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-option-card p-2 rounded-3 text-center demo-role-card" data-email="staff@sims.com" data-pass="staff123">
                            <div class="avatar bg-warning-subtle text-amber rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold fs-8" style="width: 28px; height: 28px;">S</div>
                            <span class="d-block role-title fs-8">Staff</span>
                            <small class="text-amber fs-9 fw-semibold">Terminal</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 pt-3 border-top">
                <span class="text-theme-muted fs-7">Don't have an enterprise account? <a href="/register" class="text-cyan text-decoration-none fw-bold ms-1">Register Account</a></span>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Theme Toggle Functionality
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('sims_theme', theme);
        const icon = document.getElementById('themeToggleIcon');
        if (icon) icon.className = theme === 'dark' ? 'fas fa-sun text-amber' : 'fas fa-moon text-primary';
    }

    const currentSavedTheme = localStorage.getItem('sims_theme') || 'dark';
    applyTheme(currentSavedTheme);

    const themeToggleBtn = document.getElementById('themeToggleBtn');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function () {
            const activeTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
            applyTheme(activeTheme === 'dark' ? 'light' : 'dark');
        });
    }

    // Password Visibility Toggle
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (togglePasswordBtn && passwordInput && toggleIcon) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.className = isPassword ? 'fas fa-eye-slash text-cyan' : 'fas fa-eye text-theme-muted';
        });
    }

    // Interactive Role Switcher
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
