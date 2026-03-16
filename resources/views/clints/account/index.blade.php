@extends('clints.account.layout')

@section('title', 'حسابي — نظرة عامة')

@section('account_content')
<div class="overview-section">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2 style="color: #fff; font-size: 1.8rem;">لوحة التحكم</h2>
        <p style="color: var(--color-text-dim);">مرحباً بك، هنا يمكنك متابعة آخر نشاطاتك وإدارة حسابك.</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="stat-box" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; text-align: center;">
            <i class="fas fa-shopping-bag" style="font-size: 2rem; color: var(--color-gold); margin-bottom: 15px;"></i>
            <h4 style="color: var(--color-text-dim); margin-bottom: 5px;">إجمالي الطلبات</h4>
            <div style="font-size: 2rem; color: #fff; font-weight: 700;">{{ $user->orders()->count() }}</div>
        </div>
        <div class="stat-box" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; text-align: center;">
            <i class="fas fa-heart" style="font-size: 2rem; color: #ff4d4d; margin-bottom: 15px;"></i>
            <h4 style="color: var(--color-text-dim); margin-bottom: 5px;">في المفضلة</h4>
            <div style="font-size: 2rem; color: #fff; font-weight: 700;">{{ $wishlistCount }}</div>
        </div>
        <div class="stat-box" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; text-align: center;">
            <i class="fas fa-coins" style="font-size: 2rem; color: #ffd700; margin-bottom: 15px;"></i>
            <h4 style="color: var(--color-text-dim); margin-bottom: 5px;">نقاط الولاء</h4>
            <div style="font-size: 2rem; color: #fff; font-weight: 700;">{{ $user->loyalty_points ?? 0 }}</div>
        </div>
    </div>

    <div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        
        <!-- Recent Orders -->
        <div class="recent-orders-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="color: #fff; font-size: 1.3rem;">آخر الطلبات</h3>
                <a href="{{ route('account.orders.index') }}" style="color: var(--color-gold); font-size: 0.9rem; text-decoration: none;">عرض الكل</a>
            </div>
            
            @forelse($recentOrders as $order)
                <div class="order-item-brief" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div>
                        <div style="color: #fff; font-weight: 600;">#ORD-{{ $order->id }}</div>
                        <div style="color: var(--color-text-dim); font-size: 0.8rem;">{{ $order->created_at->format('Y/m/d') }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: var(--color-gold); font-weight: 700;">{{ number_format($order->total) }} ر.س</div>
                        <span style="font-size: 0.75rem; padding: 3px 10px; border-radius: 20px; background: rgba(201, 168, 76, 0.1); color: var(--color-gold);">
                            @switch($order->status)
                                @case('pending') قيد الانتظار @break
                                @case('processing') قيد التنفيذ @break
                                @case('shipped') تم الشحن @break
                                @case('delivered') تم التوصيل @break
                                @case('cancelled') ملغي @break
                                @default {{ $order->status }}
                            @endswitch
                        </span>
                    </div>
                </div>
            @empty
                <p style="color: var(--color-text-dim); text-align: center; padding: 30px;">لا توجد طلبات سابقة.</p>
            @endforelse
        </div>

        <!-- Default Address & Profile Summary -->
        <div class="info-card" style="display: flex; flex-direction: column; gap: 30px;">
            <!-- Address -->
            <div class="address-summary" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #fff; font-size: 1.3rem;">عنوان الشحن الافتراضي</h3>
                    <a href="{{ route('account.addresses.index') }}" style="color: var(--color-gold); font-size: 0.9rem; text-decoration: none;">تعديل</a>
                </div>
                
                @if($defaultAddress)
                    <div style="color: #fff; line-height: 1.8;">
                        <div style="font-weight: 600; color: var(--color-gold);">{{ $defaultAddress->first_name }} {{ $defaultAddress->last_name }}</div>
                        <div>{{ $defaultAddress->phone }}</div>
                        <div style="color: var(--color-text-dim);">
                            {{ $defaultAddress->address_line1 }}<br>
                            {{ $defaultAddress->city }}, {{ $defaultAddress->state }}
                        </div>
                    </div>
                @else
                    <p style="color: var(--color-text-dim);">لم يتم إضافة عنوان افتراضي بعد.</p>
                    <a href="{{ route('account.addresses.create') }}" style="display: inline-block; margin-top: 10px; color: var(--color-gold); text-decoration: none; font-size: 0.9rem;">
                        <i class="fas fa-plus"></i> إضافة عنوان جديد
                    </a>
                @endif
            </div>

            <!-- Profile Summary -->
            <div class="profile-summary" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="color: #fff; font-size: 1.2rem;">بيانات الحساب</h3>
                    <a href="{{ route('account.profile.index') }}" style="color: var(--color-gold); font-size: 0.9rem; text-decoration: none;">إدارة</a>
                </div>
                <div style="color: var(--color-text-dim);">
                    <strong>الاسم:</strong> {{ $user->name }}<br>
                    <strong>البريد:</strong> {{ $user->email }}<br>
                    <strong>الهاتف:</strong> {{ $user->phone ?? '—' }}
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
