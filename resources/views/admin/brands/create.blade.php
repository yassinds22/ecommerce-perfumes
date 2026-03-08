@extends('admin.layout.master')

@section('title', 'إضافة ماركة جديدة — لوكس بارفيوم')

@section('page_title', 'إضافة ماركة')
@section('page_subtitle', 'إنشاء علامة تجارية جديدة')

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 600px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>بيانات الماركة الجديدة</h3>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            
            <div class="form-group">
                <label>اسم الماركة</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="مثال: Chanel" required>
                @error('name') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-top: 20px">
                <label>شعار الماركة</label>
                <input type="file" name="logo" accept="image/*">
                @error('logo') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> حفظ الماركة
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
