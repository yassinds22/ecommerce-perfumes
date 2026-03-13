@extends('admin.layout.master')

@section('title', 'تقارير المتجر الشاملة — لوكس بارفيوم')

@section('content')
<section class="dashboard-section active" id="reports">
    <div class="section-header mb-4" style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 class="h4">تقارير المتجر الشاملة</h2>
            <p class="text-dim">تحليل دقيق للأداء، المبيعات، المخزون، وسلوك العملاء</p>
        </div>
        <div class="header__actions" style="display: flex; gap: 10px;">
            <div class="dropdown">
                <button class="btn btn-outline btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-file-export"></i> تصدير المبيعات
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.reports.export.sales', array_merge(request()->all(), ['format' => 'csv'])) }}"><i class="fas fa-file-csv"></i> CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.reports.export.sales', array_merge(request()->all(), ['format' => 'pdf'])) }}"><i class="fas fa-file-pdf"></i> PDF</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.reports.export.inventory') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-boxes"></i> تصدير حالة المخزون
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <x-report.filter :categories="$categories" :brands="$brands" />



  
</section>

<section class="dashboard-section active" id="reports-results">
    <!-- Sales & Revenue KPI Row -->
    <div class="section-label mb-3 mt-4">نظرة عامة على المبيعات (مقارنة)</div>
    <div class="stats-grid staggered-entry">
        <x-report.kpi-card 
            title="إجمالي المبيعات" 
            :value="'$' . number_format($sales['total_revenue'], 2)" 
            :trend="round($sales['revenue_growth'], 1)"
            icon="fas fa-wallet" 
            type="revenue" 
        />
        <x-report.kpi-card 
            title="عدد الطلبات" 
            :value="number_format($sales['orders_count'])" 
            icon="fas fa-shopping-cart" 
            type="orders" 
        />
        <x-report.kpi-card 
            title="متوسط قيمة الطلب" 
            :value="'$' . number_format($sales['avg_order_value'], 2)" 
            icon="fas fa-chart-line" 
            type="revenue" 
        />
        <x-report.kpi-card 
            title="الشحن والضرائب" 
            :value="'$' . number_format($sales['total_tax'] + $sales['total_shipping'], 2)" 
            icon="fas fa-truck" 
            type="orders" 
        />
    </div>

    <!-- Customers & Loyalty KPI Row -->
    <div class="section-label mb-3 mt-5">العملاء والولاء</div>
    <div class="stats-grid staggered-entry">
        <x-report.kpi-card 
            title="عملاء جدد" 
            :value="number_format($customers['new_customers'])" 
            icon="fas fa-user-plus" 
            type="customers" 
        />
        <x-report.kpi-card 
            title="عملاء دائمون" 
            :value="number_format($customers['returning_customers'])" 
            icon="fas fa-user-check" 
            type="customers" 
        />
        <x-report.kpi-card 
            title="نقاط الولاء الممنوحة" 
            :value="number_format($customers['loyalty_points']['earned'])" 
            icon="fas fa-coins" 
            type="marketing" 
        />
        <x-report.kpi-card 
            title="نقاط تم استبدالها" 
            :value="number_format($customers['loyalty_points']['redeemed'])" 
            icon="fas fa-exchange-alt" 
            type="marketing" 
        />
    </div>

    <!-- Charts Row -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Sales Trends -->
        <x-report.chart 
            id="salesTrendsChart" 
            title="اتجاهات المبيعات اليومية" 
            subtitle="مراقبة حركة الإيرادات خلال الفترة المحددة"
        />

        <!-- Payment Methods Distribution -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>طرق الدفع</h3>
            </div>
            <div class="distribution-list p-3" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                <canvas id="paymentMethodsChart"></canvas>
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

    <!-- Advanced Tables Row -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Top Customers -->
        <div class="table-card">
            <div class="table-card__header">
                <h3>أفضل العملاء (حسب الإنفاق)</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th class="text-left">إجمالي الإنفاق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers['top_spenders'] as $spender)
                    <tr>
                        <td>
                            <div class="user-inline">
                                <span class="avatar-sm">{{ mb_substr($spender->user->name ?? '?', 0, 1) }}</span>
                                {{ $spender->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="text-left" style="font-weight: 700; color: var(--color-gold)">
                            ${{ number_format($spender->lifetime_value, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Inventory List -->
    <div class="table-card mt-5 staggered-entry">
        <div class="table-card__header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>تقرير المخزون التفصيلي</h3>
            <div class="search-box" style="width: 300px;">
                <input type="text" id="inventorySearch" class="form-control" placeholder="بحث باسم المنتج أو SKU...">
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>الاسم</th>
                    <th>القسم</th>
                    <th>المخزون</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($full_inventory as $product)
                <tr>
                    <td><code>{{ $product['sku'] }}</code></td>
                    <td style="font-weight: 500;">{{ $product['name'] }}</td>
                    <td><span class="badge badge-dim">{{ $product['category'] }}</span></td>
                    <td>
                        <span class="{{ $product['stock'] <= 5 ? 'text-danger fw-bold' : '' }}">
                            @if($product['stock'] <= 5)
                                <i class="fas fa-exclamation-triangle me-1"></i>
                            @endif
                            {{ $product['stock'] }} وحدة
                        </span>
                    </td>
                    <td style="color: var(--color-gold); font-weight: 600;">${{ number_format($product['price'], 2) }}</td>
                    <td>
                        <span class="status-badge {{ $product['status'] == 'Active' ? 'status-active' : 'status-inactive' }}">
                            {{ $product['status'] == 'Active' ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Trends Chart
        const salesCtx = document.getElementById('salesTrendsChart').getContext('2d');
        const trendsData = {!! json_encode($sales['trends']) !!};
        const labels = trendsData.map(t => t.date);
        const data = trendsData.map(t => t.revenue);

        const gradient = salesCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(212, 175, 55, 0.3)');
        gradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['No Data'],
                datasets: [{
                    label: 'الإيرادات اليومية',
                    data: data.length ? data : [0],
                    borderColor: '#d4af37',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#d4af37',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#a0a5ba' } },
                    x: { grid: { display: false }, ticks: { color: '#a0a5ba' } }
                }
            }
        });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const paymentData = {!! json_encode($sales['payment_methods']) !!};
        
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentData.map(p => p.payment_method),
                datasets: [{
                    data: paymentData.map(p => p.count),
                    backgroundColor: ['#d4af37', '#4b49ac', '#a0a5ba', '#ff4747'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#a0a5ba', padding: 20, font: { size: 12 } }
                    }
                }
            }
        });
        // Inventory Live Search
        const searchInput = document.getElementById('inventorySearch');
        const inventoryTable = document.querySelector('.data-table tbody');
        
        searchInput.addEventListener('keyup', function() {
            const term = searchInput.value.toLowerCase();
            const rows = inventoryTable.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });
</script>
<style>
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
