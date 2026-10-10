<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arzavo Multi-Tenant Workspace & Educational Management Platform">
    <meta name="author" content="Arzavo Team">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ media('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ media('images/logo/icon-dark.png') }}">

    <title>@yield('title', 'Workspaces') • Arzavo</title>

    <!-- Performance preconnects -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'])

    <!-- fontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    {{-- Navbar --}}
    @include('arzavo.admin.includes.navbar')
    {{-- Sidebar --}}
    @include('arzavo.admin.includes.sidebar')
    {{-- Alerts --}}
    <x-alert />
    {{-- Main content --}}
    <main class="main-admin-content relative lg:ml-65! p-4">
        @yield('content')
    </main>
</body>

</html>