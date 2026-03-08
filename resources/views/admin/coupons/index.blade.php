@extends('admin.layout.master')

@section('title', 'إدارة الكوبونات — لوكس بارفيوم')

@section('page_title', 'الكوبونات')
@section('page_subtitle', 'إدارة عروض الخصم والكوبونات')

@section('content')
<section class="dashboard-section active">
    <!-- Stats Row -->
    <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon reviews"><i class="fas fa-ticket-alt"></i></div>
            </div>
            <div class="stat-card__value">{{ $coupons->count() }}</div>
            <div class="stat-card__label">إجمالي الكوبونات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card__value">{{ $coupons->where('is_active', true)->count() }}</div>
            <div class="stat-card__label">كوبونات نشطة</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-shopping-cart"></i></div>
            </div>
            <div class="stat-card__value">{{ $coupons->sum('used_count') }}</div>
            <div class="stat-card__label">إجمالي مرات الاستخدام</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card__header">
            <h3>قائمة الكوبونات</h3>
            <div class="table-card__actions">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-gold">
                    <i class="fas fa-plus"></i> إضافة كوبون جديد
                </a>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>النوع</th>
                    <th>القيمة</th>
                    <th>تاريخ الصلاحية</th>
                    <th>النطاق</th>
                    <th>الاستخدام</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>

                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td style="font-weight:700; color:var(--color-gold)">{{ $coupon->code }}</td>
                    <td>
                        <span class="status-badge" style="background: var(--bg-input); color: var(--color-text)">
                            {{ $coupon->type == 'percent' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                        </span>
                    </td>
                    <td style="font-weight:600">
                        {{ $coupon->value }} {{ $coupon->type == 'percent' ? '%' : 'ر.س' }}
                    </td>
                    <td style="font-size: 0.8rem; color: var(--color-text-muted)">
                        @if($coupon->starts_at || $coupon->expires_at)
                            {{ $coupon->starts_at ? $coupon->starts_at->format('Y/m/d') : '...' }} - 
                            {{ $coupon->expires_at ? $coupon->expires_at->format('Y/m/d') : 'مفتوح' }}
                        @else
                            دائم
                        @endif
                    </td>
                    <td>
                        @if($coupon->is_global)
                            <span class="status-badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6 !important">عام</span>
                        @else
                            <span class="status-badge" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6 !important">
                                مخصص ({{ $coupon->products->count() }})
                            </span>
                        @endif
                    </td>

                    <td>
                        <div style="font-size: 0.8rem">
                            {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                        </div>
                        <div style="width: 100px; height: 4px; background: var(--bg-input); border-radius: 2px; margin-top: 4px">
                            @php
                                $percent = $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0;
                            @endphp
                            <div style="width: {{ min($percent, 100) }}%; height: 100%; background: var(--color-gold); border-radius: 2px"></div>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer" title="اضغط لتغيير الحالة">
                                @if($coupon->is_active)
                                    <span class="status-badge success">نشط</span>
                                    @if(!$coupon->isValid())
                                        <i class="fas fa-exclamation-circle" style="color:var(--color-warning); font-size:12px; margin-right:5px" title="تنبيه: الكوبون مفعل لكنه منتهي أو غير صالح حالياً"></i>
                                    @endif
                                @else
                                    <span class="status-badge error">غير مفعل</span>
                                @endif
                            </button>


                        </form>
                    </td>

                    <td>
                        <div style="display:flex; gap:8px">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-outline btn-sm" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" style="color:var(--color-danger)" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px">لا توجد كوبونات مضافة حالياً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
