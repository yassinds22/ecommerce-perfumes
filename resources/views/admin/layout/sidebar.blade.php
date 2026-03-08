<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
    <div class="sidebar__logo">
        <div class="sidebar__logo-icon"><i class="fas fa-gem"></i></div>
        <h2>لوكس <span>بارفيوم</span></h2>
    </div>

    <nav class="sidebar__nav">
        <div class="sidebar__label">القائمة الرئيسية</div>
        <a href="{{ route('admin.index') }}" class="sidebar__link {{ request()->routeIs('admin.index') ? 'active' : '' }}" data-section="overview">
            <i class="fas fa-th-large"></i> نظرة عامة
        </a>
        <a href="#" class="sidebar__link" data-section="products">
            <i class="fas fa-box-open"></i> المنتجات
            <span class="badge">9</span>
        </a>
        <a href="#" class="sidebar__link" data-section="orders">
            <i class="fas fa-shopping-cart"></i> الطلبات
            <span class="badge">3</span>
        </a>
        <a href="#" class="sidebar__link" data-section="customers">
            <i class="fas fa-users"></i> العملاء
        </a>
        <a href="#" class="sidebar__link" data-section="reviews">
            <i class="fas fa-star"></i> التقييمات
        </a>
        <a href="#" class="sidebar__link" data-section="offers">
            <i class="fas fa-tags"></i> العروض
        </a>

        <div class="sidebar__label" style="margin-top:16px">الإعدادات</div>
        <a href="#" class="sidebar__link">
            <i class="fas fa-cog"></i> الإعدادات
        </a>
        <a href="{{ url('/') }}" class="sidebar__link">
            <i class="fas fa-external-link-alt"></i> عرض الموقع
        </a>
    </nav>

    <div class="sidebar__footer">
        <div class="sidebar__user">
            <div class="sidebar__avatar">يم</div>
            <div class="sidebar__user-info">
                <h4>ياسين مدير</h4>
                <span>مدير النظام</span>
            </div>
        </div>
    </div>
</aside>
