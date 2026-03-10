@extends('admin.layout.master')

@section('title', 'تعديل منتج — لوكس بارفيوم')

@section('page_title', 'تعديل منتج')
@section('page_subtitle', 'تعديل بيانات المنتج: ' . $product->name)

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 1000px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>بيانات المنتج</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="modal__body" style="padding: 24px">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label>الاسم بالعربية</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar', $product->getTranslation('name', 'ar')) }}" required>
                    @error('name.ar') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>الاسم بالإنجليزية</label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $product->getTranslation('name', 'en')) }}" required>
                    @error('name.en') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>التصنيف</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الماركة</label>
                    <select name="brand_id" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>السعر الأساسي ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="form-group">
                    <label>سعر العرض ($)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>رمز المنتج (SKU)</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                    @error('sku') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>المخزون المتوفر</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                    @error('stock_quantity') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>حد التنبيه</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0" required>
                    @error('low_stock_threshold') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>الفئة المستهدفة</label>
                    <select name="gender" required>
                        <option value="Men" {{ old('gender', $product->gender) == 'Men' ? 'selected' : '' }}>رجالي</option>
                        <option value="Women" {{ old('gender', $product->gender) == 'Women' ? 'selected' : '' }}>نسائي</option>
                        <option value="Unisex" {{ old('gender', $product->gender) == 'Unisex' ? 'selected' : '' }}>للجنسين</option>
                        <option value="Kids" {{ old('gender', $product->gender) == 'Kids' ? 'selected' : '' }}>أطفال</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>تحديث الصورة الأساسية</label>
                    @if($product->getFirstMediaUrl('images'))
                        <div style="margin-bottom: 10px">
                            <img src="{{ $product->getFirstMediaUrl('images') }}" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <label>معرض الصور الحالي (Gallery)</label>
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px">
                    @foreach($product->getMedia('gallery') as $media)
                        <div style="position: relative; width: 80px; height: 80px;">
                            <img src="{{ $media->getUrl() }}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 1px solid var(--color-border)">
                        </div>
                    @endforeach
                </div>
                <label>إضافة صور للمعرض</label>
                <input type="file" name="gallery[]" accept="image/*" multiple>
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
                                <option value="{{ $note->id }}" {{ in_array($note->id, $selectedTopNotes) ? 'selected' : '' }}>
                                    {{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})
                                </option>
                            @endforeach
                        </select>
                        <small style="color:var(--color-text-muted)">يمكنك اختيار أكثر من مكون (Control + Click)</small>
                    </div>
                    <div class="form-group">
                        <label>مكونات القلب (Middle Notes)</label>
                        <select name="middle_notes[]" multiple class="form-input" style="height: 120px">
                            @foreach($notes as $note)
                                <option value="{{ $note->id }}" {{ in_array($note->id, $selectedMiddleNotes) ? 'selected' : '' }}>
                                    {{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row" style="margin-top: 16px">
                    <div class="form-group">
                        <label>مكونات القاعدة (Base Notes)</label>
                        <select name="base_notes[]" multiple class="form-input" style="height: 120px">
                            @foreach($notes as $note)
                                <option value="{{ $note->id }}" {{ in_array($note->id, $selectedBaseNotes) ? 'selected' : '' }}>
                                    {{ $note->getTranslation('name', 'ar') }} ({{ $note->getTranslation('name', 'en') }})
                                </option>
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
                <textarea name="short_description[ar]" rows="2">{{ old('short_description.ar', $product->getTranslation('short_description', 'ar')) }}</textarea>
            </div>

            <div class="form-group">
                <label>الوصف الكامل (AR)</label>
                <textarea name="description[ar]" rows="4">{{ old('description.ar', $product->getTranslation('description', 'ar')) }}</textarea>
            </div>

            <div class="form-row" style="margin-top:20px">
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" style="margin:0">منتج مميز (Featured)</label>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="is_bestseller" value="1" id="is_bestseller" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
                    <label for="is_bestseller" style="margin:0">الأكثر مبيعاً (Bestseller)</label>
                </div>
                <div class="form-group" style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="checkbox" name="status" value="1" id="status" {{ old('status', $product->status) ? 'checked' : '' }}>
                    <label for="status" style="margin:0">نشط</label>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> تحديث المنتج
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
    <div class="table-card" style="max-width: 1000px; margin: 30px auto;">
        <div class="table-card__header">
            <h3>سجل حركة المخزون</h3>
            <div class="badge {{ $product->is_out_of_stock ? 'badge--danger' : ($product->isLowStock() ? 'badge--warning' : 'badge--success') }}">
                {{ $product->is_out_of_stock ? 'نفذ من المخزون' : ($product->isLowStock() ? 'مخزون منخفض' : 'متوفر') }}
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الكمية</th>
                        <th>البيان / المرجع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->stockMovements()->latest()->take(10)->get() as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge badge--{{ $movement->type === 'increase' ? 'success' : ($movement->type === 'decrease' ? 'danger' : 'info') }}">
                                    {{ $movement->type === 'increase' ? 'زيادة' : ($movement->type === 'decrease' ? 'نقص' : 'تعديل') }}
                                </span>
                            </td>
                            <td dir="ltr">{{ $movement->type === 'decrease' ? '-' : '+' }}{{ $movement->quantity }}</td>
                            <td>{{ $movement->reference ?: '---' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px; color: var(--color-text-muted)">لا يوجد حركات مخزون مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
