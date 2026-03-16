@extends('clints.account.layout')

@section('title', 'حسابي — طلباتي')

@section('account_content')
<div class="orders-section">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2 style="color: #fff; font-size: 1.8rem;">تاريخ الطلبات</h2>
        <p style="color: var(--color-text-dim);">تتبع وإرشاد طلباتك السابقة والحالية.</p>
    </div>

    @forelse($orders as $order)
        <div class="order-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; margin-bottom: 20px; overflow: hidden;">
            <div class="order-card-header" style="padding: 20px; background: rgba(255, 255, 255, 0.02); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; gap: 30px;">
                    <div>
                        <div style="color: var(--color-text-dim); font-size: 0.8rem; margin-bottom: 5px;">رقم الطلب</div>
                        <div style="color: #fff; font-weight: 700;">#ORD-{{ $order->id }}</div>
                    </div>
                    <div>
                        <div style="color: var(--color-text-dim); font-size: 0.8rem; margin-bottom: 5px;">تاريخ الطلب</div>
                        <div style="color: #fff; font-weight: 600;">{{ $order->created_at->format('Y/m/d') }}</div>
                    </div>
                    <div>
                        <div style="color: var(--color-text-dim); font-size: 0.8rem; margin-bottom: 5px;">إجمالي المبلغ</div>
                        <div style="color: var(--color-gold); font-weight: 700;">{{ number_format($order->total) }} ر.س</div>
                    </div>
                </div>
                <div>
                    <span class="order-status-badge" style="padding: 6px 15px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; 
                        @switch($order->status)
                            @case('pending') background: rgba(255, 193, 7, 0.1); color: #ffc107; @break
                            @case('processing') background: rgba(13, 110, 253, 0.1); color: #0d6efd; @break
                            @case('shipped') background: rgba(201, 168, 76, 0.1); color: var(--color-gold); @break
                            @case('delivered') background: rgba(25, 135, 84, 0.1); color: #198754; @break
                            @case('cancelled') background: rgba(220, 53, 69, 0.1); color: #dc3545; @break
                        @endswitch">
                        @switch($order->status)
                            @case('pending') في انتظار التأكيد @break
                            @case('processing') قيد التجهيز @break
                            @case('shipped') تم الشحن @break
                            @case('delivered') تم التوصيل @break
                            @case('cancelled') ملغي @break
                            @default {{ $order->status }}
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="order-card-body" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div class="order-items-preview" style="color: var(--color-text-dim); font-size: 0.95rem;">
                    {{ $order->items->count() }} منتج (منتجات)
                </div>
                <div class="order-actions">
                    <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline" style="padding: 8px 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); color: #fff; text-decoration: none; font-size: 0.85rem; transition: 0.3s;">عرض التفاصيل</a>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state" style="text-align: center; padding: 60px 0;">
            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: rgba(255,255,255,0.05); margin-bottom: 20px;"></i>
            <h3 style="color: #fff;">لم تقم بأي طلبات بعد</h3>
            <p style="color: var(--color-text-dim); margin-bottom: 25px;">ما رأيك في استكشاف مجموعتنا العطرية المميزة؟</p>
            <a href="{{ url('/') }}" class="btn btn-primary" style="padding: 10px 30px; border-radius: 30px; background: var(--color-gold); color: #000; text-decoration: none; font-weight: 700;">تسوق الآن</a>
        </div>
    @endforelse

    <div class="pagination-wrapper" style="margin-top: 30px;">
        {{ $orders->links() }}
    </div>
</div>

<style>
    .order-card:hover {
        border-color: rgba(201, 168, 76, 0.3);
        transform: translateY(-2px);
        transition: 0.3s;
    }
    .btn-outline:hover {
        background: rgba(255,255,255,0.05);
        border-color: var(--color-gold);
        color: var(--color-gold) !important;
    }
</style>
@endsection
