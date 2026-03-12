<aside class="sidebar">
    <div class="sidebar__logo">
        <div class="sidebar__logo-icon"><i class="fas fa-crown"></i></div>
        <h2>لوكس<span>بارفيوم</span></h2>
    </div>

    <nav class="sidebar__nav">
        <div class="sidebar__label">القائمة الرئيسية</div>
        <a href="{{ route('admin.index') }}" class="sidebar__link {{ request()->routeIs('admin.index') ? 'active' : '' }}" data-section="overview">
            <i class="fas fa-th-large"></i> نظرة عامة
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="sidebar__link {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> التحليلات
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar__link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> الأقسام
        </a>
        <a href="{{ route('admin.brands.index') }}" class="sidebar__link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
            <i class="fas fa-certificate"></i> الماركات
        </a>
        <a href="{{ route('admin.products.index') }}" class="sidebar__link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> المنتجات
        </a>
        <a href="{{ route('admin.fragrance-notes.index') }}" class="sidebar__link {{ request()->routeIs('admin.fragrance-notes.*') ? 'active' : '' }}">
            <i class="fas fa-wind"></i> مكونات العطر
        </a>

        <a href="{{ route('admin.orders.index') }}" class="sidebar__link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" data-section="orders">
            <i class="fas fa-shopping-cart"></i> الطلبات
            <span class="badge">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
        </a>
        <a href="{{ route('admin.customers.index') }}" class="sidebar__link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" data-section="customers">
            <i class="fas fa-users"></i> العملاء
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="sidebar__link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" data-section="reviews">
            <i class="fas fa-star"></i> التقييمات
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="sidebar__link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> العروض
        </a>

    </nav>

    <div class="sidebar__footer">
        <div class="sidebar__nav-secondary" style="margin-bottom: 20px;">
            <a href="{{ route('admin.settings.index') }}" class="sidebar__link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}" style="padding: 8px 12px; font-size: 0.85rem;">
                <i class="fas fa-cog" style="font-size: 0.9rem;"></i> الإعدادات
            </a>
            <a href="{{ url('/') }}" target="_blank" class="sidebar__link" style="padding: 8px 12px; font-size: 0.85rem;">
                <i class="fas fa-external-link-alt" style="font-size: 0.9rem;"></i> عرض الموقع
            </a>
            <a href="#" class="sidebar__link" style="color: #ff4d4d; padding: 8px 12px; font-size: 0.85rem;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt" style="font-size: 0.9rem;"></i> تسجيل الخروج
            </a>
        </div>

        <div class="sidebar__user">
            <div class="sidebar__avatar">يم</div>
            <div class="sidebar__user-info">
                <h4>ياسين مدير</h4>
                <span>مدير النظام</span>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</aside>
