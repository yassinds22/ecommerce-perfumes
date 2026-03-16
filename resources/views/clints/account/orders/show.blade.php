@extends('clints.account.layout')

@section('title', 'تفاصيل الطلب #ORD-' . $order->id)

@section('account_content')
<div class="order-detail-section">
    <div class="section-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="color: #fff; font-size: 1.8rem;">تفاصيل الطلب #ORD-{{ $order->id }}</h2>
            <p style="color: var(--color-text-dim);">تم الطلب بتاريخ {{ $order->created_at->format('Y/m/d') }}</p>
        </div>
        <a href="{{ route('account.orders.index') }}" style="color: var(--color-gold); text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-right"></i> العودة للطلبات
        </a>
    </div>

    <!-- Order Items Table -->
    <div class="items-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; margin-bottom: 30px;">
        <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 20px;">المنتجات</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); text-align: right;">
                    <th style="padding: 15px 0; color: var(--color-text-dim); font-weight: 500;">المنتج</th>
                    <th style="padding: 15px 0; color: var(--color-text-dim); font-weight: 500;">السعر</th>
                    <th style="padding: 15px 0; color: var(--color-text-dim); font-weight: 500;">الكمية</th>
                    <th style="padding: 15px 0; color: var(--color-text-dim); font-weight: 500; text-align: left;">المجموع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 20px 0;">
                            <div style="display: flex; gap: 15px; align-items: center;">
                                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                                    <img src="{{ $item->product ? ($item->product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png')) : asset('assets/clints/images/mens-perfume.png') }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="color: #fff; font-weight: 600;">{{ $item->product->name ?? 'منتج غير متوفر' }}</div>
                            </div>
                        </td>
                        <td style="color: var(--color-text-dim);">{{ number_format($item->price) }} ر.س</td>
                        <td style="color: var(--color-text-dim);">{{ $item->quantity }}</td>
                        <td style="color: #fff; font-weight: 700; text-align: left;">{{ number_format($item->price * $item->quantity) }} ر.س</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="order-summary" style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <div style="width: 300px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--color-text-dim);">
                    <span>المجموع الفرعي:</span>
                    <span>{{ number_format($order->total - $order->shipping_cost) }} ر.س</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--color-text-dim);">
                    <span>الشحن:</span>
                    <span>{{ number_format($order->shipping_cost) }} ر.س</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px; color: var(--color-gold); font-size: 1.3rem; font-weight: 800;">
                    <span>الإجمالي:</span>
                    <span>{{ number_format($order->total) }} ر.س</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Info Grid -->
    <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        
        <!-- Status -->
        <div class="status-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px;">
            <h3 style="color: #fff; font-size: 1.2rem; margin-bottom: 20px;">حالة الطلب</h3>
            <div style="padding: 15px; background: rgba(201, 168, 76, 0.05); border: 1px dashed var(--color-gold); border-radius: 10px; color: var(--color-gold); font-weight: 600; text-align: center;">
                @switch($order->status)
                    @case('pending') في انتظار التأكيد والمراجعة @break
                    @case('processing') جارٍ تجهيز طلبك بعناية @break
                    @case('shipped') طلبك في الطريق إليك @break
                    @case('delivered') تم توصيل الطلب بنجاح @break
                    @case('cancelled') عذراً، تم إلغاء الطلب @break
                @endswitch
            </div>
            @if($order->tracking_number)
                <div style="margin-top: 20px; color: var(--color-text-dim); font-size: 0.9rem;">
                    <strong>رقم التتبع:</strong> <span style="color: #fff;">{{ $order->tracking_number }}</span>
                </div>
            @endif
        </div>

        <!-- Shipping Address -->
        <div class="info-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px;">
            <h3 style="color: #fff; font-size: 1.2rem; margin-bottom: 20px;">عنوان الشحن</h3>
            @php $addr = $order->address_details; @endphp
            <div style="color: var(--color-text-dim); line-height: 1.8;">
                <div style="color: #fff; font-weight: 600;">{{ $addr['first_name'] ?? '' }} {{ $addr['last_name'] ?? '' }}</div>
                <div>{{ $addr['phone'] ?? '' }}</div>
                <div>{{ $addr['address_line1'] ?? '' }}</div>
                <div>{{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }}</div>
            </div>
        </div>

    </div>
</div>
@endsection
