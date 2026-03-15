<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Resident Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    @stack('styles')
</head>
<body>
    <div class="admin-container">
        @include('partials.sidebar-resident')
        <div class="main-content" id="mainContent">
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="header-right">
                    <img class="admin-logo" src="{{ asset('img/logo.png') }}" alt="Logo">
                </div>
            </header>

            <main class="content-area">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/admin/admin.js') }}"></script>
    @include('partials.loading-screen')
    @stack('scripts')
</body>
</html>
