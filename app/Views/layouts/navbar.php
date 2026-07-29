<?php
use App\Core\CSRF;
$flashes = $_SESSION['flash_messages'] ?? [];
?>
<!-- Main Content Area Wrapper -->
<div id="content" class="flex-grow-1 d-flex flex-column min-vh-100 bg-dark-app overflow-x-hidden">

<!-- Top Dark Navbar -->
<header class="navbar navbar-expand bg-slate-900 border-bottom border-slate-800 sticky-top px-4 py-2.5 shadow-md">
    <button class="btn btn-slate-800 text-white border border-slate-700 d-md-none me-3" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="d-none d-md-flex align-items-center me-auto">
        <h5 class="fw-bold mb-0 text-white tracking-tight"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h5>
    </div>

    <div class="d-flex align-items-center gap-3 ms-auto">
        <!-- Live AJAX Search Bar -->
        <div class="position-relative d-none d-lg-block" style="width: 300px;">
            <input type="text" id="globalProductSearch" class="form-control form-control-sm rounded-pill ps-4 bg-slate-950 border-slate-800 text-white" placeholder="Search SKU, Product, Barcode...">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-slate-400 fs-7"></i>
            <div id="searchResultsDropdown" class="dropdown-menu dropdown-menu-dark shadow-2xl border-slate-800 bg-slate-900 w-100 mt-2 rounded-3 p-2 d-none position-absolute">
                <!-- AJAX results -->
            </div>
        </div>

        <!-- System Alerts Dropdown -->
        <div class="dropdown">
            <button class="btn btn-slate-800 text-white rounded-circle position-relative p-2 border border-slate-700" id="notifBellBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell text-slate-300"></i>
                <span id="notifBadgeCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                    0
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-2xl border-slate-800 bg-slate-900 rounded-3 p-0 overflow-hidden" style="width: 360px;">
                <div class="p-3 bg-slate-950 text-white d-flex align-items-center justify-content-between border-bottom border-slate-800">
                    <h6 class="mb-0 fw-bold fs-7"><i class="fas fa-bell me-2 text-cyan"></i> System Telemetry Alerts</h6>
                    <button class="btn btn-link btn-sm text-slate-400 p-0 text-decoration-none fs-8" id="markAllReadBtn">Mark All Read</button>
                </div>
                <div id="notifListContainer" class="list-group list-group-flush overflow-auto bg-slate-900" style="max-height: 280px;">
                    <div class="p-4 text-center text-slate-400 fs-7">Loading alerts...</div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white gap-2" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar bg-gradient-cyan text-slate-950 rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7 shadow-cyan" style="width: 36px; height: 36px;">
                    <?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>
                </div>
                <span class="d-none d-sm-inline fw-semibold fs-7 text-white"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-2xl border-slate-800 bg-slate-900 rounded-3 mt-2" aria-labelledby="userMenu">
                <li class="px-3 py-2 border-bottom border-slate-800">
                    <div class="fw-bold text-white fs-7"><?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?></div>
                    <small class="text-slate-400 d-block fs-8"><?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?></small>
                </li>
                <li><a class="dropdown-item fs-7 py-2 text-slate-200" href="/dashboard"><i class="fas fa-chart-line me-2 text-cyan"></i> Executive Hub</a></li>
                <li><hr class="dropdown-divider border-slate-800"></li>
                <li><a class="dropdown-item fs-7 py-2 text-danger" href="/logout"><i class="fas fa-right-from-bracket me-2"></i> Sign Out</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Main Page Content Container -->
<main class="flex-grow-1 p-4">
    <!-- Flash Messages Render -->
    <?php foreach ($flashes as $flashKey => $flash): ?>
        <?php if (!empty($flash['value'])): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : htmlspecialchars($flash['type']) ?> alert-dismissible fade show shadow-md mb-4 border-0 rounded-3" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-<?= $flash['type'] === 'error' ? 'circle-xmark text-danger' : 'circle-check text-success' ?> fs-5"></i>
                    <div><?= $flash['value'] ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
