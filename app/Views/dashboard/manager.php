<?php
use App\Core\CSRF;

$totalProds = max(1, $metrics['total_products'] ?? 1);
$healthyCount = $metrics['healthy_count'] ?? 0;
$lowCount = $metrics['low_stock_count'] ?? 0;
$outCount = $metrics['out_of_stock_count'] ?? 0;

$healthyPct = round(($healthyCount / $totalProds) * 100);
$lowPct = round(($lowCount / $totalProds) * 100);
$outPct = min(100 - ($healthyPct + $lowPct), round(($outCount / $totalProds) * 100));
?>
<div class="container-fluid px-0">

    <!-- Manager Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 bg-slate-900 p-4 rounded-4 border border-slate-800">
        <div>
            <h4 class="fw-bold text-white mb-1">
                <i class="fas fa-cubes-stacked text-cyan me-2"></i>Manager Operations Hub & Restock Engine
            </h4>
            <p class="text-slate-400 fs-7 mb-0">Monitor inventory health, review reorder financial commitments, and manage dispatches.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="/inventory/stock-in" class="btn btn-emerald btn-sm rounded-3">
                <i class="fas fa-plus me-1.5"></i> Stock In
            </a>
            <a href="/inventory/stock-out" class="btn btn-rose btn-sm rounded-3">
                <i class="fas fa-minus me-1.5"></i> Stock Out
            </a>
            <a href="/inventory/adjust" class="btn btn-outline-light btn-sm rounded-3">
                <i class="fas fa-sliders me-1.5"></i> Adjust Count
            </a>
        </div>
    </div>

    <!-- 5 Key Operational Metric Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4">
        <div class="col">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 border-start border-3 border-cyan">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-slate-400 fs-7 fw-semibold">Total Catalog</span>
                    <div class="metric-icon bg-cyan-subtle text-cyan rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-boxes-stacked fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-white mb-0"><?= number_format($metrics['total_products'] ?? 0) ?></h3>
                <span class="fs-8 text-slate-400">Total active SKUs</span>
            </div>
        </div>

        <div class="col">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 border-start border-3 border-emerald">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-slate-400 fs-7 fw-semibold">Healthy Items</span>
                    <div class="metric-icon bg-emerald-subtle text-emerald rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-circle-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-emerald mb-0"><?= number_format($healthyCount) ?></h3>
                <span class="fs-8 text-emerald"><?= $healthyPct ?>% of total stock</span>
            </div>
        </div>

        <div class="col">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 border-start border-3 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-slate-400 fs-7 fw-semibold">Low Stock</span>
                    <div class="metric-icon bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-warning mb-0"><?= number_format($lowCount) ?></h3>
                <span class="fs-8 text-warning">Below min threshold</span>
            </div>
        </div>

        <div class="col">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 border-start border-3 border-rose">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-slate-400 fs-7 fw-semibold">Out of Stock</span>
                    <div class="metric-icon bg-rose-subtle text-rose rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-circle-xmark fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-rose mb-0"><?= number_format($outCount) ?></h3>
                <span class="fs-8 text-rose">Immediate restock required</span>
            </div>
        </div>

        <div class="col">
            <div class="card card-metric border-0 rounded-4 bg-slate-900 p-4 h-100 border-start border-3 border-indigo">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-slate-400 fs-7 fw-semibold">Reorder Capital</span>
                    <div class="metric-icon bg-indigo-subtle text-indigo rounded-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sack-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-indigo mb-0">$<?= number_format($metrics['total_reorder_cost'] ?? 0, 2) ?></h3>
                <span class="fs-8 text-slate-400">Estimated restock cost</span>
            </div>
        </div>
    </div>

    <!-- Stock Health Visual Distribution Gauge -->
    <div class="card border-0 rounded-4 bg-slate-900 p-4 mb-4 border border-slate-800">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="fw-bold text-white mb-0 fs-7">
                <i class="fas fa-heart-pulse text-emerald me-2"></i> Warehouse Inventory Health Gauge
            </h6>
            <span class="fs-8 text-slate-400">Health Ratio: <strong class="text-emerald"><?= $healthyPct ?>%</strong></span>
        </div>
        <div class="progress rounded-pill bg-slate-800 overflow-hidden mb-3" style="height: 12px;">
            <div class="progress-bar bg-emerald" role="progressbar" style="width: <?= $healthyPct ?>%" title="Healthy (<?= $healthyPct ?>%)"></div>
            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $lowPct ?>%" title="Low Stock (<?= $lowPct ?>%)"></div>
            <div class="progress-bar bg-rose" role="progressbar" style="width: <?= $outPct ?>%" title="Out of Stock (<?= $outPct ?>%)"></div>
        </div>
        <div class="d-flex align-items-center justify-content-start gap-4 fs-8">
            <div class="d-flex align-items-center gap-1.5">
                <span class="d-inline-block rounded-circle bg-emerald" style="width: 8px; height: 8px;"></span>
                <span class="text-slate-300">Healthy: <strong><?= $healthyCount ?></strong></span>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <span class="d-inline-block rounded-circle bg-warning" style="width: 8px; height: 8px;"></span>
                <span class="text-slate-300">Low Stock: <strong><?= $lowCount ?></strong></span>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <span class="d-inline-block rounded-circle bg-rose" style="width: 8px; height: 8px;"></span>
                <span class="text-slate-300">Out of Stock: <strong><?= $outCount ?></strong></span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Restock Queue Table with Live Filter -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100 border border-slate-800">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 gap-2">
                    <div>
                        <h6 class="fw-bold text-white mb-0">
                            <i class="fas fa-truck-ramp-box text-warning me-2"></i> Items Needing Restock
                        </h6>
                        <span class="text-slate-400 fs-8">Smart restock engine recommendations based on safety levels</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-slate-800 border-slate-700 text-slate-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="restockSearchInput" class="form-control bg-slate-800 border-slate-700 text-white fs-8" placeholder="Filter restock items...">
                        </div>
                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fs-8 fw-bold"><?= count($restockQueue) ?> Item(s)</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0 fs-7" id="restockTable">
                        <thead>
                            <tr class="text-slate-400 border-bottom border-slate-800">
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Unit Cost</th>
                                <th>Current</th>
                                <th>Min Threshold</th>
                                <th>Suggested</th>
                                <th>Est. Cost</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($restockQueue)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-emerald py-4">
                                        <i class="fas fa-check-circle fs-5 me-2"></i> All products are adequately stocked!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($restockQueue as $p): 
                                    $estCost = $p->suggested_reorder_qty * ($p->cost_price ?? 0);
                                ?>
                                    <tr class="restock-row" data-name="<?= strtolower(htmlspecialchars($p->product_name)) ?>" data-sku="<?= strtolower(htmlspecialchars($p->sku)) ?>">
                                        <td class="fw-bold text-white"><?= htmlspecialchars($p->product_name) ?></td>
                                        <td class="fw-mono text-slate-400 fs-8"><?= htmlspecialchars($p->sku) ?></td>
                                        <td class="text-slate-300">$<?= number_format($p->cost_price ?? 0, 2) ?></td>
                                        <td class="fw-bold <?= $p->quantity <= 0 ? 'text-rose' : 'text-warning' ?>"><?= $p->quantity ?></td>
                                        <td><?= $p->min_stock_level ?></td>
                                        <td>
                                            <span class="badge bg-emerald-subtle text-emerald px-2.5 py-1 font-mono">
                                                +<?= $p->suggested_reorder_qty ?> units
                                            </span>
                                        </td>
                                        <td class="fw-bold text-indigo">$<?= number_format($estCost, 2) ?></td>
                                        <td class="text-end">
                                            <button type="button" 
                                                    class="btn btn-xs btn-emerald rounded-2 open-restock-modal"
                                                    data-id="<?= $p->product_id ?>"
                                                    data-name="<?= htmlspecialchars($p->product_name) ?>"
                                                    data-qty="<?= $p->suggested_reorder_qty ?>">
                                                <i class="fas fa-plus me-1"></i> Quick Restock
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Stock Activity Feed Side Widget -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 rounded-4 bg-slate-900 p-4 h-100 border border-slate-800">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-white mb-0">
                        <i class="fas fa-clock-rotate-left text-cyan me-2"></i> Recent Stock Feed
                    </h6>
                    <a href="/inventory/movements" class="fs-8 text-cyan text-decoration-none">View All <i class="fas fa-arrow-right ms-1"></i></a>
                </div>

                <div class="activity-feed">
                    <?php if (empty($recentMovements)): ?>
                        <p class="text-slate-500 fs-8 text-center my-4">No recent stock movements found.</p>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2.5">
                            <?php foreach (array_slice($recentMovements, 0, 6) as $m): 
                                $isObj = is_object($m);
                                $mType = $isObj ? ($m->movement_type ?? 'Stock In') : ($m['movement_type'] ?? $m['type'] ?? 'Stock In');
                                $pName = $isObj ? ($m->product_name ?? 'Product') : ($m['product_name'] ?? 'Product');
                                $uName = $isObj ? ($m->user_name ?? 'System') : ($m['user_name'] ?? 'System');
                                $qty = $isObj ? ($m->quantity ?? 0) : ($m['quantity'] ?? 0);
                                $createdAt = $isObj ? ($m->created_at ?? 'now') : ($m['created_at'] ?? 'now');

                                $isOut = str_contains(strtolower($mType), 'out');
                                $isIn = str_contains(strtolower($mType), 'in');

                                $badgeClass = $isIn ? 'bg-emerald-subtle text-emerald' : ($isOut ? 'bg-rose-subtle text-rose' : 'bg-warning-subtle text-warning');
                                $icon = $isIn ? 'fa-arrow-down-left' : ($isOut ? 'fa-arrow-up-right' : 'fa-sliders');
                            ?>
                                <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-slate-800/50 border border-slate-800">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="metric-icon <?= $badgeClass ?> rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas <?= $icon ?> fs-7"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white fs-7"><?= htmlspecialchars($pName) ?></div>
                                            <div class="text-slate-400 fs-8">By <?= htmlspecialchars($uName) ?> • <?= date('M d, H:i', strtotime($createdAt)) ?></div>
                                        </div>
                                    </div>
                                    <span class="badge <?= $badgeClass ?> font-mono fs-8 fw-bold">
                                        <?= $isOut ? '-' : '+' ?><?= $qty ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Restock Modal -->
<div class="modal fade" id="quickRestockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-slate-900 text-white border border-slate-800 rounded-4">
            <div class="modal-header border-bottom border-slate-800">
                <h5 class="modal-title fw-bold text-white fs-6">
                    <i class="fas fa-truck-ramp-box text-emerald me-2"></i> Quick Restock Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/inventory/stock-in" method="GET">
                <div class="modal-body p-4">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <div class="mb-3">
                        <label class="form-label text-slate-400 fs-7">Target Product</label>
                        <input type="text" id="modalProductName" class="form-control bg-slate-800 border-slate-700 text-white fw-bold" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-400 fs-7">Restock Quantity (Units)</label>
                        <input type="number" name="quantity" id="modalRestockQty" class="form-control bg-slate-800 border-slate-700 text-emerald fw-bold" min="1" required>
                    </div>
                    <div class="p-3 bg-slate-800/60 rounded-3 text-slate-400 fs-8">
                        <i class="fas fa-circle-info text-cyan me-1.5"></i> Direct restock will pre-fill the inventory receiving form with recommended quantities.
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-800">
                    <button type="button" class="btn btn-outline-light btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-emerald btn-sm rounded-3">
                        <i class="fas fa-arrow-right me-1"></i> Proceed to Receive Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Instant Filter for Restock Table
    const searchInput = document.getElementById('restockSearchInput');
    const tableRows = document.querySelectorAll('.restock-row');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const sku = row.getAttribute('data-sku') || '';
                if (name.includes(query) || sku.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Quick Restock Modal Handler
    const restockButtons = document.querySelectorAll('.open-restock-modal');
    const modalProductId = document.getElementById('modalProductId');
    const modalProductName = document.getElementById('modalProductName');
    const modalRestockQty = document.getElementById('modalRestockQty');
    const restockModalEl = document.getElementById('quickRestockModal');

    if (restockButtons.length > 0 && restockModalEl) {
        const bsModal = new bootstrap.Modal(restockModalEl);
        restockButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                modalProductId.value = this.getAttribute('data-id');
                modalProductName.value = this.getAttribute('data-name');
                modalRestockQty.value = this.getAttribute('data-qty');
                bsModal.show();
            });
        });
    }
});
</script>
