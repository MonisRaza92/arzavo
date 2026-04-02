<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="school, coaching institute, education management, admissions, fees, staff, students, online classes, reports, Arzavo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Run your school or coaching institute smarter with Arzavo. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform">
    <meta name="author" content="Monis Raza Khan">
    <meta name="robots" content="index,follow">
    <link rel="alternate" hreflang="en" href="https://arzavo.com{{ request()->getRequestUri() }}" />

    <meta property="og:type" content="website">
    <meta property="og:title" content="Arzavo - Smarter School & Coaching Management Software">
    <meta property="og:description"
        content="Run your school or coaching institute smarter with Arzavo. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform">
    <meta property="og:url" content="https://arzavo.com{{ request()->getRequestUri() }}">
    <meta property="og:image" content="{{ url(media('images/logo/icon-dark.png')) }}">


    <link rel="icon" type="image/x-icon" href="{{ media('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ url(media('images/logo/icon-dark.png')) }}">
    <title>Arzavo - Smarter School & Coaching Management Software</title>
    <x-variables :customizes="$customizes" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- fontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">

    <!-- iOS support -->
    <link rel="apple-touch-icon" href="{{ url('images/logo/icon-dark.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="canonical" href="https://arzavo.com{{ request()->getRequestUri() }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Chart Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>

<body class="mesh-bg">
    <x-alert />
    {{-- Main content --}}
    <main>
        @yield('content')
    </main>
</body>

</html>