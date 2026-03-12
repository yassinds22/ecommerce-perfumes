@extends('admin.layout.master')

@section('title', 'التحليلات والأداء — لوكس بارفيوم')

@section('content')
<section class="dashboard-section active" id="analytics">
    <div class="section-header mb-4">
        <h2 class="h4">تحليلات المبيعات والأداء</h2>
        <p class="text-dim">نظرة عميقة على أداء متجرك ونمو مبيعاتك</p>
    </div>

    <!-- KPI Cards -->
    <div class="stats-grid staggered-entry">
        <!-- Total Sales -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon revenue"><i class="fas fa-dollar-sign"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 12%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">${{ number_format($kpis['total_sales'], 2) }}</div>
                <div class="stat-card__label">إجمالي المبيعات</div>
            </div>
        </div>

        <!-- Orders Count -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-shopping-bag"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 5%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">{{ number_format($kpis['total_orders']) }}</div>
                <div class="stat-card__label">عدد الطلبات</div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-users"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 8%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">{{ number_format($kpis['total_customers']) }}</div>
                <div class="stat-card__label">إجمالي العملاء</div>
            </div>
        </div>

        <!-- Avg Order Value -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-chart-line"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 3%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">${{ number_format($kpis['avg_order_value'], 2) }}</div>
                <div class="stat-card__label">متوسط قيمة الطلب</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Revenue Trends -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>اتجاهات الإيرادات (آخر 6 أشهر)</h3>
                <div class="chart-tabs">
                    <button class="chart-tab active">شهري</button>
                    <button class="chart-tab">أسبوعي</button>
                </div>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="table-card">
            <div class="table-card__header">
                <h3>المنتجات الأكثر مبيعاً</h3>
            </div>
            <div class="top-list-premium p-3">
                @forelse($topProducts as $item)
                <div class="premium-item">
                    <div class="item-rank">{{ $loop->iteration }}</div>
                    <div class="item-info">
                        <h4>{{ $item->product->name }}</h4>
                        <span>{{ number_format($item->total_qty) }} مبيعات</span>
                    </div>
                    <div class="item-stats text-left">
                        <div class="item-count">${{ number_format($item->total_revenue, 2) }}</div>
                        <div class="item-meta">إيرادات</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-dim">لا توجد بيانات</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Customers Table -->
    <div class="table-card staggered-entry" style="margin-top: 30px">
        <div class="table-card__header">
            <h3>أفضل العملاء</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>البريد الإلكتروني</th>
                    <th class="text-center">عدد الطلبات</th>
                    <th class="text-left">إجمالي الإنفاق</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCustomers as $customer)
                <tr>
                    <td>
                        <div class="user-inline">
                            <span class="avatar-sm">{{ mb_substr($customer->user->name, 0, 1) }}</span>
                            {{ $customer->user->name }}
                        </div>
                    </td>
                    <td class="text-dim">{{ $customer->user->email }}</td>
                    <td class="text-center">{{ $customer->total_orders }}</td>
                    <td class="text-left" style="font-weight: 700; color: var(--color-gold)">${{ number_format($customer->total_spend, 2) }}</td>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Custom gradient for the chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(212, 175, 55, 0.3)');
        gradient.addColorStop(1, 'rgba(212, 175, 55, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrends['labels']) !!},
                datasets: [{
                    label: 'الإيرادات',
                    data: {!! json_encode($revenueTrends['data']) !!},
                    borderColor: '#d4af37',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#d4af37',
                    pointBorderColor: 'rgba(255,255,255,0.2)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#a0a5ba'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#a0a5ba'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
