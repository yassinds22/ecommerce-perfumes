@extends('clints.account.layout')

@section('title', 'تعديل العنوان')

@section('account_content')
<div class="address-form-section">
    <div class="section-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="color: #fff; font-size: 1.8rem;">تعديل العنوان</h2>
            <p style="color: var(--color-text-dim);">تحديث تفاصيل العنوان الخاص بك.</p>
        </div>
        <a href="{{ route('account.addresses.index') }}" style="color: var(--color-gold); text-decoration: none;">إلغاء</a>
    </div>

    <form action="{{ route('account.addresses.update', $address->id) }}" method="POST" style="max-width: 800px;">
        @csrf
        @method('PUT')
        
        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label style="display: block; color: var(--color-gold); margin-bottom: 8px;">نوع العنوان</label>
                <select name="type">
                    <option value="home" {{ $address->type == 'home' ? 'selected' : '' }}>منزل</option>
                    <option value="work" {{ $address->type == 'work' ? 'selected' : '' }}>عمل</option>
                    <option value="other" {{ $address->type == 'other' ? 'selected' : '' }}>آخر</option>
                </select>
            </div>
            <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                <label style="color: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--color-gold);"> تعيين كعنوان افتراضي
                </label>
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="first_name">الاسم الأول</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $address->first_name) }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">اسم العائلة</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $address->last_name) }}" required>
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" required>
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني (اختياري)</label>
                <input type="email" name="email" id="email" value="{{ old('email', $address->email) }}">
            </div>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="city">المدينة</label>
                <input type="text" name="city" id="city" value="{{ old('city', $address->city) }}" required>
            </div>
            <div class="form-group">
                <label for="state">المنطقة / الولاية</label>
                <input type="text" name="state" id="state" value="{{ old('state', $address->state) }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="address_line1">العنوان (الشارع، الحي، المبنى)</label>
            <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1', $address->address_line1) }}" required>
        </div>

        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="form-group">
                <label for="address_line2">شقة، مكتب، إلخ (اختياري)</label>
                <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2', $address->address_line2) }}">
            </div>
            <div class="form-group">
                <label for="zip_code">الرمز البريدي (اختياري)</label>
                <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $address->zip_code) }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 50px; border-radius: 30px; background: var(--color-gold); color: #000; font-weight: 700; border: none; cursor: pointer; transition: 0.3s;">تحديث العنوان</button>
    </form>
</div>
@endsection
