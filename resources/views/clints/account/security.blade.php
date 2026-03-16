@extends('clints.account.layout')

@section('title', 'حسابي — الأمان')

@section('account_content')
<div class="security-section">
    <div class="section-header" style="margin-bottom: 40px;">
        <h2 style="color: #fff; font-size: 1.8rem;">الأمان وإعدادات الحساب</h2>
        <p style="color: var(--color-text-dim);">إدارة كلمة المرور والوصول إلى حسابك.</p>
    </div>

    <!-- Password Management -->
    <div class="security-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 30px; margin-bottom: 30px;">
        <h3 style="color: #fff; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-key" style="color: var(--color-gold);"></i> تغيير كلمة المرور
        </h3>
        <form action="{{ route('account.security.password') }}" method="POST" style="max-width: 500px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">كلمة المرور الحالية</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور الجديدة</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 30px; border-radius: 25px; background: var(--color-gold); color: #000; font-weight: 700; border: none; cursor: pointer; transition: 0.3s;">تحديث كلمة المرور</button>
        </form>
    </div>

    <!-- Email Update (Optional) -->
    <div class="security-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 30px;">
        <h3 style="color: #fff; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-envelope" style="color: var(--color-gold);"></i> تحديث البريد الإلكتروني
        </h3>
        <form action="{{ route('account.security.email') }}" method="POST" style="max-width: 500px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="email_security">البريد الإلكتروني الحالي</label>
                <input type="email" name="email" id="email_security" value="{{ $user->email }}" required>
            </div>

            <button type="submit" class="btn btn-outline" style="padding: 10px 30px; border-radius: 25px; border: 1px solid var(--color-gold); color: var(--color-gold); font-weight: 700; background: transparent; cursor: pointer; transition: 0.3s;">تحديث البريد</button>
        </form>
    </div>
</div>

<style>
    input:focus {
        border-color: var(--color-gold) !important;
    }
</style>
@endsection
