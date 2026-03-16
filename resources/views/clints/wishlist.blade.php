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

    </style>
</head>

<body>
    @include('clints.layout.nav')

    <div class="page-spacer"></div>

    <section class="wishlist-page loading">
        <div class="container">
            <div class="section-header">
                <span class="section-label">{{ isset($isSharedView) ? "قائمة مشتركة من $ownerName" : "مفضلاتك" }}</span>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <h2 style="margin: 0;">{{ isset($isSharedView) ? "قائمة أمنيات $ownerName" : "قائمة الأمنيات" }}</h2>
                    @if(!isset($isSharedView) && auth()->check())
                        <button class="btn btn-outline" onclick="BtnUtils.copyToClipboard('{{ route('wishlist.shared', base64_encode(auth()->id())) }}')" style="border-radius: 50px; padding: 10px 25px;">
                            <i class="fas fa-share-alt" style="margin-left: 8px;"></i> مشاركة القائمة
                        </button>
                    @endif
                </div>
                <div class="divider"></div>
            </div>

            <!-- Skeleton Loading Screen -->
            <div class="skeleton-wrapper">
                @for($i=0; $i<4; $i++)
                    <div class="skeleton-item"><div class="skeleton-shimmer"></div></div>
                @endfor
            </div>

            @if($wishlistItems->count() > 0)
                <div class="wishlist-grid">
                    @foreach($wishlistItems as $item)
                        @php $product = $item->product; @endphp
                        <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->getTranslation('name', 'ar') }}" data-price="{{ $product->price }}"
                            data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
                            
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="price-drop-badge">
                                    <i class="fas fa-arrow-down"></i> هبط السعر!
                                </div>
                            @endif

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
                                <p class="product-card__price">
                                    @if($product->sale_price)
                                        <span style="text-decoration: line-through; color: var(--color-text-muted); font-size: 0.85rem; margin-left: 8px;">${{ $product->price }}</span>
                                        <span>${{ $product->sale_price }}</span>
                                    @else
                                        ${{ $product->price }}
                                    @endif
                                </p>
                                
                                @if(!isset($isSharedView))
                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                                        <div style="display: flex; gap: 10px;">
                                            <button class="product-card__btn add-to-cart-btn" style="flex: 1;">أضف للسلة</button>
                                            <button class="product-card__btn remove-wishlist-ajax" data-id="{{ $product->id }}" style="flex: 0 0 45px; background: #f8dede; color: #c62828; border: 1px solid #f2c7c7;"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                        <button class="product-card__btn move-to-cart-btn" data-id="{{ $product->id }}" style="background: transparent; border: 1px solid var(--color-gold); color: var(--color-gold);">نقل إلى السلة</button>
                                    </div>
                                @else
                                    <button class="product-card__btn add-to-cart-btn" style="margin-top: 15px; width: 100%;">أضف للسلة</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="wishlist-empty reveal">
                    <i class="far fa-heart"></i>
                    <h2>قائمة أمنياتك فارغة حالياً</h2>
                    <p>اكتشف مجموعتنا المختارة بعناية وأضف عطورك المفضلة!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top: 20px;">ابدأ التسوق</a>
                </div>

                @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
                    <div class="wishlist-recommendations" style="margin-top: 100px;">
                        <div class="section-header">
                            <span class="section-label">قد يعجبك أيضاً</span>
                            <h3>منتجات مقترحة لك</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="wishlist-grid">
                            @foreach($recommendedProducts as $product)
                                <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->getTranslation('name', 'ar') }}" data-price="{{ $product->price }}"
                                    data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
                                    <div class="product-card__image">
                                        <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}" alt="{{ $product->getTranslation('name', 'ar') }}">
                                        <div class="product-card__actions">
                                            <button class="wishlist-btn {{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'active' : '' }}">
                                                <i class="{{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'fas' : 'far' }} fa-heart"></i>
                                            </button>
                                            <button onclick="window.location.href='{{ route('product', $product->id) }}'"><i class="far fa-eye"></i></button>
                                        </div>
                                    </div>
                                    <div class="product-card__info">
                                        <p class="product-card__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
                                        <h4 class="product-card__name">{{ $product->getTranslation('name', 'ar') }}</h4>
                                        <p class="product-card__price">${{ $product->sale_price ?: $product->price }}</p>
                                        <button class="product-card__btn add-to-cart-btn">أضف للسلة</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>

    @include('clints.layout.footer')

    <script src="{{ asset('assets/clints/js/app.js') }}"></script>
    <script>
        // Simulate loading end for Skeletons
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.querySelector('.wishlist-page').classList.remove('loading');
            }, 800);
        });
    </script>
</body>

</html>
