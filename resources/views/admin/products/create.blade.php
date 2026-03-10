@extends('admin.layout.master')

@section('title', 'إضافة منتج جديد — لوكس بارفيوم')

@section('page_title', 'إضافة منتج')
@section('page_subtitle', 'إضافة منتج جديد للمخزون')

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 1000px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>تفاصيل المنتج الجديد</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label>الاسم بالعربية</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar') }}" placeholder="مثال: عطر عنبر عود" required>
                    @error('name.ar') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>الاسم بالإنجليزية</label>
                    <input type="text" name="name[en]" value="{{ old('name.en') }}" placeholder="Example: Amber Oud" required>
                    @error('name.en') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>التصنيف</label>
                    <select name="category_id" required>
                        <option value="">اختر التصنيف</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>الماركة</label>
                    <select name="brand_id" required>
                        <option value="">اختر الماركة</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>السعر الأساسي ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                    @error('price') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>سعر العرض ($) - اختياري</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00">
                    @error('sale_price') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>رمز المنتج (SKU)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="E.g. PRD-001" required>
                    @error('sku') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>المخزون المتوفر</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required>
                    @error('stock_quantity') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>حد التنبيه (المخزون المنخفض)</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="0" required>
                    @error('low_stock_threshold') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>الفئة المستهدفة</label>
                    <select name="gender" required>
                        <option value="Men" {{ old('gender') == 'Men' ? 'selected' : '' }}>رجالي</option>
                        <option value="Women" {{ old('gender') == 'Women' ? 'selected' : '' }}>نسائي</option>
                        <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>للجنسين</option>
                        <option value="Kids" {{ old('gender') == 'Kids' ? 'selected' : '' }}>أطفال</option>
                    </select>
                    @error('gender') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>صورة المنتج الأساسية</label>
                    <input type="file" name="image" accept="image/*">
                    @error('image') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>معرض الصور (Gallery)</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple>
                    <small style="color:var(--color-text-muted)">يمكنك اختيار أكثر من صورة</small>
                </div>
            </div>


            <div style="margin-top: 24px; padding: 20px; background: rgba(255, 255, 255, 0.02); border-radius: 12px; border: 1px solid var(--color-border)">
                <h4 style="margin-bottom: 20px; color: var(--color-gold); display: flex; align-items: center; gap: 10px">
                    <i class="fas fa-layer-group"></i> الهرم العطري (مكونات العطر)
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>مكونات القمة (Top Notes)</label>
                        <select name="top_notes[]" multiple class="form-input" style="height: 120px">
                            @foreach($notes as $note)
                                <option value="{{ $note->id }}">{{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})</option>
                            @endforeach
                        </select>
                        <small style="color:var(--color-text-muted)">يمكنك اختيار أكثر من مكون (Control + Click)</small>
                    </div>
                    <div class="form-group">
                        <label>مكونات القلب (Middle Notes)</label>
                        <select name="middle_notes[]" multiple class="form-input" style="height: 120px">
                            @foreach($notes as $note)
                                <option value="{{ $note->id }}">{{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row" style="margin-top: 16px">
                    <div class="form-group">
                        <label>مكونات القاعدة (Base Notes)</label>
                        <select name="base_notes[]" multiple class="form-input" style="height: 120px">
                            @foreach($notes as $note)
                                <option value="{{ $note->id }}">{{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: center; opacity: 0.5">
                        <div style="text-align: center">
                            <i class="fas fa-info-circle fa-2x" style="margin-bottom: 10px; color: var(--color-gold)"></i>
                            <p style="font-size: 0.8rem">يتم عرض هذه المكونات في صفحة المنتج في المتجر لمساعدة العميل على فهم تركيبة العطر.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">

                <label>وصف قصير (AR)</label>
                <textarea name="short_description[ar]" rows="2">{{ old('short_description.ar') }}</textarea>
            </div>

            <div class="form-group">
                <label>الوصف الكامل (AR)</label>
                <textarea name="description[ar]" rows="4">{{ old('description.ar') }}</textarea>
            </div>

            <div class="form-row" style="margin-top:20px">
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                    <label for="is_featured" style="margin:0">منتج مميز (Featured)</label>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="is_bestseller" value="1" id="is_bestseller" {{ old('is_bestseller') ? 'checked' : '' }}>
                    <label for="is_bestseller" style="margin:0">الأكثر مبيعاً (Bestseller)</label>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="status" value="1" id="status" {{ old('status', 1) ? 'checked' : '' }}>
                    <label for="status" style="margin:0">نشط (نشره فوراً)</label>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> حفظ المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
