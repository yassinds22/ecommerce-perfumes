<!-- Header -->
<header class="dashboard-header">
    <div class="header__right">
        <button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
        <div class="header__title">
            <h1>@yield('page_title', 'نظرة عامة')</h1>
            <p>@yield('page_subtitle', 'مرحباً بك مجدداً، إليك نظرة سريعة اليوم.')</p>
        </div>
    </div>
    <div class="header__left">
        <div class="header__search">
            <input type="text" placeholder="بحث...">
            <i class="fas fa-search"></i>
        </div>
        
        <!-- Notifications Dropdown -->
        <div class="notifications-dropdown">
            <button class="header__btn" title="الإشعارات" id="notifBtn">
                <i class="fas fa-bell"></i>
                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                <span class="dot" id="notifDot"></span>
                @endif
            </button>
            <div class="dropdown-menu" id="notifMenu">
                <div class="dropdown-header">
                    <span>التنبيهات</span>
                    <a href="#" onclick="markAllAsRead(event)">تحديد الكل كمقروء</a>
                </div>
                <div class="dropdown-body" id="notifList">
                    <div class="notif-loading">جاري التحميل...</div>
                </div>
                <div class="dropdown-footer">
                    <a href="#">عرض جميع التنبيهات</a>
                </div>
            </div>
        </div>

        <button class="header__btn" title="الوضع الليلي">
            <i class="fas fa-moon"></i>
        </button>
    </div>
</header>
