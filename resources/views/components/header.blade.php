@php
    $tenant = app()->bound('currentTenant')
        ? app('currentTenant')
        : null;

    $siteName = $tenant?->name
        ?? config('app.name'); // Arzavo fallback

    $admin = $tenant?->admin;

    $author = $admin
        ? trim(($admin->fname ?? '') . ' ' . ($admin->lname ?? ''))
        : 'Arzavo Team';
@endphp
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="keywords"
    content="{{ $settings['meta_keywords'] ?? $siteName . ', school, coaching institute, education' }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
    content="{{ $settings['meta_description'] ?? 'Run your school or coaching institute smarter with Arzavo. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform' }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="@yield('robots', 'index,follow')">
<link rel="alternate" hreflang="en" href="{{ url()->current() }}">

<meta property="og:type" content="website">
<meta property="og:title" content="@hasSection('title')
    @yield('title') • {{ $siteName }}
@else
    {{ $settings['meta_title'] ?? $siteName }}
@endif">
<meta property="og:description"
    content="{{ $settings['meta_description'] ?? 'Run your school or coaching institute smarter with Arzavo. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ url(media($customizes['logo'] ?? 'images/logo/icon-dark.png')) }}">


<link rel="icon" type="image/x-icon" href="{{ media($customizes['favicon'] ?? 'images/logo/icon-dark.png') }}">
<link rel="shortcut icon" href="{{ url(media($customizes['favicon'] ?? 'images/logo/icon-dark.png')) }}">
<title>
    @hasSection('title')
        @yield('title') • {{ $siteName }}
    @else
        {{ $settings['meta_title'] ?? $siteName }}
    @endif
</title>
<x-variables :customizes="$customizes" />
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- fontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#ffffff">

<!-- iOS support -->
<link rel="apple-touch-icon" href="{{ url(media($customizes['favicon'] ?? 'images/logo/icon-dark.png')) }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<link rel="canonical" href="{{ url()->current() }}">
@php
    $websiteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => $siteName ?? 'Arzavo',
        "url" => tenant_url(),
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => request()->getSchemeAndHttpHost() . "/search?q={search_term_string}",
            "query-input" => "required name=search_term_string"
        ]
    ];

    $orgSchema = [
        "@context" => "https://schema.org",
        "@type" => "EducationalOrganization",
        "name" => $siteName ?? 'Arzavo',
        "url" => tenant_url(),
        "logo" => url(media($customizes['logo'] ?? 'images/logo/icon-dark.png')),
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}
</script>

<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES) !!}
</script>

<!-- Google fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

<!-- Chart Js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>