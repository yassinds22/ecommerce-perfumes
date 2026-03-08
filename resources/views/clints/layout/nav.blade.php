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