<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create Educational Institute • Arzavo Platform">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ media('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ media('images/logo/icon-dark.png') }}">

    <title>@yield('title', 'Create Institute • Arzavo')</title>

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
                var theme = localStorage.getItem('arzaq_theme') || 'system';
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
</head>

<body class="bg-secondary text-theme-text font-normal min-h-screen antialiased flex flex-col justify-center items-center p-4 sm:p-6 relative selection:bg-brand selection:text-primary">
    {{-- Flash Alerts --}}
    <x-alert />

    {{-- Main Popup Modal Content Only --}}
    <main class="w-full max-w-xl mx-auto my-auto relative z-10">
        @yield('content')
    </main>
</body>

</html>
