<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوكس بارفيوم — اكتشف عطرك المميز')</title>
    <link rel="stylesheet" href="{{asset('assets/clints/css/style.css')}}">
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- ========== شريط التنقل ========== -->
    @include('clints.layout.nav')

    <main>
        @yield('content')
    </main>

    <!-- ========== التذييل ========== -->
    @include('clints.layout.footer')

    @yield('scripts')
</body>

</html>
