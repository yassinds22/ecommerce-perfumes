  <nav class="navbar" id="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="nav-logo">لوكس <span>بارفيوم</span></a>
      <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">الرئيسية</a></li>
        <li><a href="{{ route('shop') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">المتجر</a></li>
        @isset($parentCategories)
          @foreach($parentCategories as $cat)
            <li><a href="{{ route('shop', ['cat' => $cat->id]) }}" class="{{ request()->get('cat') == $cat->id ? 'active' : '' }}">{{ $cat->getTranslation('name', 'ar') }}</a></li>
          @endforeach
        @endisset
        <li><a href="#footer">من نحن</a></li>
      </ul>
      <div class="nav-actions">
        <div class="nav-user-dropdown" id="userDropdown">
          <button class="nav-action-btn" id="userBtn" aria-label="حسابي">
            <i class="far fa-user"></i>
          </button>
          <div class="dropdown-menu">
            @auth
              <span class="dropdown-header">أهلاً، {{ auth()->user()->name }}</span>
              @if(auth()->user()->role === 'Admin')
                <a href="{{ route('admin.index') }}"><i class="fas fa-cog"></i> لوحة التحكم</a>
              @endif
              <a href="{{ route('loyalty.index') }}"><i class="fas fa-crown"></i> نقاط الولاء</a>
              <a href="#" onclick="event.preventDefault(); document.getElementById('nav-logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
              </a>
              <form id="nav-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            @else
              <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
              <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> إنشاء حساب</a>
            @endauth
          </div>
        </div>
        <button class="nav-action-btn" id="searchBtn" aria-label="بحث">
          <i class="fas fa-search"></i>
        </button>
        <a href="{{ route('wishlist.index') }}" class="nav-action-btn" aria-label="المفضلة">
          <i class="far fa-heart"></i>
          @php $wishlistCount = auth()->check() ? auth()->user()->wishlist()->count() : 0; @endphp
          <span class="badge {{ $wishlistCount > 0 ? '' : 'hide-badge' }}" id="wishlistCount">{{ $wishlistCount }}</span>
        </a>
        <button class="nav-action-btn" id="cartToggle" aria-label="سلة التسوق">
          <i class="fas fa-shopping-bag"></i>
          <span class="badge" id="cartCount">0</span>
        </button>
        <div class="nav-hamburger" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </nav>

  <!-- القائمة المتنقلة -->
  <div class="mobile-nav" id="mobileNav">
    <a href="{{ route('home') }}">الرئيسية</a>
    <a href="{{ route('shop') }}">المتجر</a>
    @isset($parentCategories)
      @foreach($parentCategories as $cat)
        <a href="{{ route('shop', ['cat' => $cat->id]) }}" class="{{ request()->get('cat') == $cat->id ? 'active' : '' }}">{{ $cat->getTranslation('name', 'ar') }}</a>
      @endforeach
    @endisset
    <a href="#footer">من نحن</a>
  </div>
  <div class="nav-overlay" id="navOverlay"></div>

  <!-- سلة التسوق المصغرة -->
  <div class="cart-overlay" id="cartOverlay"></div>
  <aside class="mini-cart" id="miniCart">
    <div class="mini-cart__header">
      <h3>حقيبة التسوق</h3>
      <button class="mini-cart__close" id="miniCartClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="mini-cart__items" id="miniCartItems">
      <div class="mini-cart__empty" id="miniCartEmpty">
        <i class="fas fa-shopping-bag"></i>
        <p>حقيبتك فارغة</p>
        <a href="{{ route('shop') }}" class="btn btn-primary">تسوق الآن</a>
      </div>
    </div>
    <div class="mini-cart__footer" id="miniCartFooter" style="display:none;">
      <div class="mini-cart__total">
        <span>المجموع</span>
        <span id="miniCartTotal">$0.00</span>
      </div>
      <a href="{{ route('cart') }}" class="btn btn-primary">عرض السلة</a>
    </div>
  </aside>

  <!-- واجهة البحث الذكي -->
  <div class="search-overlay" id="searchOverlay">
    <div class="search-overlay__header">
      <button class="search-overlay__close" id="searchClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="search-overlay__content">
      <form action="{{ route('search') }}" method="GET">
        <div class="search-overlay__input-wrapper">
          <input type="text" name="q" id="searchInput" class="search-overlay__input" placeholder="ابحث عن عطر، براند، أو مكون عطري..." autocomplete="off">
          <i class="fas fa-search search-overlay__icon"></i>
        </div>
      </form>

      <div class="search-suggestions" id="searchSuggestions">
        <span class="search-suggestions__title">مقترحات البحث</span>
        <div class="search-suggestions__list" id="suggestionsList">
          <!-- سيتم تحميل المقترحات هنا عبر AJAX -->
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchBtn = document.getElementById('searchBtn');
      const searchOverlay = document.getElementById('searchOverlay');
      const searchClose = document.getElementById('searchClose');
      const searchInput = document.getElementById('searchInput');
      const suggestionsList = document.getElementById('suggestionsList');
      const searchSuggestions = document.getElementById('searchSuggestions');

      // فتح البحث
      if (searchBtn) {
        searchBtn.addEventListener('click', () => {
          searchOverlay.classList.add('active');
          setTimeout(() => searchInput.focus(), 300);
        });
      }

      // إغلاق البحث
      if (searchClose) {
        searchClose.addEventListener('click', () => {
          searchOverlay.classList.remove('active');
        });
      }

      // منطق الإكمال التلقائي
      let timeout = null;
      searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();

        if (query.length < 2) {
          searchSuggestions.classList.remove('active');
          return;
        }

        timeout = setTimeout(() => {
          fetch(`/search/suggestions?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
              suggestionsList.innerHTML = '';
              if (data.length > 0) {
                data.forEach(item => {
                  const suggestionHtml = `
                    <a href="/product/${item.id}" class="search-suggestions__item">
                      <div class="search-suggestions__img">
                        <img src="${item.image || '/assets/clints/img/placeholder.png'}" alt="${item.name}">
                      </div>
                      <div class="search-suggestions__info">
                        <span class="search-suggestions__name">${item.name}</span>
                        <span class="search-suggestions__brand">${item.brand || ''}</span>
                      </div>
                    </a>
                  `;
                  suggestionsList.insertAdjacentHTML('beforeend', suggestionHtml);
                });
                searchSuggestions.classList.add('active');
              } else {
                searchSuggestions.classList.remove('active');
              }
            });
        }, 300);
      });
    });
  </script>