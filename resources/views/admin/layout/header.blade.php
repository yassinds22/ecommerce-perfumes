<!-- Header -->
<header class="dashboard-header">
    <div class="header__right">
        <button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
        <div class="header__title">
            <h1>@yield('page_title', 'نظرة عامة')</h1>
            <p>@yield('page_subtitle', 'مرحباً بك في لوحة التحكم')</p>
        </div>
    </div>
    <div class="header__left">
        <div class="header__search">
            <input type="text" placeholder="بحث...">
            <i class="fas fa-search"></i>
        </div>
        <button class="header__btn" title="الإشعارات">
            <i class="fas fa-bell"></i>
            <span class="dot"></span>
        </button>
        <button class="header__btn" title="الوضع الليلي">
            <i class="fas fa-moon"></i>
        </button>
    </div>
</header>
