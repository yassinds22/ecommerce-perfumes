<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم — لوكس بارفيوم')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/admin/css/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('styles')
</head>

<body>
    <div class="dashboard-layout">
        @include('admin.layout.sidebar')

        <div class="sidebar-overlay"></div>

        <div class="main-content">
            @include('admin.layout.header')

            @yield('content')
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="dashToast"></div>

    <script src="{{ asset('assets/admin/js/dashboard.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
</body>

</html>
