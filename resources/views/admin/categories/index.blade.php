@extends('admin.layout.master')

@section('title', 'إدارة الأقسام — لوكس بارفيوم')

@section('page_title', 'الأقسام')
@section('page_subtitle', 'إدارة تصنيفات المنتجات')

@section('content')
<section class="dashboard-section active">
    <div class="table-card">
        <div class="table-card__header">
            <h3>قائمة الأقسام <span style="color:var(--color-text-muted);font-weight:400">({{ $categories->count() }})</span></h3>
            <div class="table-card__actions">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-gold">
                    <i class="fas fa-plus"></i> إضافة قسم جديد
                </a>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم (AR)</th>
                    <th>الاسم (EN)</th>
                    <th>القسم الأب</th>
                    <th>عدد المنتجات</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        @if($category->getFirstMediaUrl('images'))
                            <img src="{{ $category->getFirstMediaUrl('images') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px">
                        @else
                            <div style="width: 40px; height: 40px; background: var(--color-bg-light); border-radius: 4px; display: flex; align-items: center; justify-content: center">
                                <i class="fas fa-image" style="color: var(--color-text-muted)"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ $category->getTranslation('name', 'ar') }}</td>

                    <td>{{ $category->getTranslation('name', 'en') }}</td>
                    <td>
                        @if($category->parent)
                            <span class="status-badge shipped">{{ $category->parent->name }}</span>
                        @else
                            <span class="status-badge pending">رئيسي</span>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ $category->products_count ?? 0 }}</td>
                    <td>
                        <div style="display:flex; gap:8px">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline btn-sm" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
                    <td colspan="5" style="text-align:center; padding: 40px">لا يوجد أقسام مضافة حالياً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
