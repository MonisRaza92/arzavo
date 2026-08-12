<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="school, coaching institute, education management, admissions, fees, staff, students, online classes, reports, Arzavo, Arzaq Insights">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Arzavo by Arzaq Insights — run your school or coaching institute smarter. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform.">
    <meta name="author" content="Arzaq Insights">
    <meta name="robots" content="index,follow">
    <link rel="alternate" hreflang="en" href="https://arzavo.com{{ request()->getRequestUri() }}" />

    <meta property="og:type" content="website">
    <meta property="og:title" content="Arzavo by Arzaq Insights — Smarter School & Coaching Management Software">
    <meta property="og:description"
        content="Arzavo by Arzaq Insights — run your school or coaching institute smarter. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform.">
    <meta property="og:url" content="https://arzavo.com{{ request()->getRequestUri() }}">
    <meta property="og:image" content="{{ url(media('images/logo/icon-dark.png')) }}">
    <meta property="og:site_name" content="Arzavo by Arzaq Insights">


    <link rel="icon" type="image/x-icon" href="{{ media('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ url(media('images/logo/icon-dark.png')) }}">
    <title>@yield('title', 'Arzavo by Arzaq Insights — Smarter School & Coaching Management Software')</title>
    <x-variables :customizes="$customizes" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- fontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

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

    {{-- Organization + SoftwareApplication Structured Data --}}
    @php
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Arzaq Insights',
        'url' => 'https://arzaqinsights.com',
        'logo' => url(media('images/logo/icon-dark.png')),
        'brand' => [
            '@type' => 'Brand',
            'name' => 'Arzavo',
        ],
        'sameAs' => [
            'https://arzaqinsights.com',
            'https://arzavo.com',
        ],
    ];

    $appSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => 'Arzavo',
        'url' => 'https://arzavo.com',
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem' => 'Web',
        'description' => 'Arzavo by Arzaq Insights — the complete ERP & LMS platform for schools, coaching centers, and digital academies.',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'INR',
        ],
        'creator' => [
            '@type' => 'Organization',
            'name' => 'Arzaq Insights',
            'url' => 'https://arzaqinsights.com',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Arzaq Insights',
            'url' => 'https://arzaqinsights.com',
        ],
    ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($appSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
</head>

<body class="mesh-bg">
    <x-alert />
    {{-- Main content --}}
    <main>
        @yield('content')
    </main>
</body>

</html>