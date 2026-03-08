@extends('admin.layout.master')

@section('title', 'إدارة الماركات — لوكس بارفيوم')

@section('page_title', 'الماركات')
@section('page_subtitle', 'إدارة العلامات التجارية للشركاء')

@section('content')
<section class="dashboard-section active">
    <!-- Stats Row for Brands -->
    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon reviews"><i class="fas fa-certificate"></i></div>
            </div>
            <div class="stat-card__value">{{ $brands->count() }}</div>
            <div class="stat-card__label">إجمالي الماركات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-box"></i></div>
            </div>
            <div class="stat-card__value">{{ \App\Models\Product::count() }}</div>
            <div class="stat-card__label">إجمالي المنتجات</div>
        </div>
    </div>

    <div class="table-card" style="max-width: 900px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>قائمة الماركات</h3>
            <div class="table-card__actions">
                <a href="{{ route('admin.brands.create') }}" class="btn btn-gold">
                    <i class="fas fa-plus"></i> إضافة ماركة جديدة
                </a>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>الشعار</th>
                    <th>اسم الماركة</th>
                    <th>الرابط (Slug)</th>
                    <th>عدد المنتجات</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td>
                        @if($brand->getFirstMediaUrl('logos'))
                            <img src="{{ $brand->getFirstMediaUrl('logos') }}" alt="" style="width: 50px; height: 50px; object-fit: contain; background: #fff; padding: 5px; border-radius: 4px">
                        @else
                            <div style="width: 50px; height: 50px; background: var(--color-bg-light); border-radius: 4px; display: flex; align-items: center; justify-content: center">
                                <i class="fas fa-image" style="color: var(--color-text-muted)"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ $brand->name }}</td>
                    <td style="color:var(--color-text-muted)">{{ $brand->slug }}</td>
                    <td>
                        <span class="status-badge pending">
                            {{ $brand->products_count ?? $brand->products()->count() }} منتج
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-outline btn-sm" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الماركة وكل المنتجات التابعة لها؟')">
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
                    <td colspan="5" style="text-align:center; padding: 40px">لا توجد ماركات مضافة حالياً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
