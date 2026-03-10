<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->getTranslation('name', 'ar') }} — لوكس بارفيوم</title>
    <meta name="description"
        content="عنبر عود ملكي — مزيج فخم من العود الكمبودي النادر والعنبر الدافئ. عطر فاخر من لوكس بارفيوم.">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/product.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <!-- ========== شريط التنقل ========== -->
  @include('clints.layout.nav')

    <div class="page-spacer"></div>

    <!-- مسار التنقل -->
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">الرئيسية</a><span class="separator">/</span>
            <a href="{{ route('shop') }}">المتجر</a><span class="separator">/</span>
            <span class="current">{{ $product->getTranslation('name', 'ar') }}</span>
        </div>
    </div>

    <!-- تفاصيل المنتج -->
    <section class="section product-detail">
        <div class="container">
            <div class="product-detail__layout">
                <!-- المعرض -->
                <div class="product-gallery">
                    <div class="product-gallery__main" id="mainImage">
                        <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/arabic-perfume.png') }}" alt="{{ $product->getTranslation('name', 'ar') }}" id="galleryMain">
                        <div class="product-gallery__zoom-hint"><i class="fas fa-search-plus"></i> مرر للتكبير</div>
                    </div>
                    <div class="product-gallery__thumbs">
                        @foreach($product->getMedia('gallery') as $media)
                        <button class="thumb {{ $loop->first ? 'active' : '' }}" onclick="changeImage(this, '{{ $media->getUrl() }}')">
                            <img src="{{ $media->getUrl() }}" alt="عرض {{ $loop->iteration }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- المعلومات -->
                <div class="product-info">
                    @if($product->is_bestseller)
                    <span class="product-info__badge">الأكثر مبيعاً</span>
                    @endif
                    <p class="product-info__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
                    <h1 class="product-info__name">{{ $product->getTranslation('name', 'ar') }}</h1>

                    <div class="product-info__rating">
                        <div class="stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <span>{{ number_format($product->average_rating ?? 5.0, 1) }} ({{ $product->reviews_count }} تقييم)</span>
                    </div>

                    <div class="product-info__price">
                        <span class="price-current">${{ $product->sale_price ?: $product->price }}</span>
                        @if($product->sale_price)
                        <span class="price-original">${{ $product->price }}</span>
                        <span class="price-tag">خصم {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                        @endif
                    </div>

                    <p class="product-info__desc">
                        {{ $product->description }}
                    </p>

                    <!-- طبقات العطر -->
                    @if($product->fragranceNotes->count() > 0)
                    <div class="fragrance-notes">
                        <h4>طبقات العطر</h4>
                        <div class="fragrance-notes__pyramid">
                            @if($product->topNotes->count() > 0)
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">المقدمة</span>
                                    <div class="note-bar top"></div>
                                </div>
                                <div class="note-items">
                                    @foreach($product->topNotes as $note)
                                    <span>{{ $note->getTranslation('name', 'ar') }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($product->heartNotes->count() > 0)
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">القلب</span>
                                    <div class="note-bar heart"></div>
                                </div>
                                <div class="note-items">
                                    @foreach($product->heartNotes as $note)
                                    <span>{{ $note->getTranslation('name', 'ar') }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($product->baseNotes->count() > 0)
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">القاعدة</span>
                                    <div class="note-bar base"></div>
                                </div>
                                <div class="note-items">
                                    @foreach($product->baseNotes as $note)
                                    <span>{{ $note->getTranslation('name', 'ar') }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif


                    <!-- الحجم -->
                    <div class="product-info__sizes">
                        <h4>الحجم</h4>
                        <div class="size-options">
                            <button class="size-btn">30 مل <span>$180</span></button>
                            <button class="size-btn active">100 مل <span>$340</span></button>
                            <button class="size-btn">200 مل <span>$520</span></button>
                        </div>
                    </div>

                    <!-- إضافة للسلة -->
                    <div class="product-info__actions">
                        <div class="qty-selector">
                            <button id="qtyMinus">−</button>
                            <input type="number" value="1" min="1" max="10" id="qtyInput" readonly>
                            <button id="qtyPlus">+</button>
                        </div>
                        <button class="btn btn-primary product-add-btn" id="addToCartBtn">
                            <i class="fas fa-shopping-bag"></i> أضف للسلة
                        </button>
                        <button class="btn-icon wishlist-btn {{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'active' : '' }}" id="wishlistBtn" data-product-id="{{ $product->id }}">
                            <i class="{{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="product-info__meta">
                        <div><i class="fas fa-truck"></i> شحن مجاني للطلبات فوق $200</div>
                        <div><i class="fas fa-undo"></i> سياسة إرجاع لمدة 30 يوم</div>
                        <div><i class="fas fa-shield-alt"></i> ضمان أصالة المنتج 100%</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- التقييمات -->
    <section class="section section--alt reviews-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">تقييمات العملاء</span>
                <h2>ماذا يقول عملاؤنا</h2>
                <div class="divider"></div>
            </div>

            <div class="reviews-summary">
                <div class="reviews-summary__score">
                    <span class="big-score">5.0</span>
                    <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <span>بناءً على 312 تقييم</span>
                </div>
                <div class="reviews-summary__bars">
                    <div class="bar-row"><span>5 ★</span>
                        <div class="bar">
                            <div class="bar-fill" style="width:85%"></div>
                        </div><span>265</span>
                    </div>
                    <div class="bar-row"><span>4 ★</span>
                        <div class="bar">
                            <div class="bar-fill" style="width:10%"></div>
                        </div><span>31</span>
                    </div>
                    <div class="bar-row"><span>3 ★</span>
                        <div class="bar">
                            <div class="bar-fill" style="width:3%"></div>
                        </div><span>10</span>
                    </div>
                    <div class="bar-row"><span>2 ★</span>
                        <div class="bar">
                            <div class="bar-fill" style="width:1%"></div>
                        </div><span>4</span>
                    </div>
                    <div class="bar-row"><span>1 ★</span>
                        <div class="bar">
                            <div class="bar-fill" style="width:0.5%"></div>
                        </div><span>2</span>
                    </div>
                </div>
            <!-- Add Review Form -->
            <div class="add-review-form" style="margin-bottom: 40px; background: var(--bg-card); padding: 30px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                @auth
                <h3 style="margin-bottom: 20px;">أضف تقييمك</h3>
                <form id="reviewForm" data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="rating-input" style="margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
                        <span>تقييمك:</span>
                        <div class="star-rating" style="display: flex; flex-direction: row-reverse; gap: 5px; font-size: 1.5rem;">
                            <input type="radio" name="rating" value="5" id="r5" style="display:none;"><label for="r5" style="cursor:pointer; color: var(--color-text-dim);">★</label>
                            <input type="radio" name="rating" value="4" id="r4" style="display:none;"><label for="r4" style="cursor:pointer; color: var(--color-text-dim);">★</label>
                            <input type="radio" name="rating" value="3" id="r3" style="display:none;"><label for="r3" style="cursor:pointer; color: var(--color-text-dim);">★</label>
                            <input type="radio" name="rating" value="2" id="r2" style="display:none;"><label for="r2" style="cursor:pointer; color: var(--color-text-dim);">★</label>
                            <input type="radio" name="rating" value="1" id="r1" style="display:none;"><label for="r1" style="cursor:pointer; color: var(--color-text-dim);">★</label>
                        </div>
                    </div>
                    <style>
                        .star-rating label:hover,
                        .star-rating label:hover ~ label,
                        .star-rating input:checked ~ label {
                            color: var(--color-gold) !important;
                        }
                    </style>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <textarea id="reviewComment" name="comment" placeholder="أدخل تعليقك هنا..." required style="width: 100%; padding: 15px; background: var(--bg-input); border: 1px solid var(--border-color); border-radius: var(--radius-sm); color: var(--color-text); min-height: 120px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitReviewBtn">إرسال التقييم</button>
                </form>
                @else
                <div style="text-align: center; padding: 20px;">
                    <i class="fas fa-lock" style="font-size: 2rem; color: var(--color-gold); margin-bottom: 15px; display: block;"></i>
                    <p style="margin-bottom: 20px; color: var(--color-text-dim);">يرجى تسجيل الدخول لتتمكن من إضافة تقييم لهذا المنتج.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">تسجيل الدخول</a>
                </div>
                @endauth
            </div>

            <div class="reviews-list">
                @forelse($product->reviews as $review)
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="review-card__author">
                            <div class="review-avatar">{{ mb_substr($review->user->name, 0, 2) }}</div>
                            <div>
                                <h4>{{ $review->user->name }}</h4><span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star" style="color: {{ $i <= $review->rating ? 'var(--color-gold)' : 'var(--color-text-dim)' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p>{{ $review->comment }}</p>
                    @if($review->is_verified_purchase)
                        <span class="review-verified"><i class="fas fa-check-circle"></i> شراء موثق</span>
                    @endif
                </div>
                @empty
                <div class="mini-cart__empty">
                    <i class="fas fa-comment-slash"></i>
                    <p>لا توجد تقييمات لهذا المنتج بعد. كن أول من يقيمه!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- منتجات مشابهة -->
    <section class="section related-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">قد يعجبك أيضاً</span>
                <h2>عطور مشابهة</h2>
                <div class="divider"></div>
            </div>
            <div class="related-grid">
                @foreach($relatedProducts as $item)
                <div class="product-card" data-id="{{ $item->id }}" data-name="{{ $item->getTranslation('name', 'ar') }}" data-price="{{ $item->price }}"
                    data-img="{{ $item->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
                    <div class="product-card__image">
                        <img src="{{ $item->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}"
                            alt="{{ $item->getTranslation('name', 'ar') }}">
                        <div class="product-card__actions">
                            <button class="wishlist-btn {{ auth()->check() && auth()->user()->wishlist->contains('product_id', $item->id) ? 'active' : '' }}">
                                <i class="{{ auth()->check() && auth()->user()->wishlist->contains('product_id', $item->id) ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                            <button onclick="window.location.href='{{ route('product', $item->id) }}'"><i class="far fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__brand">{{ $item->brand->name ?? 'لوكس بارفيوم' }}</p>
                        <h4 class="product-card__name">{{ $item->getTranslation('name', 'ar') }}</h4>
                        <div class="product-card__rating">
                            <i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star-half-alt star"></i><span>(128)</span>
                        </div>
                        <p class="product-card__price">${{ $item->sale_price ?: $item->price }}</p>
                        <button class="product-card__btn add-to-cart-btn">أضف للسلة</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

  <!-- ========== التذييل ========== -->
  @include('clints.layout.footer')

  <script src="{{ asset('assets/clints/js/product.js') }}"></script>
</body>

</html>