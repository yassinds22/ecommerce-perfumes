@extends('clints.layout.master')

@section('title', 'تم الدفع بنجاح')

@section('content')
<div class="container" style="padding: 100px 20px; text-align: center;">
    <div style="background: rgba(255, 255, 255, 0.05); padding: 40px; border-radius: 20px; border: 1px solid var(--color-gold); max-width: 600px; margin: 0 auto;">
        <i class="fas fa-check-circle" style="font-size: 80px; color: var(--color-gold); margin-bottom: 20px;"></i>
        <h1 style="color: var(--color-gold); margin-bottom: 20px;">شكراً لك! تم الدفع بنجاح</h1>
        <p style="margin-bottom: 30px;">لقد تلقينا طلبك وسنباشر العمل عليه فوراً. يمكنك تتبع حالة طلبك من خلال حسابك.</p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="{{ route('index') }}" class="btn btn-gold">العودة للمتجر</a>
        </div>
    </div>
</div>
@endsection
