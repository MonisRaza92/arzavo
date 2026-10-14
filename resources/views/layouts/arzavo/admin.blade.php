<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arzavo Super Admin Management Cockpit">
    <meta name="author" content="Arzavo Team">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ media('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ media('images/logo/icon-dark.png') }}">

    <title>@yield('title', 'Admin Cockpit') • Arzavo</title>

    <!-- Performance preconnects -->
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

    @vite(['resources/css/theme.css', 'resources/js/app.js'])

    <!-- fontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body x-data="{
    search: (new URLSearchParams(window.location.search)).get('search') || '',
    filter: 'all',
}"
class="bg-primary text-theme-text font-normal min-h-screen antialiased selection:bg-brand selection:text-primary">

    {{-- Sidebar --}}
    @include('arzavo.admin.includes.sidebar')

    {{-- Alerts --}}
    <x-alert />

    {{-- Main Content Canvas --}}
    <main class="relative bg-secondary rounded-tl-lg lg:ml-[260px] px-4 sm:px-6 pb-6 min-h-dvh">
        {{-- Navbar Header --}}
        @include('arzavo.admin.includes.navbar')

        {{-- Page Content --}}
        @yield('content')
    </main>
</body>

</html>