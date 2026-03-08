  <nav class="navbar" id="navbar">
    <div class="container">
      <a href="{{ route('home') }}" class="nav-logo">لوكس <span>بارفيوم</span></a>
      <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">الرئيسية</a></li>
        <li><a href="{{ route('shop') }}" class="{{ request()->is('shop*') ? 'active' : '' }}">المتجر</a></li>
        <li><a href="{{ route('shop', ['cat' => 'men']) }}">للرجال</a></li>
        <li><a href="{{ route('shop', ['cat' => 'women']) }}">للنساء</a></li>
        <li><a href="{{ route('product') }}" class="{{ request()->is('product*') ? 'active' : '' }}">المجموعات</a></li>
        <li><a href="#footer">من نحن</a></li>
      </ul>
      <div class="nav-actions">
        <div class="nav-user-dropdown" id="userDropdown">
          <button class="nav-action-btn" id="userBtn" aria-label="حسابي">
            <i class="far fa-user"></i>
          </button>
          <div class="dropdown-menu">
            <a href="#"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
            <a href="#"><i class="fas fa-user-plus"></i> إنشاء حساب</a>
          </div>
        </div>
        <button class="nav-action-btn" id="searchBtn" aria-label="بحث">
          <i class="fas fa-search"></i>
        </button>
        <button class="nav-action-btn" aria-label="المفضلة">
          <i class="far fa-heart"></i>
        </button>
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