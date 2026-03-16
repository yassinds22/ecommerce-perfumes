@extends('clints.layout.master')

@section('title', 'حسابي — لوكس بارفيوم')

@section('content')
<section class="account-guest-section" style="padding: 100px 0; text-align: center; background: var(--color-bg-dark)">
    <div class="container" style="max-width: 600px; margin: 0 auto; padding: 40px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; backdrop-filter: blur(10px);">
        <div class="guest-icon" style="font-size: 4rem; color: var(--color-gold); margin-bottom: 20px;">
            <i class="fas fa-user-circle"></i>
        </div>
        <h2 style="color: #fff; margin-bottom: 15px; font-size: 2rem;">مرحباً بك في عالم الفخامة</h2>
        <p style="color: var(--color-text-dim); margin-bottom: 30px; font-size: 1.1rem; line-height: 1.6;">سجل دخولك الآن لتتمكن من تتبع طلباتك، إدارة عناوينك، والوصول إلى قائمتك المفضلة.</p>
        
        <div class="guest-actions" style="display: flex; gap: 20px; justify-content: center;">
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 12px 35px; border-radius: 30px; background: var(--color-gold); color: #000; font-weight: 700; text-decoration: none; transition: 0.3s;">تسجيل الدخول</a>
            <a href="{{ route('register') }}" class="btn btn-outline" style="padding: 12px 35px; border-radius: 30px; border: 1px solid var(--color-gold); color: var(--color-gold); font-weight: 700; text-decoration: none; transition: 0.3s;">إنشاء حساب جديد</a>
        </div>
    </div>
</section>
@endsection
