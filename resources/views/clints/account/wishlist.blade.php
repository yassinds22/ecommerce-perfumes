@extends('clints.account.layout')

@section('title', 'حسابي — قائمة الأمنيات')

@section('account_content')
<div class="wishlist-section">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2 style="color: #fff; font-size: 1.8rem;">قائمة الأمنيات</h2>
        <p style="color: var(--color-text-dim);">المنتجات التي حفظتها للعودة إليها لاحقاً.</p>
    </div>

    <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
        @forelse($wishlistItems as $item)
            @if($item->product)
                <div class="wishlist-item-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; overflow: hidden; transition: 0.3s; position: relative;">
                    <form action="{{ route('account.wishlist.destroy', $item->id) }}" method="POST" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 35px; height: 35px; border-radius: 50%; background: rgba(0,0,0,0.5); border: none; color: #ff4d4d; cursor: pointer; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                    <div class="item-img" style="height: 250px; background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <img src="{{ $item->product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}" alt="{{ $item->product->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    
                    <div class="item-info" style="padding: 20px;">
                        <h4 style="color: #fff; margin-bottom: 8px; font-size: 1.1rem; min-height: 2.2rem;">{{ $item->product->name }}</h4>
                        <div class="price" style="color: var(--color-gold); font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">{{ number_format($item->product->price) }} ر.س</div>
                        
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('product', $item->product->id) }}" class="btn btn-primary" style="flex: 1; padding: 10px; border-radius: 10px; background: var(--color-gold); color: #000; text-align: center; text-decoration: none; font-weight: 700; font-size: 0.9rem;">عرض المنتج</a>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 80px 0;">
                <i class="fas fa-heart-broken" style="font-size: 4rem; color: rgba(255,255,255,0.05); margin-bottom: 20px;"></i>
                <h3 style="color: #fff;">قائمتك المفضلة فارغة حالياً</h3>
                <p style="color: var(--color-text-dim); margin-bottom: 25px;">لم تقم بإضافة أي منتجات بعد.</p>
                <a href="{{ url('/') }}" class="btn btn-primary" style="padding: 10px 30px; border-radius: 30px; background: var(--color-gold); color: #000; text-decoration: none; font-weight: 700;">اكتشف العطور</a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .wishlist-item-card:hover {
        border-color: var(--color-gold);
        transform: translateY(-5px);
    }
</style>
@endsection
