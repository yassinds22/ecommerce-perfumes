@extends('clints.account.layout')

@section('title', 'حسابي — الملف الشخصي')

@section('account_content')
<div class="profile-section">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2 style="color: #fff; font-size: 1.8rem;">تعديل الملف الشخصي</h2>
        <p style="color: var(--color-text-dim);">تحديث بياناتك الشخصية المسجلة لدينا.</p>
    </div>

    <form action="{{ route('account.profile.update') }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">الاسم بالكامل</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="phone">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 40px; border-radius: 30px; background: var(--color-gold); color: #000; font-weight: 700; border: none; cursor: pointer; transition: 0.3s;">حفظ التغييرات</button>
    </form>
</div>

<style>
    input:focus {
        border-color: var(--color-gold) !important;
        box-shadow: 0 0 10px rgba(201, 168, 76, 0.2);
    }
</style>
@endsection
