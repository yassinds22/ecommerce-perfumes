<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>قائمة الأمنيات — لوكس بارفيوم</title>
    <link rel="stylesheet" href="{{ asset('assets/clints/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/shop.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .wishlist-page { padding: 60px 0; min-height: 60vh; }
        .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; }
        .wishlist-empty { text-align: center; padding: 100px 20px; }
        .wishlist-empty i { font-size: 4rem; color: var(--color-gold); margin-bottom: 20px; display: block; }
        .wishlist-empty h2 { margin-bottom: 20px; }
        .wishlist-remove-btn { 
            position: absolute; top: 10px; right: 10px; 
            background: var(--bg-card); border: none; border-radius: 50%; 
            width: 35px; height: 35px; cursor: pointer; color: var(--color-danger);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: 0.3s;
        }
        .wishlist-remove-btn:hover { background: var(--color-danger); color: white; transform: rotate(90deg); }
    </style>
</head>

<body>
    @include('clints.layout.nav')

    <div class="page-spacer"></div>

    <section class="wishlist-page">
        <div class="container">
            <div class="section-header">
                <span class="section-label">مفضلاتك</span>
                <h2>قائمة الأمنيات</h2>
                <div class="divider"></div>
            </div>

            @if($wishlistItems->count() > 0)
                <div class="wishlist-grid">
                    @foreach($wishlistItems as $item)
                        @php $product = $item->product; @endphp
                        <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->getTranslation('name', 'ar') }}" data-price="{{ $product->price }}"
                            data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-remove-btn" title="إزالة من القائمة">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            <div class="product-card__image">
                                <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}"
                                    alt="{{ $product->getTranslation('name', 'ar') }}">
                                <div class="product-card__actions">
                                    <button onclick="window.location.href='{{ route('product', $product->id) }}'"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="product-card__info">
                                <p class="product-card__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
                                <h4 class="product-card__name">{{ $product->getTranslation('name', 'ar') }}</h4>
                                <p class="product-card__price">${{ $product->sale_price ?: $product->price }}</p>
                                <div style="display: flex; gap: 10px; margin-top: 15px;">
                                    <button class="product-card__btn add-to-cart-btn" style="flex: 1;">أضف للسلة</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="wishlist-empty">
                    <i class="far fa-heart"></i>
                    <h2>قائمة أمنياتك فارغة حالياً</h2>
                    <p>أضف بعض المنتجات التي تعجبك إلى قائمتك لتجدها لاحقاً!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top: 20px;">ابدأ التسوق</a>
                </div>
            @endif
        </div>
    </section>

    @include('clints.layout.footer')

    <script src="{{ asset('assets/clints/js/app.js') }}"></script>
</body>

</html>
