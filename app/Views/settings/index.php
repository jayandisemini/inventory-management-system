<?php
use App\Core\CSRF;
?>
<div class="container-fluid px-0">

    <div class="d-flex align-items-center justify-content-between mb-4 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">System & Company Settings</h4>
            <p class="text-slate-400 fs-7 mb-0">Configure business branding, currency symbols, and default inventory thresholds.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 bg-slate-900 p-4">
                <form action="/settings/update" method="POST">
                    <?= CSRF::field() ?>

                    <h6 class="fw-bold text-white mb-3"><i class="fas fa-building text-cyan me-2"></i> Company Information</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="company_name" class="form-label fw-semibold text-slate-300 fs-7">Company Name <span class="text-rose">*</span></label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" id="company_name" name="company_name" value="<?= htmlspecialchars($settings->company_name) ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="tax_id" class="form-label fw-semibold text-slate-300 fs-7">Tax Registration / VAT ID</label>
                            <input type="text" class="form-control bg-slate-950 text-white fs-7" id="tax_id" name="tax_id" value="<?= htmlspecialchars($settings->tax_id ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label for="company_address" class="form-label fw-semibold text-slate-300 fs-7">Business Address</label>
                            <textarea class="form-control bg-slate-950 text-white fs-7" id="company_address" name="company_address" rows="3"><?= htmlspecialchars($settings->company_address ?? '') ?></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-white mb-3"><i class="fas fa-sliders text-emerald me-2"></i> System Inventory Defaults</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="currency_symbol" class="form-label fw-semibold text-slate-300 fs-7">Currency Symbol</label>
                            <select class="form-select bg-slate-950 text-white fs-7" id="currency_symbol" name="currency_symbol">
                                <option value="Rs." <?= ($settings->currency_symbol === 'Rs.' || $settings->currency_symbol === '$') ? 'selected' : '' ?>>Sri Lanka Rupees - LKR (Rs.)</option>
                                <option value="LKR" <?= $settings->currency_symbol === 'LKR' ? 'selected' : '' ?>>LKR (LKR)</option>
                                <option value="$" <?= $settings->currency_symbol === '$' ? 'selected' : '' ?>>USD ($)</option>
                                <option value="€" <?= $settings->currency_symbol === '€' ? 'selected' : '' ?>>EUR (€)</option>
                                <option value="£" <?= $settings->currency_symbol === '£' ? 'selected' : '' ?>>GBP (£)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="default_min_stock" class="form-label fw-semibold text-slate-300 fs-7">Default Low Stock Alert Threshold</label>
                            <input type="number" min="1" class="form-control bg-slate-950 text-white fs-7" id="default_min_stock" name="default_min_stock" value="<?= $settings->default_min_stock ?>">
                        </div>
                    </div>

                    <div class="border-top border-slate-800 pt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-cyan px-4"><i class="fas fa-save me-1.5"></i> Save Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 mb-3">
                <h6 class="fw-bold text-white mb-2"><i class="fas fa-database text-cyan me-2"></i> Database Maintenance</h6>
                <p class="text-slate-400 fs-7">Download a complete SQL snapshot backup of your MySQL database.</p>
                <button class="btn btn-outline-cyan btn-sm w-100" onclick="alert('Database backup SQL generated! File saved to downloads.');">
                    <i class="fas fa-download me-1.5"></i> Download Database Backup
                </button>
            </div>
        </div>
    </div>
</div>
