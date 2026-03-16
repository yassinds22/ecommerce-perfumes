@extends('clints.account.layout')

@section('title', 'إضافة عنوان جديد')

@section('account_content')
<div class="address-form-section">
    <div class="section-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="color: #fff; font-size: 1.8rem;">إضافة عنوان جديد</h2>
            <p style="color: var(--color-text-dim);">أدخل تفاصيل العنوان الجديد بدقة لضمان سرعة التوصيل.</p>
        </div>
        <a href="{{ route('account.addresses.index') }}" style="color: var(--color-gold); text-decoration: none;">إلغاء</a>
    </div>

    <form action="{{ route('account.addresses.store') }}" method="POST" style="max-width: 800px;">
        @csrf
        
        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="display: block; color: var(--color-gold); margin-bottom: 8px;">نوع العنوان</label>
                <select name="type">
                    <option value="home">منزل</option>
                    <option value="work">عمل</option>
                    <option value="other">آخر</option>
                </select>
            </div>
            <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                <label style="color: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_default" value="1" style="width: 18px; height: 18px; accent-color: var(--color-gold);"> تعيين كعنوان افتراضي
                </label>
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="first_name">الاسم الأول</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">اسم العائلة</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني (اختياري)</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="city">المدينة</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" required>
            </div>
            <div class="form-group">
                <label for="state">المنطقة / الولاية</label>
                <input type="text" name="state" id="state" value="{{ old('state') }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="address_line1">العنوان (الشارع، الحي، المبنى)</label>
            <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1') }}" required>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group">
                <label for="address_line2">شقة، مكتب، إلخ (اختياري)</label>
                <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2') }}">
            </div>
            <div class="form-group">
                <label for="zip_code">الرمز البريدي (اختياري)</label>
                <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 50px; border-radius: 30px; background: var(--color-gold); color: #000; font-weight: 700; border: none; cursor: pointer; transition: 0.3s;">حفظ العنوان</button>
    </form>
</div>
@endsection
