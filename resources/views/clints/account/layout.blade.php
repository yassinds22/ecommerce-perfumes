@extends('clints.layout.master')

@section('content')
<section class="account-dashboard-wrapper" style="padding: 50px 0; background: #0f0f0f; min-height: 80vh; color: #fff;">
    <div class="container">
        <div class="account-container" style="display: grid; grid-template-columns: 280px 1fr; gap: 30px; align-items: start;">
            
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; position: sticky; top: 100px;">
                <div class="user-brief" style="margin-bottom: 30px; text-align: center;">
                    <div class="user-avatar" style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--color-gold), var(--color-gold-dark)); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #000; font-weight: 700;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h3 style="color: #fff; margin-bottom: 5px;">{{ auth()->user()->name }}</h3>
                    <p style="color: var(--color-text-dim); font-size: 0.9rem;">{{ auth()->user()->email }}</p>
                </div>

                <nav class="account-nav">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.index') }}" class="account-nav-link {{ request()->routeIs('account.index') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <i class="fas fa-th-large"></i> نظرة عامة
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.profile.index') }}" class="account-nav-link {{ request()->routeIs('account.profile.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <i class="fas fa-user"></i> الملف الشخصي
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.orders.index') }}" class="account-nav-link {{ request()->routeIs('account.orders.*') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-shopping-bag"></i> طلباتي
                                </div>
                                @php $ordersCount = auth()->user()->orders()->count(); @endphp
                                @if($ordersCount > 0)
                                    <span style="background: var(--color-gold); color: #000; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">{{ $ordersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.wishlist.index') }}" class="account-nav-link {{ request()->routeIs('account.wishlist.*') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-heart"></i> المفضلة
                                </div>
                                @php $wishlistCount = auth()->user()->wishlist()->count(); @endphp
                                <span id="wishlistCount" class="badge {{ $wishlistCount > 0 ? '' : 'hide-badge' }}" style="background: #ff4d4d; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">{{ $wishlistCount }}</span>
                            </a>
                        </li>
                         <li style="margin-bottom: 10px;">
                            <a href="{{ route('cart') }}" class="account-nav-link {{ request()->routeIs('cart') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-shopping-cart"></i> السلة
                                </div>
                                <span id="cartCount" class="badge" style="background: var(--color-gold); color: #000; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">0</span>
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.addresses.index') }}" class="account-nav-link {{ request()->routeIs('account.addresses.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <i class="fas fa-map-marker-alt"></i> عناويني
                            </a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('account.security.index') }}" class="account-nav-link {{ request()->routeIs('account.security.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; color: var(--color-text-dim); text-decoration: none; transition: 0.3s;">
                                <i class="fas fa-shield-alt"></i> الأمان
                            </a>
                        </li>
                        <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; color: #ff4d4d; background: transparent; border: none; cursor: pointer; transition: 0.3s; font-size: 1rem;">
                                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="account-content" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 30px; min-height: 500px;">
                @if(session('success'))
                    <div class="alert alert-success" style="padding: 15px; background: rgba(0, 255, 0, 0.1); border: 1px solid rgba(0, 255, 0, 0.2); color: #00ff00; border-radius: 10px; margin-bottom: 25px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" style="padding: 15px; background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.2); color: #ff4d4d; border-radius: 10px; margin-bottom: 25px;">
                        <ul style="margin: 0; padding-right: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('account_content')
            </div>

        </div>
    </div>
</section>

<style>
    .account-nav-link:hover, .account-nav-link.active {
        background: rgba(201, 168, 76, 0.1);
        color: var(--color-gold) !important;
    }
    .account-nav-link.active i {
        color: var(--color-gold);
    }
    
    .hide-badge {
        display: none !important;
    }
    
    /* Input and Select Visibility Fix */
    .account-content input, 
    .account-content select, 
    .account-content textarea {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        padding: 12px 15px !important;
        border-radius: 10px !important;
        width: 100%;
        outline: none;
        display: block;
    }
    
    .account-content label {
        display: block;
        color: var(--color-gold);
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    .account-content select option {
        background-color: #1a1a1a;
        color: #ffffff;
    }

    .account-content input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    @media (max-width: 991px) {
        .account-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
