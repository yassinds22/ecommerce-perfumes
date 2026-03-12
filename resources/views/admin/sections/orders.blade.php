<!-- ===== ORDERS SECTION ===== -->
<section class="dashboard-section {{ ($isActive ?? false) ? 'active' : '' }}" id="orders">
    <div class="stats-grid" style="grid-template-columns: repeat(4,1fr); margin-bottom:24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-clock"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">{{ $orderStats['pending'] ?? 0 }}</div>
            <div class="stat-card__label">قيد الانتظار</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon"
                    style="background:rgba(96,165,250,0.15);color:var(--color-info)"><i
                        class="fas fa-shipping-fast"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">{{ $orderStats['shipped'] ?? 0 }}</div>
            <div class="stat-card__label">تم الشحن</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">{{ $orderStats['completed'] ?? 0 }}</div>
            <div class="stat-card__label">مكتمل</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">{{ $orderStats['cancelled'] ?? 0 }}</div>
            <div class="stat-card__label">ملغي</div>
        </div>
    </div>
    <div class="table-card">
        <div class="table-card__header">
            <h3>جميع الطلبات</h3>
            <div class="table-card__actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-filter"></i> فلتر</button>
                <button class="btn btn-outline btn-sm"><i class="fas fa-download"></i> تصدير</button>
            </div>
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
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                @foreach($orders as $order)
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#{{ $order->order_number }}</td>
                    <td>
                        <div class="table-product__name">{{ $order->user->name ?? 'عميل مجهول' }}</div>
                        <div class="table-product__brand">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td>{{ $order->items_count ?? $order->items->count() }} منتج</td>
                    <td style="font-weight:600">${{ number_format($order->total) }}</td>
                    <td>
                        @php $status = strtolower($order->status); @endphp
                        <span class="status-badge {{ $status }}">
                            @if($status == 'completed') مكتمل
                            @elseif($status == 'pending') قيد الانتظار
                            @elseif($status == 'shipped') تم الشحن
                            @elseif($status == 'processing') قيد المعالجة
                            @elseif($status == 'delivered') تم التسليم
                            @elseif($status == 'cancelled' || $status == 'canceled') ملغي
                            @elseif($status == 'paid') تم الدفع
                            @else {{ $order->status }}
                            @endif
                        </span>
                        @if(strtolower($order->payment_status) == 'paid')
                            <div style="font-size: 0.7rem; color: var(--color-success); margin-top: 2px">
                                <i class="fas fa-check-double"></i> مدفوع
                            </div>
                        @endif
                        @if($order->tracking_number)
                            <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 5px">
                                <i class="fas fa-truck"></i> {{ $order->shipping_company }}: {{ $order->tracking_number }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="table-actions">
                            <button title="عرض" onclick="viewOrder({{ $order->id }})"><i class="fas fa-eye"></i></button>
                            <button title="بيانات الشحن" onclick="editShipping({{ $order->id }}, '{{ $order->shipping_company }}', '{{ $order->tracking_number }}')">
                                <i class="fas fa-shipping-fast"></i>
                            </button>
                            <button title="تغيير الحالة" onclick="editOrderStatus({{ $order->id }}, '{{ $order->status }}')">
                                <i class="fas fa-tasks"></i>
                            </button>
                            @if(in_array(strtolower($order->status), ['processing', 'shipped', 'delivered', 'completed']))
                            <a href="{{ route('orders.invoice.download', $order->id) }}" target="_blank" title="تحميل الفاتورة" class="btn-icon" style="color:var(--color-danger); display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(239, 68, 68, 0.1);">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-footer">
            <span>عرض {{ $orders->count() }} من {{ $orders->total() }} نتيجة</span>
            <div class="pagination">
                {{ $orders->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</section>
