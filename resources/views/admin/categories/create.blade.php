@extends('admin.layout.master')

@section('title', 'إضافة قسم جديد — لوكس بارفيوم')

@section('page_title', 'إضافة قسم')
@section('page_subtitle', 'إنشاء تصنيف منتجات جديد')

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 800px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>بيانات القسم الجديد</h3>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px">
                <label>صورة القسم</label>
                <input type="file" name="image" accept="image/*">
                @error('image') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>الاسم بالعربية</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar') }}" placeholder="مثال: عطور رجالية" required>
                    @error('name.ar') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>الاسم بالإنجليزية</label>
                    <input type="text" name="name[en]" value="{{ old('name.en') }}" placeholder="Example: Men Perfumes" required>
                    @error('name.en') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>القسم الأب</label>
                <select name="parent_id">
                    <option value="">قسم رئيسي (بدون أب)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> حفظ القسم
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
