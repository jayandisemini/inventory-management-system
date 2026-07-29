/**
 * Smart Inventory Management System (SIMS Pro) Dark Theme Client Logic
 */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleClose = document.getElementById('sidebarToggleClose');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
    if (sidebarToggleClose && sidebar) {
        sidebarToggleClose.addEventListener('click', () => sidebar.classList.remove('show'));
    }

    // 2. DataTables Configuration with Dark Styling & Graceful Empty States
    const dataTableOptions = {
        pageLength: 10,
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records...",
            zeroRecords: "No matching records found"
        }
    };

    if ($.fn.DataTable) {
        if ($('#productsDataTable').length) $('#productsDataTable').DataTable(dataTableOptions);
        if ($('#suppliersDataTable').length) $('#suppliersDataTable').DataTable(dataTableOptions);
        if ($('#movementsDataTable').length) $('#movementsDataTable').DataTable(dataTableOptions);
        if ($('#salesOrdersDataTable').length) $('#salesOrdersDataTable').DataTable(dataTableOptions);
        if ($('#purchaseOrdersDataTable').length) $('#purchaseOrdersDataTable').DataTable(dataTableOptions);
        if ($('#stockRequestsDataTable').length) $('#stockRequestsDataTable').DataTable(dataTableOptions);
        if ($('#batchesDataTable').length) $('#batchesDataTable').DataTable(dataTableOptions);
        if ($('#transfersDataTable').length) $('#transfersDataTable').DataTable(dataTableOptions);
        if ($('#stockTakesDataTable').length) $('#stockTakesDataTable').DataTable(dataTableOptions);
        if ($('#assembliesDataTable').length) $('#assembliesDataTable').DataTable(dataTableOptions);
        if ($('#customersDataTable').length) $('#customersDataTable').DataTable(dataTableOptions);
        if ($('#usersDataTable').length) $('#usersDataTable').DataTable(dataTableOptions);
        if ($('#dashboardLowStockTable').length) $('#dashboardLowStockTable').DataTable({ pageLength: 5, searching: false, lengthChange: false });
    }

    // 3. Render Chart.js with Dark Mode Colors
    if (window.dashboardChartsData && typeof Chart !== 'undefined') {
        const cData = window.dashboardChartsData;

        // Configure Chart.js global dark defaults
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';

        // Monthly Movement Chart (Bar)
        const ctxMonthly = document.getElementById('monthlyMovementsChart');
        if (ctxMonthly) {
            const labels = cData.monthly_movements.map(m => m.month_label);
            const stockIn = cData.monthly_movements.map(m => parseInt(m.stock_in));
            const stockOut = cData.monthly_movements.map(m => parseInt(m.stock_out));

            new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Stock In', data: stockIn, backgroundColor: '#10b981', borderRadius: 6 },
                        { label: 'Stock Out', data: stockOut, backgroundColor: '#f43f5e', borderRadius: 6 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#e2e8f0' } } },
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)' } }
                    }
                }
            });
        }

        // Category Distribution Chart (Doughnut)
        const ctxCategory = document.getElementById('categoryDistributionChart');
        if (ctxCategory) {
            const catLabels = cData.category_distribution.map(c => c.category_name);
            const catData = cData.category_distribution.map(c => parseInt(c.total_qty));

            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catData,
                        backgroundColor: ['#06b6d4', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'],
                        borderWidth: 2,
                        borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { color: '#e2e8f0' } } }
                }
            });
        }

        // Top Moving Products (Horizontal Bar)
        const ctxTop = document.getElementById('topMovingProductsChart');
        if (ctxTop) {
            const topLabels = cData.top_moving.map(t => t.product_name);
            const topData = cData.top_moving.map(t => parseInt(t.total_moved));

            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{
                        label: 'Units Transacted',
                        data: topData,
                        backgroundColor: '#06b6d4',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)' } }
                    }
                }
            });
        }
    }

    // 4. Live AJAX Search Bar
    const searchInput = document.getElementById('globalProductSearch');
    const searchDropdown = document.getElementById('searchResultsDropdown');

    if (searchInput && searchDropdown) {
        let debounceTimer;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const kw = this.value.trim();
            if (kw.length < 2) {
                searchDropdown.classList.add('d-none');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/products?keyword=${encodeURIComponent(kw)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.products.length > 0) {
                        searchDropdown.innerHTML = data.products.slice(0, 5).map(p => `
                            <a href="/products/show?id=${p.product_id}" class="dropdown-item d-flex align-items-center justify-content-between py-2 border-bottom border-slate-800">
                                <div>
                                    <div class="fw-bold text-white fs-7">${p.product_name}</div>
                                    <small class="text-slate-400 fw-mono fs-8">${p.sku}</small>
                                </div>
                                <span class="badge ${p.quantity <= 0 ? 'bg-danger' : (p.quantity <= p.min_stock_level ? 'bg-warning text-dark' : 'bg-success')}">${p.quantity} in stock</span>
                            </a>
                        `).join('');
                        searchDropdown.classList.remove('d-none');
                    } else {
                        searchDropdown.innerHTML = '<div class="p-3 text-center text-slate-400 fs-7">No products matched.</div>';
                        searchDropdown.classList.remove('d-none');
                    }
                });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.add('d-none');
            }
        });
    }

    // 5. Header Notifications Polling
    function fetchNotifications() {
        const notifBadge = document.getElementById('notifBadgeCount');
        const notifContainer = document.getElementById('notifListContainer');

        if (!notifBadge || !notifContainer) return;

        fetch('/notifications/unread', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.count > 0) {
                    notifBadge.textContent = data.count;
                    notifBadge.classList.remove('d-none');

                    notifContainer.innerHTML = data.notifications.map(n => `
                        <div class="list-group-item bg-slate-900 border-slate-800 p-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-${n.type === 'danger' ? 'danger' : (n.type === 'warning' ? 'warning text-dark' : 'cyan')} rounded-circle p-1"></span>
                                <small class="text-slate-400 fs-8">${n.created_at}</small>
                            </div>
                            <p class="mb-0 fs-7 text-white fw-medium">${n.message}</p>
                        </div>
                    `).join('');
                } else {
                    notifBadge.classList.add('d-none');
                    notifContainer.innerHTML = '<div class="p-4 text-center text-slate-400 fs-7"><i class="fas fa-check-circle text-emerald me-1"></i> No unread notifications.</div>';
                }
            }
        }).catch(() => {});
    }

    fetchNotifications();
    setInterval(fetchNotifications, 15000);

    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function () {
            fetch('/notifications/mark-read', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => fetchNotifications());
        });
    }

    // 6. Dynamic Auto SKU Generator
    const genSkuBtn = document.getElementById('generateSkuBtn');
    const skuInput = document.getElementById('sku');
    if (genSkuBtn && skuInput) {
        genSkuBtn.addEventListener('click', function () {
            const prefix = 'SKU-' + Math.floor(1000 + Math.random() * 9000);
            skuInput.value = prefix;
        });
    }

    // 7. Modals JavaScript Listeners
    document.querySelectorAll('.delete-product-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteProductId').value = this.dataset.id;
            document.getElementById('deleteProductName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
        });
    });

    document.querySelectorAll('.edit-cat-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editCatId').value = this.dataset.id;
            document.getElementById('editCatName').value = this.dataset.name;
            document.getElementById('editCatDesc').value = this.dataset.desc;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        });
    });

    document.querySelectorAll('.delete-cat-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteCatId').value = this.dataset.id;
            document.getElementById('deleteCatName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        });
    });

    document.querySelectorAll('.edit-sup-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editSupId').value = this.dataset.id;
            document.getElementById('editSupName').value = this.dataset.name;
            document.getElementById('editSupPerson').value = this.dataset.person;
            document.getElementById('editSupPhone').value = this.dataset.phone;
            document.getElementById('editSupEmail').value = this.dataset.email;
            document.getElementById('editSupAddress').value = this.dataset.address;
            new bootstrap.Modal(document.getElementById('editSupplierModal')).show();
        });
    });

    document.querySelectorAll('.delete-sup-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteSupId').value = this.dataset.id;
            document.getElementById('deleteSupName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteSupplierModal')).show();
        });
    });

    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('editUserId').value = this.dataset.id;
            document.getElementById('editUserName').value = this.dataset.name;
            document.getElementById('editUserEmail').value = this.dataset.email;
            document.getElementById('editUserRole').value = this.dataset.role;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
    });

    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('deleteUserId').value = this.dataset.id;
            document.getElementById('deleteUserName').textContent = this.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        });
    });

    document.querySelectorAll('.edit-wh-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            alert('Warehouse details: ' + this.dataset.name + ' (' + this.dataset.code + ')');
        });
    });

});
