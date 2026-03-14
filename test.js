
    document.addEventListener('DOMContentLoaded', function() {
        let salesChart = null;
        let distributionChart = null;
        const filterForm = document.getElementById('reportFilterForm');
        const reportTableBody = document.getElementById('table-body');
        const reportTableHead = document.getElementById('table-head');
        const loader = document.getElementById('table-loader');
        const emptyState = document.getElementById('empty-state');
        const tableContainer = document.getElementById('report-table-container');

        const initialSalesData = [];
        const initialPaymentData = [];
        const initialReportData = [];
        const initialReportType = """";

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
                        label: 'الإيرادات',
                        data: trends.map(t => t.revenue),
                        borderColor: '#d4af37',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'عدد الطلبات',
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
                        headers = ['التاريخ', 'عدد الطلبات', 'الإيرادات', 'القطع المباعة'];
                        if (data && data.length > 0) {
                            rows = data.map(d => `<tr><td>${d.date}</td><td>${d.orders_count || d.count || 0}</td><td>$${parseFloat(d.revenue || d.total_value || 0).toLocaleString()}</td><td>${d.items_sold || 0}</td></tr>`);
                        } else {
                            // Fallback rendering
                            rows = [`<tr><td colspan="4" class="text-center text-dim">جارِ تهيئة بيانات المبيعات المباشرة...</td></tr>`];
                        }
                        break;
                    case 'orders_status':
                        headers = ['الحالة', 'عدد الطلبات', 'القيمة الإجمالية'];
                        rows = data.map(d => `<tr><td><span class="badge badge-dim">${d.status}</span></td><td>${d.count}</td><td>$${parseFloat(d.total_value).toLocaleString()}</td></tr>`);
                        break;
                    case 'inventory':
                        headers = ['SKU', 'الاسم', 'القسم', 'المخزون', 'السعر', 'الحالة'];
                        rows = data.map(d => `<tr><td><code>${d.sku}</code></td><td>${d.name}</td><td>${d.category}</td><td>${d.stock}</td><td>$${parseFloat(d.price).toLocaleString()}</td><td><span class="badge ${d.status === 'Active' ? 'badge-success' : 'badge-danger'}">${d.status}</span></td></tr>`);
                        break;
                    case 'top_products':
                        headers = ['المنتج', 'القسم', 'البراند', 'الكمية المباعة', 'الإيراد'];
                        rows = data.map(d => `<tr><td>${d.name || d.product_name}</td><td>${d.category || 'N/A'}</td><td>${d.brand || 'N/A'}</td><td>${d.total_sold || d.total_quantity}</td><td>$${parseFloat(d.revenue || d.total_revenue).toLocaleString()}</td></tr>`);
                        break;
                    case 'profit':
                        headers = ['التاريخ', 'الإيرادات', 'التكلفة (تقديري)', 'الربح الصافي', 'المارجن %'];
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
            alert('تم الضغط على زر التحديث. جاري إرسال طلب للحصول على البيانات...');
            loader.style.display = 'block';
            tableContainer.style.opacity = '0.5';

            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            
            fetch(`${window.location.pathname}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                console.log('AJAX Response Status:', res.status);
                return res.json();
            })
            .then(data => {
                console.log('AJAX Data Received:', data);
                // Update KPIs safely
                updateKPICard('kpi-total-revenue', `$${parseFloat(data.sales.total_revenue).toLocaleString()}`);
                updateKPICard('kpi-orders-count', data.sales.orders_count);
                updateKPICard('kpi-items-sold', data.sales.items_sold);
                updateKPICard('kpi-avg-order', `$${parseFloat(data.sales.avg_order_value).toLocaleString()}`);
                updateKPICard('kpi-profit', `$${parseFloat(data.sales.total_revenue * 0.4).toLocaleString()}`);
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
                alert('فشل تحديث البيانات. يرجى مراجعة لوحة تحكم المتصفح (Console) لمعرفة السبب.');
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

        document.getElementById('exportBtn').addEventListener('click', () => {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            window.location.href = `""?${params.toString()}`;
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

