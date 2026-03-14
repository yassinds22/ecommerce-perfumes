@extends('admin.layout.master')

@section('title', 'تقارير المتجر الشاملة — لوكس بارفيوم')

@section('content')
<section class="dashboard-section active" id="reports">
    <div class="section-header mb-4" style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 class="h4">Store Comprehensive Reports</h2>
            <p class="text-dim">Detailed analysis of performance, sales, inventory, and customer behavior</p>
        </div>
        <div></div>
    </div>

    <!-- Filters Section -->
    <x-report.filter :categories="$categories" :brands="$brands" />



  
</section>

<section class="dashboard-section active" id="reports-results">
    <!-- Sales & Revenue KPI Row -->
    <div class="section-label mb-3 mt-4">Performance Overview</div>
    <div class="stats-grid staggered-entry" id="kpi-container">
        <x-report.kpi-card 
            id="kpi-total-revenue"
            title="Total Revenue" 
            :value="'$' . number_format($sales['total_revenue'], 2)" 
            :trend="round($sales['revenue_growth'], 1)"
            icon="fas fa-wallet" 
            type="revenue" 
            color="#d4af37"
        />
        <x-report.kpi-card 
            id="kpi-orders-count"
            title="Total Orders" 
            :value="number_format($sales['orders_count'])" 
            icon="fas fa-shopping-cart" 
            type="orders" 
            color="#4b49ac"
        />
        <x-report.kpi-card 
            id="kpi-items-sold"
            title="Items Sold" 
            :value="number_format($sales['items_sold'] ?? 0)" 
            icon="fas fa-box-open" 
            type="products" 
            color="#2ecc71"
        />
        <x-report.kpi-card 
            id="kpi-avg-order"
            title="Avg Order Value" 
            :value="'$' . number_format($sales['avg_order_value'], 2)" 
            icon="fas fa-chart-line" 
            type="revenue" 
            color="#f1c40f"
        />
    </div>

    <!-- Profit & Customers KPI Row -->
    <div class="section-label mb-3 mt-5">Profits & Customers</div>
    <div class="stats-grid staggered-entry" id="kpi-container-2">
        <x-report.kpi-card 
            id="kpi-profit"
            title="Total Profit" 
            :value="'$' . number_format($sales['total_profit'] ?? 0, 2)" 
            icon="fas fa-hand-holding-usd" 
            type="profit" 
            color="#e67e22"
        />
        <x-report.kpi-card 
            id="kpi-new-customers"
            title="New Customers" 
            :value="number_format($customers['new_customers'])" 
            icon="fas fa-user-plus" 
            type="customers" 
            color="#3498db"
        />
        <x-report.kpi-card 
            id="kpi-returning-customers"
            title="Returning Customers" 
            :value="number_format($customers['returning_customers'])" 
            icon="fas fa-user-check" 
            type="customers" 
            color="#9b59b6"
        />
        <x-report.kpi-card 
            id="kpi-low-stock"
            title="Stock Alerts" 
            :value="number_format($inventory['low_stock_count'])" 
            icon="fas fa-exclamation-triangle" 
            type="products" 
            color="#e74c3c"
        />
    </div>

    <!-- Charts Row -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Sales Trends -->
        <div class="chart-card" style="flex: 2;">
            <div class="chart-card__header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3>اتجاهات المبيعات والأوامر</h3>
                    <p class="text-dim">مراقبة حركة الإيرادات وحجم الطلبات</p>
                </div>
                <div class="chart-actions">
                    <select id="chart-aggregation" class="form-control form-control-sm">
                        <option value="daily">يومي</option>
                        <option value="weekly">أسبوعي</option>
                        <option value="monthly">شهري</option>
                    </select>
                </div>
            </div>
            <div class="chart-container p-3" style="min-height: 350px;">
                <canvas id="salesTrendsChart"></canvas>
            </div>
        </div>

        <!-- Distribution Chart -->
        <div class="chart-card" style="flex: 1;">
            <div class="chart-card__header">
                <h3>توزيع الطلبات</h3>
            </div>
            <div class="distribution-list p-3" style="min-height: 350px; display: flex; align-items: center; justify-content: center;">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Inventory & Products Row -->
    <div class="section-label mb-3 mt-5">المخزون والمنتجات</div>
    <div class="stats-grid staggered-entry">
        <x-report.kpi-card 
            title="إجمالي المنتجات" 
            :value="number_format($inventory['total_products'])" 
            icon="fas fa-box" 
            type="products" 
        />
        <x-report.kpi-card 
            title="منتجات قريبة من النفاد" 
            :value="number_format($inventory['low_stock_count'])" 
            icon="fas fa-exclamation-triangle" 
            type="customers" 
        />
        <x-report.kpi-card 
            title="منتجات نفدت" 
            :value="number_format($inventory['out_of_stock_count'])" 
            icon="fas fa-times-circle" 
            type="customers" 
        />
        <x-report.kpi-card 
            title="أداء الأقسام (طلبات)" 
            :value="number_format($sales['orders_count'])" 
            icon="fas fa-check-circle" 
            type="products" 
        />
    </div>

    <!-- Dynamic Report Results Table -->
    <div class="table-card mt-5 staggered-entry">
        <div class="table-card__header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 id="report-title">Daily Sales Report</h3>
            <div class="header__actions" style="display: flex; gap: 10px;">
                <div class="search-box" style="width: 250px;">
                    <input type="text" id="reportSearch" class="form-control" placeholder="Search in results...">
                </div>
            </div>
        </div>
        <div id="table-loader" style="display:none; text-align:center; padding: 40px;">
            <div class="spinner-border text-gold" role="status"></div>
            <p class="mt-2 text-dim">Loading data...</p>
        </div>
        <div id="report-table-container">
            <table class="data-table" id="dynamic-report-table">
                <thead>
                    <tr id="table-head">
                        <!-- Headings will be injected here -->
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Data will be injected here -->
                </tbody>
            </table>
        </div>
        <div id="empty-state" style="display:none; text-align:center; padding: 60px;">
            <i class="fas fa-chart-bar fa-3x text-dim mb-3"></i>
            <p class="text-dim">No data available for the selected date range</p>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let salesChart = null;
        let distributionChart = null;
        const filterForm = document.getElementById('reportFilterForm');
        const reportTableBody = document.getElementById('table-body');
        const reportTableHead = document.getElementById('table-head');
        const loader = document.getElementById('table-loader');
        const emptyState = document.getElementById('empty-state');
        const tableContainer = document.getElementById('report-table-container');

        const initialSalesData = {!! json_encode($sales['trends'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?: '[]' !!};
        const initialPaymentData = {!! json_encode($sales['payment_methods'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?: '[]' !!};
        const initialReportData = {!! json_encode($reportData ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?: '[]' !!};
        const initialReportType = "{{ $reportType ?? 'daily_sales' }}";

        // Initialize Charts
        function initCharts(trends, distribution) {
            try {
                if (trends && !Array.isArray(trends)) trends = Object.values(trends);
                if (distribution && !Array.isArray(distribution)) distribution = Object.values(distribution);

                const ctx = document.getElementById('salesTrendsChart').getContext('2d');
                const distCtx = document.getElementById('distributionChart').getContext('2d');

                if(salesChart) salesChart.destroy();
                if(distributionChart) distributionChart.destroy();

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(212, 175, 55, 0.3)');
            gradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trends.map(t => t.date),
                    datasets: [{
                        label: 'Revenue',
                        data: trends.map(t => t.revenue),
                        borderColor: '#d4af37',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'Orders Count',
                        data: trends.map(t => t.orders_count || 0),
                        borderColor: '#4b49ac',
                        borderWidth: 2,
                        pointRadius: 3,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: '#a0a5ba' } } },
                    scales: {
                        y: { type: 'linear', display: true, position: 'left', grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#a0a5ba' } },
                        y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { color: '#a0a5ba' } },
                        x: { grid: { display: false }, ticks: { color: '#a0a5ba' } }
                    }
                }
            });

            distributionChart = new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: distribution.map(p => p.payment_method || p.status || 'Other'),
                    datasets: [{
                        data: distribution.map(p => p.count),
                        backgroundColor: ['#d4af37', '#4b49ac', '#2ecc71', '#e74c3c', '#f1c40f', '#9b59b6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'bottom', labels: { color: '#a0a5ba', padding: 15 } } }
                }
            });
            } catch (e) {
                console.error('Error in initCharts:', e);
            }
        }

        function updateTable(type, data) {
            console.log('Updating Table:', type, data);
            
            if (data && !Array.isArray(data)) {
                data = Object.values(data);
            }

            reportTableHead.innerHTML = '';
            reportTableBody.innerHTML = '';
            
            try {
                if(!data || data.length === 0) {
                    emptyState.style.display = 'block';
                    tableContainer.style.display = 'none';
                    return;
                }

                emptyState.style.display = 'none';
                tableContainer.style.display = 'block';

                let headers = [];
                let rows = [];

                switch(type) {
                    case 'daily_sales':
                        headers = ['Date', 'Orders', 'Revenue', 'Items Sold'];
                        if (data && data.length > 0) {
                            rows = data.map(d => `<tr><td>${d.date}</td><td>${d.orders_count || d.count || 0}</td><td>$${parseFloat(d.revenue || d.total_value || 0).toLocaleString()}</td><td>${d.items_sold || 0}</td></tr>`);
                        } else {
                            // Fallback rendering
                            rows = [`<tr><td colspan="4" class="text-center text-dim">Initializing real-time data...</td></tr>`];
                        }
                        break;
                    case 'orders_status':
                        headers = ['Status', 'Orders', 'Total Value'];
                        rows = data.map(d => `<tr><td><span class="badge badge-dim">${d.status}</span></td><td>${d.count}</td><td>$${parseFloat(d.total_value).toLocaleString()}</td></tr>`);
                        break;
                    case 'inventory':
                        headers = ['SKU', 'Name', 'Category', 'Stock', 'Price', 'Status'];
                        rows = data.map(d => `<tr><td><code>${d.sku}</code></td><td>${d.name}</td><td>${d.category}</td><td>${d.stock}</td><td>$${parseFloat(d.price).toLocaleString()}</td><td><span class="badge ${d.status === 'Active' ? 'badge-success' : 'badge-danger'}">${d.status}</span></td></tr>`);
                        break;
                    case 'top_products':
                        headers = ['Product', 'Category', 'Brand', 'Sold Qty', 'Revenue'];
                        rows = data.map(d => `<tr><td>${d.name || d.product_name}</td><td>${d.category || 'N/A'}</td><td>${d.brand || 'N/A'}</td><td>${d.total_sold || d.total_quantity}</td><td>$${parseFloat(d.revenue || d.total_revenue).toLocaleString()}</td></tr>`);
                        break;
                    case 'profit':
                        headers = ['Date', 'Revenue', 'Cost', 'Profit', 'Margin %'];
                        rows = data.map(d => `<tr><td>${d.date}</td><td>$${parseFloat(d.revenue).toLocaleString()}</td><td>$${parseFloat(d.estimated_cost || d.cost || 0).toLocaleString()}</td><td class="text-success">$${parseFloat(d.profit).toLocaleString()}</td><td>${parseFloat(d.profit_margin || 0).toFixed(1)}%</td></tr>`);
                        break;
                }

                if (headers.length > 0) {
                    reportTableHead.innerHTML = headers.map(h => `<th>${h}</th>`).join('');
                    reportTableBody.innerHTML = rows.join('');
                }

                const titleEl = document.getElementById('report-title');
                const optionEl = document.querySelector(`#report_type option[value="${type}"]`);
                if (titleEl && optionEl) {
                    titleEl.innerText = optionEl.text;
                }
            } catch (e) {
                console.error('Table Update Error:', e);
            }
        }

        // Helper to update KPI Card safely
        function updateKPICard(id, value) {
            const card = document.getElementById(id);
            if (card) {
                const valueEl = card.querySelector('.stat-card__value');
                if (valueEl) valueEl.innerText = value;
            }
        }

        function refreshData() {
            loader.style.display = 'block';
            tableContainer.style.opacity = '0.5';

            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            
            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                // Update KPIs safely
                updateKPICard('kpi-total-revenue', `$${parseFloat(data.sales.total_revenue).toLocaleString()}`);
                updateKPICard('kpi-orders-count', data.sales.orders_count);
                updateKPICard('kpi-items-sold', data.sales.items_sold);
                updateKPICard('kpi-avg-order', `$${parseFloat(data.sales.avg_order_value).toLocaleString()}`);
                updateKPICard('kpi-profit', `$${parseFloat(data.sales.total_profit).toLocaleString()}`);
                updateKPICard('kpi-new-customers', data.customers.new_customers);
                updateKPICard('kpi-returning-customers', data.customers.returning_customers);
                updateKPICard('kpi-low-stock', data.inventory.low_stock_count);

                console.log('KPIs updated, initializing charts...');
                // Update Charts
                initCharts(data.sales.trends, data.reportType === 'orders_status' ? data.reportData : data.sales.payment_methods);
                
                console.log('Charts initialized, updating table...');
                // Update Table
                updateTable(data.reportType, data.reportData);

                // Update URL
                history.pushState(null, '', `?${params.toString()}`);
            })
            .catch(err => {
                console.error('Failed to refresh data:', err);
                alert('Data refresh failed. Please check the browser console for more details.');
            })
            .finally(() => {
                loader.style.display = 'none';
                tableContainer.style.opacity = '1';
            });
        }

        // Debounce logic for input changes
        let debounceTimer;
        filterForm.addEventListener('change', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(refreshData, 300);
        });

        // Prevent full page reload on submit button click and use AJAX instead
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            refreshData();
        });

        const exportBtnMain = document.getElementById('exportBtnMain');
        const exportMenu = document.getElementById('exportMenu');

        if (exportBtnMain) {
            console.log('Export button found');
            exportBtnMain.addEventListener('click', (e) => {
                console.log('Export main button clicked');
                e.preventDefault();
                e.stopPropagation();
                exportMenu.classList.toggle('show');
                console.log('Menu classList:', exportMenu.classList);
            });
        } else {
            console.error('Export button NOT found');
        }

        document.addEventListener('click', () => {
            if (exportMenu && exportMenu.classList.contains('show')) {
                console.log('Closing export menu on outside click');
                exportMenu.classList.remove('show');
            }
        });

        document.querySelectorAll('.export-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const format = this.getAttribute('data-format');
                console.log('Export format selected:', format);
                
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                params.append('format', format);
                
                const url = `{{ route('admin.reports.export') }}?${params.toString()}`;
                console.log('Redirecting to:', url);
                window.location.href = url;
            });
        });

        // Initialize with initial data
        try {
            initCharts(initialSalesData, initialPaymentData);
        } catch (e) {
            console.error('Error initializing charts:', e);
        }
        
        try {
            updateTable(initialReportType, initialReportData);
        } catch (e) {
            console.error('Error updating initial table:', e);
        }

        // Search logic
        document.getElementById('reportSearch').addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const rows = reportTableBody.querySelectorAll('tr');
            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    });
</script>
<style>
    body {
        overflow: auto !important;
    }
    .section-label {
        font-weight: 700;
        color: var(--color-gold);
        font-family: var(--font-heading);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--glass-border);
    }
</style>
@endpush
@endsection
