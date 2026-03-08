@extends('admin.layout.master')

@section('title', 'تعديل الكوبون — لوكس بارفيوم')

@section('page_title', 'تعديل كوبون')
@section('page_subtitle', 'تحديث بيانات عرض الخصم')

@section('content')
<section class="dashboard-section active">
    <div class="table-card" style="max-width: 800px; margin: 0 auto;">
        <div class="table-card__header">
            <h3>بيانات الكوبون: {{ $coupon->code }}</h3>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>
        
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="modal__body" style="padding: 24px">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px">
                <div class="form-group">
                    <label>كود الخصم (Code)</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase">
                    @error('code') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>نوع الخصم</label>
                    <select name="type" required>
                        <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ر.س)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>قيمة الخصم</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required>
                    @error('value') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>الحد الأقصى للاستخدام (Usage Limit)</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>

                <div class="form-group">
                    <label>تاريخ البدء</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>تاريخ الانتهاء</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                    @error('expires_at') <span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px">
                <label>نطاق الكوبون</label>
                <select name="is_global" id="is_global" required onchange="toggleProductSelection()">
                    <option value="1" {{ old('is_global', $coupon->is_global) == 1 ? 'selected' : '' }}>عام (كل المنتجات)</option>
                    <option value="0" {{ old('is_global', $coupon->is_global) == 0 ? 'selected' : '' }}>منتجات محددة</option>
                </select>
            </div>

            <div id="product_selection" class="form-group" style="margin-top: 20px; display: {{ old('is_global', $coupon->is_global) == 0 ? 'block' : 'none' }}">
                <label>اختر المنتجات</label>
                <select name="products[]" multiple style="height: 150px">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ (is_array(old('products', $selectedProducts)) && in_array($product->id, old('products', $selectedProducts))) ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                    @endforeach
                </select>
                <small style="color:var(--color-text-dim)">اضغط Ctrl (أو Command) لاختيار أكثر من منتج</small>
                @error('products') <br><span style="color:var(--color-danger); font-size: 0.8rem">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-top: 20px">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} style="width: auto">
                    تفعيل الكوبون
                </label>
            </div>

            <script>
                function toggleProductSelection() {
                    const isGlobal = document.getElementById('is_global').value;
                    const productDiv = document.getElementById('product_selection');
                    productDiv.style.display = (isGlobal == '0') ? 'block' : 'none';
                }
            </script>


            <div style="margin-top: 32px; display: flex; gap: 12px">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> تحديث الكوبون
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</section>
@endsection
