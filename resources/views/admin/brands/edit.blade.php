@extends('admin.layout.master')

@section('title', 'تعديل الماركة — لوكس بارفيوم')

@section('page_title', 'تعديل ماركة')
@section('page_subtitle', 'تعديل بيانات العلامة التجارية')

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 600px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>بيانات الماركة</h3>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>اسم الماركة</label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}" required>
                @error('name') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-top: 20px">
                <label>تحديث الشعار</label>
                @if($brand->getFirstMediaUrl('logos'))
                    <div style="margin-bottom: 10px">
                        <img src="{{ $brand->getFirstMediaUrl('logos') }}" alt="" style="width: 100px; height: 100px; object-fit: contain; background: #fff; padding: 10px; border-radius: 8px">
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*">
                @error('logo') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> تحديث الماركة
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
