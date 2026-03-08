<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المتجر — لوكس بارفيوم</title>
    <meta name="description"
        content="تصفح مجموعة العطور الفاخرة لدينا. فلتر حسب العلامة التجارية، نوع العطر، الجنس، ونطاق السعر.">
    <link rel="stylesheet" href="{{asset('assets/clints/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/clints/css/shop.css')}}">
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

  <!-- رأس الصفحة -->
  <div class="page-spacer"></div>
  <section class="shop-header">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('home') }}">الرئيسية</a>
        <span class="separator">/</span>
        <span class="current">المتجر</span>
      </div>
      <h1>مجموعتنا</h1>
      <p class="section-subtitle">استكشف مجموعة العطور الفاخرة لدينا — من الكلاسيكيات الخالدة إلى الإصدارات
        الجديدة</p>
    </div>
  </section>

  <!-- تخطيط المتجر -->
  <section class="section shop-section">
    <div class="container">
      <div class="shop-layout">
        <!-- الفلاتر الجانبية -->
        <aside class="filters" id="filtersPanel">
          <div class="filters__header">
            <h3>الفلاتر</h3>
            <button class="filters__close" id="filtersClose"><i class="fas fa-times"></i></button>
          </div>

          <div class="filter-group">
            <h4 class="filter-group__title">نطاق السعر <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <div class="price-range">
                <input type="range" min="0" max="500" value="500" id="priceRange">
                <div class="price-range__labels"><span>$0</span><span id="priceValue">$500</span></div>
              </div>
            </div>
          </div>

          <div class="filter-group">
            <h4 class="filter-group__title">العلامة التجارية <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <label class="filter-check"><input type="checkbox" checked> لوكس بارفيوم
                <span>(12)</span></label>
              <label class="filter-check"><input type="checkbox"> ميزون رويال <span>(8)</span></label>
              <label class="filter-check"><input type="checkbox"> مجموعة العود <span>(6)</span></label>
              <label class="filter-check"><input type="checkbox"> فلور دو لوكس <span>(10)</span></label>
              <label class="filter-check"><input type="checkbox"> نوار أتيلييه <span>(5)</span></label>
            </div>
          </div>

          <div class="filter-group">
            <h4 class="filter-group__title">نوع العطر <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <label class="filter-check"><input type="checkbox"> خشبي</label>
              <label class="filter-check"><input type="checkbox"> زهري</label>
              <label class="filter-check"><input type="checkbox"> شرقي</label>
              <label class="filter-check"><input type="checkbox"> منعش</label>
              <label class="filter-check"><input type="checkbox"> عودي</label>
            </div>
          </div>

          <div class="filter-group">
            <h4 class="filter-group__title">الجنس <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <label class="filter-check"><input type="checkbox"> رجالي</label>
              <label class="filter-check"><input type="checkbox"> نسائي</label>
              <label class="filter-check"><input type="checkbox"> مشترك</label>
            </div>
          </div>

          <div class="filter-group">
            <h4 class="filter-group__title">الحجم <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <label class="filter-check"><input type="checkbox"> 30 مل</label>
              <label class="filter-check"><input type="checkbox"> 50 مل</label>
              <label class="filter-check"><input type="checkbox"> 100 مل</label>
              <label class="filter-check"><input type="checkbox"> 200 مل</label>
            </div>
          </div>

          <button class="btn btn-primary filters__apply"
            onclick="document.getElementById('filtersPanel').classList.remove('active'); document.getElementById('filtersOverlay').classList.remove('active');">تطبيق
            الفلاتر</button>
        </aside>
        <div class="filters-overlay" id="filtersOverlay"></div>

        <!-- المنتجات -->
        <div class="shop-main">
          <div class="shop-toolbar">
            <div class="shop-toolbar__info">
              <p>عرض <strong>12</strong> من <strong>48</strong> نتيجة</p>
            </div>
            <div class="shop-toolbar__controls">
              <button class="shop-toolbar__filter-btn" id="filterToggle"><i class="fas fa-sliders-h"></i>
                فلاتر</button>
              <select class="shop-toolbar__sort" id="sortSelect">
                <option value="popular">الأكثر شعبية</option>
                <option value="new">وصل حديثاً</option>
                <option value="price-low">السعر: من الأقل</option>
                <option value="price-high">السعر: من الأعلى</option>
                <option value="rating">الأعلى تقييماً</option>
              </select>
              <div class="shop-toolbar__view">
                <button class="view-btn active" data-view="grid"><i class="fas fa-th"></i></button>
                <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
              </div>
            </div>
          </div>

          <div class="product-grid" id="productGrid">
            <div class="product-card" data-id="1" data-name="نوار إيليغانس" data-price="185"
              data-img="{{asset('assets/clints/images/mens-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/mens-perfume.png')}}"
                  alt="نوار إيليغانس"><span class="product-card__badge">جديد</span>
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">نوار إيليغانس</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star-half-alt star"></i><span>(128)</span></div>
                <p class="product-card__price">$185.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="2" data-name="روز ميستيك" data-price="220"
              data-img="{{asset('assets/clints/images/womens-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/womens-perfume.png')}}"
                  alt="روز ميستيك">
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">روز ميستيك</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><span>(256)</span>
                </div>
                <p class="product-card__price">$220.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="3" data-name="عنبر عود ملكي" data-price="340"
              data-img="{{asset('assets/clints/images/arabic-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/arabic-perfume.png')}}"
                  alt="عنبر عود ملكي"><span class="product-card__badge">الأكثر مبيعاً</span>
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">عنبر عود ملكي</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><span>(312)</span>
                </div>
                <p class="product-card__price">$340.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="4" data-name="فيلفيت سانتال" data-price="195"
              data-img="{{asset('assets/clints/images/unisex-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/unisex-perfume.png')}}"
                  alt="فيلفيت سانتال">
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">فيلفيت سانتال</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="far fa-star star empty"></i><span>(89)</span></div>
                <p class="product-card__price">$195.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="5" data-name="ياسمين ذهبي" data-price="275"
              data-img="{{asset('assets/clints/images/womens-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/womens-perfume.png')}}"
                  alt="ياسمين ذهبي"><span class="product-card__badge">محدود</span>
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">ياسمين ذهبي</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star-half-alt star"></i><span>(167)</span></div>
                <p class="product-card__price">$275.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="6" data-name="ميدنايت ليذر" data-price="210"
              data-img="{{asset('assets/clints/images/mens-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/mens-perfume.png')}}"
                  alt="ميدنايت ليذر">
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">لوكس بارفيوم</p>
                <h4 class="product-card__name">ميدنايت ليذر</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="far fa-star star empty"></i><span>(94)</span></div>
                <p class="product-card__price">$210.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="7" data-name="إيريس أبسولو" data-price="290"
              data-img="{{asset('assets/clints/images/womens-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/womens-perfume.png')}}"
                  alt="إيريس أبسولو">
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">فلور دو لوكس</p>
                <h4 class="product-card__name">إيريس أبسولو</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><span>(201)</span>
                </div>
                <p class="product-card__price">$290.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="8" data-name="أرز ودخان" data-price="165"
              data-img="{{asset('assets/clints/images/unisex-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/unisex-perfume.png')}}"
                  alt="أرز ودخان"><span class="product-card__badge">تخفيض</span>
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">نوار أتيلييه</p>
                <h4 class="product-card__name">أرز ودخان</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="far fa-star star empty"></i><span>(76)</span></div>
                <p class="product-card__price">$165.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
            <div class="product-card" data-id="9" data-name="عود ملكي مكثف" data-price="420"
              data-img="{{asset('assets/clints/images/arabic-perfume.png')}}">
              <div class="product-card__image"><img src="{{asset('assets/clints/images/arabic-perfume.png')}}"
                  alt="عود ملكي مكثف">
                <div class="product-card__actions"><button><i class="far fa-heart"></i></button><button
                    onclick="window.location.href='{{ route('product') }}'"><i class="far fa-eye"></i></button></div>
              </div>
              <div class="product-card__info">
                <p class="product-card__brand">مجموعة العود</p>
                <h4 class="product-card__name">عود ملكي مكثف</h4>
                <div class="product-card__rating"><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><span>(189)</span>
                </div>
                <p class="product-card__price">$420.00</p><button class="product-card__btn add-to-cart-btn">أضف
                  للسلة</button>
              </div>
            </div>
          </div>

          <div class="pagination">
            <button class="pagination__btn disabled"><i class="fas fa-chevron-right"></i></button>
            <button class="pagination__page active">1</button>
            <button class="pagination__page">2</button>
            <button class="pagination__page">3</button>
            <span class="pagination__dots">...</span>
            <button class="pagination__page">6</button>
            <button class="pagination__btn"><i class="fas fa-chevron-left"></i></button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== التذييل ========== -->
  @include('clints.layout.footer')

    <div class="toast" id="toast"></div>
    <button class="back-to-top" id="backToTop" aria-label="العودة للأعلى"><i class="fas fa-chevron-up"></i></button>
    <script src="{{asset('assets/clints/js/app.js')}}"></script>
    <script src="{{asset('assets/clints/js/shop.js')}}"></script>
</body>

</html>