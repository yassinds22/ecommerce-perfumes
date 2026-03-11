@extends('clints.layout.master')

@section('title', 'تم إلغاء الدفع')

@section('content')
<div class="container" style="padding: 100px 20px; text-align: center;">
    <div style="background: rgba(255, 255, 255, 0.05); padding: 40px; border-radius: 20px; border: 1px solid var(--color-danger); max-width: 600px; margin: 0 auto;">
        <i class="fas fa-times-circle" style="font-size: 80px; color: var(--color-danger); margin-bottom: 20px;"></i>
        <h1 style="color: var(--color-danger); margin-bottom: 20px;">تم إلغاء عملية الدفع</h1>
        <p style="margin-bottom: 30px;">يبدو أنك قمت بإلغاء عملية الدفع، أو حدث خطأ ما. لم يتم خصم أي مبالغ من بطاقتك.</p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="{{ url()->previous() }}" class="btn btn-outline">العودة وإعادة المحاولة</a>
            <a href="{{ route('index') }}" class="btn btn-gold">العودة للمتجر</a>
        </div>
    </div>
</div>
@endsection
