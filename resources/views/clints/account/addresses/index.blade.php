@extends('clints.account.layout')

@section('title', 'حسابي — العناوين')

@section('account_content')
<div class="addresses-section">
    <div class="section-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: start;">
        <div>
            <h2 style="color: #fff; font-size: 1.8rem;">دفتر العناوين</h2>
            <p style="color: var(--color-text-dim);">إدارة عناوين الشحن الخاصة بك لعملية شراء أسرع.</p>
        </div>
        <a href="{{ route('account.addresses.create') }}" class="btn btn-primary" style="padding: 10px 25px; border-radius: 25px; background: var(--color-gold); color: #000; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> إضافة عنوان جديد
        </a>
    </div>

    <div class="addresses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
        @forelse($addresses as $address)
            <div class="address-card {{ $address->is_default ? 'default' : '' }}" style="background: rgba(255, 255, 255, 0.03); border: 1px solid {{ $address->is_default ? 'var(--color-gold)' : 'rgba(255, 255, 255, 0.05)' }}; border-radius: 15px; padding: 25px; position: relative;">
                @if($address->is_default)
                    <span style="position: absolute; top: 15px; left: 15px; background: var(--color-gold); color: #000; font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 10px;">افتراضي</span>
                @endif

                <div class="address-type" style="color: var(--color-gold); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; font-weight: 700;">
                    <i class="fas {{ $address->type == 'home' ? 'fa-home' : ($address->type == 'work' ? 'fa-briefcase' : 'fa-map-marker-alt') }}"></i>
                    {{ $address->type == 'home' ? 'منزل' : ($address->type == 'work' ? 'عمل' : 'آخر') }}
                </div>

                <div class="address-details" style="color: #fff; margin-bottom: 20px; line-height: 1.6;">
                    <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 5px;">{{ $address->first_name }} {{ $address->last_name }}</div>
                    <div style="color: var(--color-text-dim); font-size: 0.9rem;">{{ $address->phone }}</div>
                    <div style="margin-top: 10px; font-size: 0.95rem;">
                        {{ $address->address_line1 }}<br>
                        @if($address->address_line2) {{ $address->address_line2 }}<br> @endif
                        {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}
                    </div>
                </div>

                <div class="address-actions" style="display: flex; gap: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                    <a href="{{ route('account.addresses.edit', $address->id) }}" style="color: var(--color-text-dim); text-decoration: none; font-size: 0.9rem; transition: 0.3s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='var(--color-text-dim)'">تعديل</a>
                    
                    @if(!$address->is_default)
                        <form action="{{ route('account.addresses.set-default', $address->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: var(--color-text-dim); cursor: pointer; font-size: 0.9rem; transition: 0.3s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='var(--color-text-dim)'">تعيين كافتراضي</button>
                        </form>
                    @endif

                    <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنوان؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 0.9rem; opacity: 0.7; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">حذف</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 60px 0;">
                <i class="fas fa-map-marked-alt" style="font-size: 4rem; color: rgba(255,255,255,0.05); margin-bottom: 20px;"></i>
                <h3 style="color: #fff;">لا توجد عناوين مسجلة</h3>
                <p style="color: var(--color-text-dim);">أضف عنوانك لتسهيل عملية الشحن والتوصيل.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
