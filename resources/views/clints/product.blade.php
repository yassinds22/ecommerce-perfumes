<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عنبر عود ملكي — لوكس بارفيوم</title>
    <meta name="description"
        content="عنبر عود ملكي — مزيج فخم من العود الكمبودي النادر والعنبر الدافئ. عطر فاخر من لوكس بارفيوم.">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/product.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <!-- ========== شريط التنقل ========== -->
  @include('clints.layout.nav')

  <!-- القائمة المتنقلة -->
  <div class="mobile-nav" id="mobileNav">
    <a href="{{ route('home') }}">الرئيسية</a>
    <a href="{{ route('shop') }}">المتجر</a>
    <a href="{{ route('shop', ['cat' => 'men']) }}">للرجال</a>
    <a href="{{ route('shop', ['cat' => 'women']) }}">للنساء</a>
    <a href="{{ route('product') }}">المجموعات</a>
    <a href="#footer">من نحن</a>
  </div>
  <div class="nav-overlay" id="navOverlay"></div>

  <!-- سلة التسوق المصغرة -->
  <div class="cart-overlay" id="cartOverlay"></div>
  <aside class="mini-cart" id="miniCart">
    <div class="mini-cart__header">
      <h3>حقيبة التسوق</h3><button class="mini-cart__close" id="miniCartClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="mini-cart__items" id="miniCartItems">
      <div class="mini-cart__empty" id="miniCartEmpty"><i class="fas fa-shopping-bag"></i>
        <p>حقيبتك فارغة</p><a href="{{ route('shop') }}" class="btn btn-primary">تسوق الآن</a>
      </div>
    </div>
    <div class="mini-cart__footer" id="miniCartFooter" style="display:none;">
      <div class="mini-cart__total"><span>المجموع</span><span id="miniCartTotal">$0.00</span></div><a
        href="{{ route('cart') }}" class="btn btn-primary">عرض السلة</a>
    </div>
  </aside>

    <div class="page-spacer"></div>

    <!-- مسار التنقل -->
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">الرئيسية</a><span class="separator">/</span>
            <a href="{{ route('shop') }}">المتجر</a><span class="separator">/</span>
            <span class="current">عنبر عود ملكي</span>
        </div>
    </div>

    <!-- تفاصيل المنتج -->
    <section class="section product-detail">
        <div class="container">
            <div class="product-detail__layout">
                <!-- المعرض -->
                <div class="product-gallery">
                    <div class="product-gallery__main" id="mainImage">
                        <img src="{{ asset('assets/clints/images/arabic-perfume.png') }}" alt="عنبر عود ملكي" id="galleryMain">
                        <div class="product-gallery__zoom-hint"><i class="fas fa-search-plus"></i> مرر للتكبير</div>
                    </div>
                    <div class="product-gallery__thumbs">
                        <button class="thumb active" onclick="changeImage(this, '{{ asset('assets/clints/images/arabic-perfume.png') }}')">
                            <img src="{{ asset('assets/clints/images/arabic-perfume.png') }}" alt="عرض 1">
                        </button>
                        <button class="thumb" onclick="changeImage(this, '{{ asset('assets/clints/images/mens-perfume.png') }}')">
                            <img src="{{ asset('assets/clints/images/mens-perfume.png') }}" alt="عرض 2">
                        </button>
                        <button class="thumb" onclick="changeImage(this, '{{ asset('assets/clints/images/unisex-perfume.png') }}')">
                            <img src="{{ asset('assets/clints/images/unisex-perfume.png') }}" alt="عرض 3">
                        </button>
                        <button class="thumb" onclick="changeImage(this, '{{ asset('assets/clints/images/gift-set.png') }}')">
                            <img src="{{ asset('assets/clints/images/gift-set.png') }}" alt="عرض 4">
                        </button>
                    </div>
                </div>

                <!-- المعلومات -->
                <div class="product-info">
                    <span class="product-info__badge">الأكثر مبيعاً</span>
                    <p class="product-info__brand">لوكس بارفيوم — المجموعة المميزة</p>
                    <h1 class="product-info__name">عنبر عود ملكي</h1>

                    <div class="product-info__rating">
                        <div class="stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <span>5.0 (312 تقييم)</span>
                    </div>

                    <div class="product-info__price">
                        <span class="price-current">$340.00</span>
                        <span class="price-original">$400.00</span>
                        <span class="price-tag">خصم 15%</span>
                    </div>

                    <p class="product-info__desc">
                        مزيج فخم من العود الكمبودي النادر والعنبر الدافئ، معزز بخيوط الزعفران وورد الطائف المطلق. هذا
                        العطر الفاخر يجسد جوهر الترف الشرقي، ويترك أثراً من الغموض والرقي يدوم طوال اليوم.
                    </p>

                    <!-- طبقات العطر -->
                    <div class="fragrance-notes">
                        <h4>طبقات العطر</h4>
                        <div class="fragrance-notes__pyramid">
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">المقدمة</span>
                                    <div class="note-bar top"></div>
                                </div>
                                <div class="note-items">
                                    <span>زعفران</span>
                                    <span>برغموت</span>
                                    <span>فلفل وردي</span>
                                </div>
                            </div>
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">القلب</span>
                                    <div class="note-bar heart"></div>
                                </div>
                                <div class="note-items">
                                    <span>ورد مطلق</span>
                                    <span>ياسمين</span>
                                    <span>عود</span>
                                </div>
                            </div>
                            <div class="note-row">
                                <div class="note-level">
                                    <span class="note-label">القاعدة</span>
                                    <div class="note-bar base"></div>
                                </div>
                                <div class="note-items">
                                    <span>عنبر</span>
                                    <span>خشب صندل</span>
                                    <span>مسك</span>
                                </div>
                            </div>
                        </div>
                    </div>

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
                        <button class="btn-icon wishlist-btn" id="wishlistBtn">
                            <i class="far fa-heart"></i>
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
            </div>

            <div class="reviews-list">
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="review-card__author">
                            <div class="review-avatar">أخ</div>
                            <div>
                                <h4>أحمد خالد</h4><span class="review-date">28 فبراير، 2026</span>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <h4 class="review-title">عطر رائع بكل المقاييس</h4>
                    <p>هذا أفضل عطر عود ارتديته على الإطلاق. مزيج الزعفران والورد مع العود مذهل. الثبات مدهش — أحصل على
                        12+ ساعة من الرائحة الجميلة. يستحق كل قرش.</p>
                    <span class="review-verified"><i class="fas fa-check-circle"></i> شراء موثق</span>
                </div>
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="review-card__author">
                            <div class="review-avatar">نع</div>
                            <div>
                                <h4>نورة العلي</h4><span class="review-date">15 فبراير، 2026</span>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <h4 class="review-title">هدية مثالية</h4>
                    <p>اشتريته كهدية لزوجي وهو يعشقه تماماً. التغليف أنيق جداً والرائحة راقية دون أن تكون طاغية. أنصح به
                        بشدة!</p>
                    <span class="review-verified"><i class="fas fa-check-circle"></i> شراء موثق</span>
                </div>
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="review-card__author">
                            <div class="review-avatar">يح</div>
                            <div>
                                <h4>ياسر حسن</h4><span class="review-date">20 يناير، 2026</span>
                            </div>
                        </div>
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <h4 class="review-title">فخامة في زجاجة</h4>
                    <p>المقدمة غنية بشكل لا يصدق مع الزعفران والفلفل. يجف ليصبح مزيج عنبر وعود دافئ وجميل. عطر جدي
                        لمناسبات جدية. أتمنى فقط لو كان السعر أقل قليلاً.</p>
                    <span class="review-verified"><i class="fas fa-check-circle"></i> شراء موثق</span>
                </div>
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
                <div class="product-card" data-id="1" data-name="نوار إيليغانس" data-price="185"
                    data-img="{{ asset('assets/clints/images/mens-perfume.png') }}">
                    <div class="product-card__image"><img src="{{ asset('assets/clints/images/mens-perfume.png') }}" alt="نوار إيليغانس">
                        <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                                onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__brand">لوكس بارفيوم</p>
                        <h4 class="product-card__name">نوار إيليغانس</h4>
                        <div class="product-card__rating"><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star-half-alt star"></i><span>(128)</span>
                        </div>
                        <p class="product-card__price">$185.00</p><button class="product-card__btn add-to-cart-btn">أضف
                            للسلة</button>
                    </div>
                </div>
                <div class="product-card" data-id="4" data-name="فيلفيت سانتال" data-price="195"
                    data-img="{{ asset('assets/clints/images/unisex-perfume.png') }}">
                    <div class="product-card__image"><img src="{{ asset('assets/clints/images/unisex-perfume.png') }}" alt="فيلفيت سانتال">
                        <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                                onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__brand">لوكس بارفيوم</p>
                        <h4 class="product-card__name">فيلفيت سانتال</h4>
                        <div class="product-card__rating"><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="far fa-star star empty"></i><span>(89)</span>
                        </div>
                        <p class="product-card__price">$195.00</p><button class="product-card__btn add-to-cart-btn">أضف
                            للسلة</button>
                    </div>
                </div>
                <div class="product-card" data-id="2" data-name="روز ميستيك" data-price="220"
                    data-img="{{ asset('assets/clints/images/womens-perfume.png') }}">
                    <div class="product-card__image"><img src="{{ asset('assets/clints/images/womens-perfume.png') }}" alt="روز ميستيك">
                        <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                                onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__brand">لوكس بارفيوم</p>
                        <h4 class="product-card__name">روز ميستيك</h4>
                        <div class="product-card__rating"><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star star"></i><span>(256)</span></div>
                        <p class="product-card__price">$220.00</p><button class="product-card__btn add-to-cart-btn">أضف
                            للسلة</button>
                    </div>
                </div>
                <div class="product-card" data-id="6" data-name="ميدنايت ليذر" data-price="210"
                    data-img="{{ asset('assets/clints/images/mens-perfume.png') }}">
                    <div class="product-card__image"><img src="{{ asset('assets/clints/images/mens-perfume.png') }}" alt="ميدنايت ليذر">
                        <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                                onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
                    </div>
                    <div class="product-card__info">
                        <p class="product-card__brand">لوكس بارفيوم</p>
                        <h4 class="product-card__name">ميدنايت ليذر</h4>
                        <div class="product-card__rating"><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                                class="fas fa-star star"></i><i class="far fa-star star empty"></i><span>(94)</span>
                        </div>
                        <p class="product-card__price">$210.00</p><button class="product-card__btn add-to-cart-btn">أضف
                            للسلة</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

  <!-- ========== التذييل ========== -->
  @include('clints.layout.footer')


    <div class="toast" id="toast"></div>
    <button class="back-to-top" id="backToTop" aria-label="العودة للأعلى"><i class="fas fa-chevron-up"></i></button>
    <script src="js/app.js"></script>
    <script src="js/product.js"></script>
</body>

</html>