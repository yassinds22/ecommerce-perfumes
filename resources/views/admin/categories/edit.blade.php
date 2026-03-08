@extends('admin.layout.master')

@section('title', 'تعديل القسم — لوكس بارفيوم')

@section('page_title', 'تعديل القسم')
@section('page_subtitle', 'تعديل بيانات التصنيف: ' . $category->name)

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 800px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>تعديل بيانات القسم</h3>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 20px">
                <label>صورة القسم الحالية</label>
                @if($category->getFirstMediaUrl('images'))
                    <div style="margin-bottom: 10px">
                        <img src="{{ $category->getFirstMediaUrl('images') }}" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*">
                @error('image') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>الاسم بالعربية</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar', $category->getTranslation('name', 'ar')) }}" placeholder="مثال: عطور رجالية" required>
                    @error('name.ar') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>الاسم بالإنجليزية</label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $category->getTranslation('name', 'en')) }}" placeholder="Example: Men Perfumes" required>
                    @error('name.en') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>القسم الأب</label>
                <select name="parent_id">
                    <option value="">قسم رئيسي (بدون أب)</option>
                    @foreach($parentCategories as $parent)
                        @if($parent->id != $category->id)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> تحديث القسم
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
