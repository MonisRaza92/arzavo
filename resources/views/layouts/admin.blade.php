<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arzavo Institute Management Cockpit">
    <meta name="robots" content="noindex, nofollow">

    @php
        $tenant = app()->bound('currentTenant') ? app('currentTenant') : null;
        $siteName = $tenant?->name ?? config('app.name', 'Arzavo');
    @endphp

    <title>@yield('title', 'Admin Cockpit') • {{ $siteName }}</title>

    <!-- Performance Preconnects -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Anti-Theme-Flicker: Execute immediately before first paint -->
    <script>
        (function () {
            try {
                var theme = localStorage.getItem('arzaq_theme') || 'light';
                if (theme === 'black' || theme === 'obsidian') theme = 'dark';
                if (theme === 'white' || theme === 'snow') theme = 'light';
                if (theme === 'emerald') theme = 'aurora';

                var effective = theme;
                if (theme === 'system') {
                    var isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    effective = isDark ? 'dark' : 'light';
                }

                document.documentElement.setAttribute('data-theme', effective);

                var isLight = ['light', 'snow', 'ivory', 'frost', 'sage', 'white'].indexOf(effective) !== -1;
                if (isLight) {
                    document.documentElement.classList.add('light');
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            } catch (e) { }
        })();
    </script>

    @vite(['resources/css/tenant.css', 'resources/js/app.js'])

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
</head>

<body x-data="{ mobileSidebarOpen: false }"
      class="bg-primary text-theme-text font-normal min-h-screen antialiased selection:bg-brand selection:text-primary">

    {{-- Top Navbar (Structure preserved: Sticky top-0, bg-primary) --}}
    @include('tenant.admin.includes.navbar')

    {{-- Left Sidebar (Structure preserved: Fixed w-[260px], top-[57px], bg-primary) --}}
    @include('tenant.admin.includes.sidebar')

    {{-- Alerts & Modals --}}
    <x-alert />
    <x-content-model />

    {{-- Main Content Canvas (Adjacent to fixed sidebar: lg:ml-[280px], bg-secondary) --}}
    <main class="main-admin-content relative lg:ml-[280px] p-4 sm:p-6 bg-secondary min-h-dvh">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
