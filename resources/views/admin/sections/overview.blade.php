<!-- ===== OVERVIEW SECTION ===== -->
<section class="dashboard-section active" id="overview">

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon revenue"><i class="fas fa-dollar-sign"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> {{ $trends['revenue'] }}%</span>
            </div>
            <div class="stat-card__value" data-value="${{ number_format($stats['total_revenue']) }}">{{ number_format($stats['total_revenue']) }}</div>
            <div class="stat-card__label">إجمالي الإيرادات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-shopping-bag"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> {{ $trends['orders'] }}%</span>
            </div>
            <div class="stat-card__value" data-value="{{ $stats['orders_count'] }}">{{ $stats['orders_count'] }}</div>
            <div class="stat-card__label">إجمالي الطلبات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-box-open"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> {{ $trends['products'] }}%</span>
            </div>
            <div class="stat-card__value" data-value="{{ $stats['active_products'] }}">{{ $stats['active_products'] }}</div>
            <div class="stat-card__label">المنتجات النشطة</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-users"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> {{ $trends['customers'] }}%</span>
            </div>
            <div class="stat-card__value" data-value="{{ $stats['customers_count'] }}">{{ $stats['customers_count'] }}</div>
            <div class="stat-card__label">العملاء المسجلين</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-row">
        <!-- Revenue Chart -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>المبيعات الشهرية</h3>
                <div class="chart-tabs">
                    <button class="chart-tab active">شهري</button>
                    <button class="chart-tab">أسبوعي</button>
                </div>
            </div>
            <div class="chart-placeholder" id="salesChartContainer" 
                 data-sales='@json($salesData)'>
                <div class="chart-bars">
                    @foreach($salesData as $data)
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%" title="${{ number_format($data['value']) }}"></div>
                        <span class="chart-bar-label">{{ $data['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Categories Donut -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>التصنيفات</h3>
            </div>
            @php
                $totalProducts = $stats['active_products'];
                $conicParts = [];
                $currentPercent = 0;
                foreach($categoriesStats as $cat) {
                    $percent = $totalProducts > 0 ? ($cat['count'] / $totalProducts) * 100 : 0;
                    $conicParts[] = "{$cat['color']} {$currentPercent}% " . ($currentPercent + $percent) . "%";
                    $currentPercent += $percent;
                }
                $conicGradient = count($conicParts) > 0 ? implode(', ', $conicParts) : '#8b8fa3 0% 100%';
            @endphp
            <div class="donut-chart">
                <div class="donut-visual"
                    style="background: conic-gradient({{ $conicGradient }})">
                    <div class="donut-center">
                        <strong>{{ $totalProducts }}</strong>
                        <span>منتج</span>
                    </div>
                </div>
                <div class="donut-legend">
                    @foreach($categoriesStats as $cat)
                    <div class="legend-item">
                        <div class="legend-dot" style="background:{{ $cat['color'] }}"></div>{{ $cat['name'] }} <span>{{ $totalProducts > 0 ? round(($cat['count'] / $totalProducts) * 100) : 0 }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="table-card">
        <div class="table-card__header">
            <h3>أحدث الطلبات</h3>
            <a href="#" class="btn btn-outline btn-sm"
                onclick="document.querySelector('[data-section=orders]').click()">عرض الكل <i
                    class="fas fa-arrow-left"></i></a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المنتجات</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'عميل مجهول' }}</td>
                    <td>{{ $order->items_count ?? $order->products->count() }} منتج</td>
                    <td style="font-weight:600">${{ number_format($order->total) }}</td>
                    <td>
                        <span class="status-badge {{ $order->status }}">
                            @if($order->status == 'completed') مكتمل
                            @elseif($order->status == 'pending') قيد الانتظار
                            @elseif($order->status == 'shipped') تم الشحن
                            @else {{ $order->status }}
                            @endif
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
