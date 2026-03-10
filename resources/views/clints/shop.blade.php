<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

          <!-- نطاق السعر -->
          <div class="filter-group">
            <h4 class="filter-group__title">نطاق السعر <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              <div class="price-range">
                <input type="range" min="0" max="500" value="{{ request('max_price', 500) }}" id="priceRange" onchange="applyFilters()">
                <div class="price-range__labels"><span>$0</span><span id="priceValue">${{ request('max_price', 500) }}</span></div>
              </div>
            </div>
          </div>

          <!-- الأقسام -->
          <div class="filter-group">
            <h4 class="filter-group__title">الأقسام <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              @php $selectedCats = explode(',', request('cat', '')); @endphp
              @foreach($categories as $category)
              <label class="filter-check">
                <input type="checkbox" name="cat" value="{{ $category->id }}"
                  {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}
                  onchange="applyFilters()"> {{ $category->getTranslation('name', 'ar') }}
                <span>({{ $category->products_count }})</span>
              </label>
              @endforeach
            </div>
          </div>

          <!-- الماركات -->
          <div class="filter-group">
            <h4 class="filter-group__title">العلامة التجارية <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              @php $selectedBrands = explode(',', request('brand', '')); @endphp
              @foreach($brands as $brand)
              <label class="filter-check">
                <input type="checkbox" name="brand" value="{{ $brand->id }}"
                  {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }}
                  onchange="applyFilters()"> {{ $brand->name }}
                <span>({{ $brand->products_count }})</span>
              </label>
              @endforeach
            </div>
          </div>

          <!-- الجنس -->
          <div class="filter-group">
            <h4 class="filter-group__title">الجنس <i class="fas fa-chevron-down"></i></h4>
            <div class="filter-group__content">
              @php $selectedGenders = explode(',', request('gender', '')); @endphp
              <label class="filter-check">
                <input type="checkbox" name="gender" value="Men" {{ in_array('Men', $selectedGenders) ? 'checked' : '' }} onchange="applyFilters()"> رجالي
              </label>
              <label class="filter-check">
                <input type="checkbox" name="gender" value="Women" {{ in_array('Women', $selectedGenders) ? 'checked' : '' }} onchange="applyFilters()"> نسائي
              </label>
              <label class="filter-check">
                <input type="checkbox" name="gender" value="Unisex" {{ in_array('Unisex', $selectedGenders) ? 'checked' : '' }} onchange="applyFilters()"> مشترك
              </label>
            </div>
          </div>

          <button class="btn btn-primary filters__apply"
            onclick="applyFilters()">تطبيق
            الفلاتر</button>
        </aside>
        <div class="filters-overlay" id="filtersOverlay"></div>

        <!-- المنتجات -->
        <div class="shop-main">
          <div class="shop-toolbar">
            <div class="shop-toolbar__info">
              <p>عرض <strong>{{ $products->count() }}</strong> من <strong>{{ $products->total() }}</strong> نتيجة</p>
            </div>
            <div class="shop-toolbar__controls">
              <button class="shop-toolbar__filter-btn" id="filterToggle"><i class="fas fa-sliders-h"></i>
                فلاتر</button>
              <select class="shop-toolbar__sort" id="sortSelect" onchange="applyFilters()">
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر شعبية</option>
                <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>وصل حديثاً</option>
                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>السعر: من الأقل</option>
                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>السعر: من الأعلى</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>الأعلى تقييماً</option>
              </select>
              <div class="shop-toolbar__view">
                <button class="view-btn active" data-view="grid"><i class="fas fa-th"></i></button>
                <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
              </div>
            </div>
          </div>

          <div class="product-grid" id="productGrid">
            @foreach($products as $product)
            <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->getTranslation('name', 'ar') }}" data-price="{{ $product->price }}"
              data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
              <div class="product-card__image">
                <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}"
                  alt="{{ $product->getTranslation('name', 'ar') }}">
                @if($product->created_at->gt(now()->subDays(7)))
                  <span class="product-card__badge">جديد</span>
                @endif
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
                <div class="product-card__rating">
                  <i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                    class="fas fa-star star"></i><i class="fas fa-star-half-alt star"></i><span>(128)</span>
                </div>
                <p class="product-card__price">${{ $product->sale_price ?: $product->price }}</p>
                <button class="product-card__btn add-to-cart-btn">أضف للسلة</button>
              </div>
            </div>
            @endforeach
          </div>

          <div class="pagination">
            {{ $products->links('vendor.pagination.custom') }}
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== التذييل ========== -->
  @include('clints.layout.footer')

  <script src="{{asset('assets/clints/js/shop.js')}}"></script>
</body>

</html>