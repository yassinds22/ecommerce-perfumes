<!-- ===== OVERVIEW SECTION ===== -->
<section class="dashboard-section active" id="overview">

    <!-- Header Stats -->
    <div class="stats-grid staggered-entry">
        <!-- Revenue Card -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon revenue"><i class="fas fa-coins"></i></div>
                <span class="stat-card__trend {{ $trends['revenue'] >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-caret-{{ $trends['revenue'] >= 0 ? 'up' : 'down' }}"></i> {{ abs($trends['revenue']) }}%
                </span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">${{ number_format($stats['total_revenue']) }}</div>
                <div class="stat-card__label">إجمالي المبيعات</div>
            </div>
            <div class="stat-card__mini-chart">
                @for($i = 0; $i < 6; $i++)
                <div class="mini-bar" style="height: {{ rand(30, 90) }}%"></div>
                @endfor
            </div>
        </div>

        <!-- Orders Card -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-shopping-bag"></i></div>
                <span class="stat-card__trend {{ $trends['orders'] >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-caret-{{ $trends['orders'] >= 0 ? 'up' : 'down' }}"></i> {{ abs($trends['orders']) }}%
                </span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">{{ number_format($stats['orders_count'] ?? 0) }}</div>
                <div class="stat-card__label">الطلبات الجديدة</div>
            </div>
            <div class="stat-card__mini-chart">
                @for($i = 0; $i < 6; $i++)
                <div class="mini-bar" style="height: {{ rand(20, 70) }}%; background: var(--color-info)"></div>
                @endfor
            </div>
        </div>

        <!-- Products Card -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-box-open"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 0%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">{{ number_format($stats['active_products'] ?? 0) }}</div>
                <div class="stat-card__label">منتجات نشطة</div>
            </div>
            <div class="stat-card__mini-chart">
                @for($i = 0; $i < 6; $i++)
                <div class="mini-bar" style="height: {{ rand(40, 80) }}%; background: var(--color-success)"></div>
                @endfor
            </div>
        </div>

        <!-- Customers Card -->
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-users"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-caret-up"></i> 0%</span>
            </div>
            <div class="stat-card__body">
                <div class="stat-card__value">{{ number_format($stats['customers_count'] ?? 0) }}</div>
                <div class="stat-card__label">عملاء مسجلين</div>
            </div>
            <div class="stat-card__mini-chart">
                @for($i = 0; $i < 6; $i++)
                <div class="mini-bar" style="height: {{ rand(30, 60) }}%; background: #a855f7"></div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Main Analytics Area -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Sales Performance -->
        <div class="chart-card glass-card" style="flex: 2">
            <div class="chart-card__header">
                <div class="title-with-subtitle">
                    <h3>أداء المبيعات</h3>
                    <span class="subtitle">نظرة عامة على الإيرادات الشهرية</span>
                </div>
                <div class="chart-tabs">
                    <button class="chart-tab active">شهري</button>
                    <button class="chart-tab">أسبوعي</button>
                </div>
            </div>
            <div class="chart-placeholder" id="salesChartContainer" data-sales='@json($salesData)'>
                <div class="chart-bars">
                    @foreach($salesData as $index => $data)
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%; animation-delay: {{ $index * 0.1 }}s" title="${{ number_format($data['value']) }}">
                            <div class="bar-glow"></div>
                        </div>
                        <span class="chart-bar-label">{{ $data['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="chart-card glass-card" style="flex: 1">
            <div class="chart-card__header">
                <h3>توزيع الأقسام</h3>
            </div>
            @php
                $totalProducts = $categoriesStats->sum('count') ?: 1;
            @endphp
            <div class="distribution-list">
                @foreach($categoriesStats->take(5) as $cat)
                @php $percent = round(($cat['count'] / $totalProducts) * 100); @endphp
                <div class="dist-item">
                    <div class="dist-info">
                        <span class="dist-name">{{ $cat['name'] }}</span>
                        <span class="dist-percent">{{ $percent }}%</span>
                    </div>
                    <div class="dist-progress">
                        <div class="dist-fill" style="width: {{ $percent }}%; background: {{ $cat['color'] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Secondary Row: Top Products & Loyalty -->
    <div class="charts-row items-grid staggered-entry" style="margin-top: 30px">
        <!-- Top Products -->
        <div class="table-card glass-card">
            <div class="table-card__header">
                <div class="title-with-icon">
                    <i class="fas fa-crown gold-icon"></i>
                    <h3>الأكثر مبيعاً</h3>
                </div>
            </div>
            <div class="top-list-premium">
                @forelse($topProducts as $product)
                <div class="premium-item">
                    <div class="item-rank">{{ $loop->iteration }}</div>
                    <div class="item-img">
                        <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/images/placeholder.jpg') }}" alt="{{ $product->name }}">
                    </div>
                    <div class="item-info">
                        <h4>{{ $product->name }}</h4>
                        <span>{{ $product->category->name ?? 'قسم عام' }}</span>
                    </div>
                    <div class="item-stats text-left">
                        <div class="item-count">{{ $product->total_sold ?? 0 }}</div>
                        <div class="item-meta">مبيعات</div>
                    </div>
                </div>
                @empty
                <div class="empty-placeholder">لا توجد بيانات متاحة</div>
                @endforelse
            </div>
        </div>

        <!-- Customer Loyalty -->
        <div class="chart-card glass-card">
            <div class="chart-card__header">
                <h3>ولاء العملاء</h3>
            </div>
            @php
                $returningPercent = $customerStats['total'] > 0 ? round(($customerStats['returning'] / $customerStats['total']) * 100) : 0;
            @endphp
            <div class="loyalty-container">
                <div class="donut-chart-complex">
                    <svg viewBox="0 0 36 36" class="circular-chart gold">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle" stroke-dasharray="{{ $returningPercent }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <text x="18" y="20.35" class="percentage">{{ $returningPercent }}%</text>
                    </svg>
                    <div class="donut-info">
                        <div class="info-row">
                            <span class="dot returning"></span>
                            <small>عائدون: {{ $customerStats['returning'] }}</small>
                        </div>
                        <div class="info-row">
                            <span class="dot new"></span>
                            <small>جدد: {{ $customerStats['new'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row: Recent Orders & Activities -->
    <div class="charts-row staggered-entry" style="margin-top: 30px">
        <!-- Recent Orders -->
        <div class="table-card glass-card" style="flex: 2">
            <div class="table-card__header">
                <div class="title-with-icon">
                    <i class="fas fa-shopping-cart" style="color: var(--color-gold)"></i>
                    <h3>أحدث الطلبات</h3>
                </div>
                <a href="#" class="btn btn-outline btn-sm" onclick="document.querySelector('[data-section=orders]').click()">عرض الكل <i class="fas fa-arrow-left"></i></a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders->take(6) as $order)
                    <tr>
                        <td style="font-weight:700; color:var(--color-gold)">#ORD-{{ $order->id }}</td>
                        <td>
                            <div class="user-inline">
                                <span class="avatar-sm">{{ mb_substr($order->user->name ?? '?', 0, 1) }}</span>
                                {{ $order->user->name ?? 'عميل مجهول' }}
                            </div>
                        </td>
                        <td style="font-weight:700">${{ number_format($order->total) }}</td>
                        <td>
                            <span class="status-badge {{ $order->status }}">
                                @if($order->status == 'completed') مكتمل
                                @elseif($order->status == 'pending') قيد الانتظار
                                @elseif($order->status == 'shipped') تم الشحن
                                @else {{ $order->status }}
                                @endif
                            </span>
                        </td>
                        <td class="text-dim">{{ $order->created_at->format('Y/m/d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">لا توجد طلبات حديثة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Activities Timeline -->
        <div class="chart-card glass-card" style="flex: 1">
            <div class="chart-card__header">
                <div class="title-with-icon">
                    <i class="fas fa-history" style="color: var(--color-info)"></i>
                    <h3>سجل النشاطات</h3>
                </div>
                <button class="btn-text">الكل</button>
            </div>
            <div class="activity-timeline-modern mini">
                @foreach($recentActivities->take(6) as $log)
                <div class="timeline-item">
                    <div class="timeline-dot-wrapper">
                        <div class="timeline-dot 
                            @if(str_contains($log->action, 'إضافة')) success 
                            @elseif(str_contains($log->action, 'حذف')) error 
                            @else info @endif">
                        </div>
                    </div>
                    <div class="timeline-content">
                        <div class="content-header">
                            <span class="action-type">{{ $log->action }}</span>
                            <span class="time">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="description">{{ $log->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
