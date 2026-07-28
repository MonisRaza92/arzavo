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

    $firstLetter = strtoupper(substr($siteName, 0, 1));
    $textToImageUrl = "https://ui-avatars.com/api/?name={$firstLetter}&background=000000&color=ffffff&size=100&font-size=0.7&bold=true";

    $defaultDesc = 'Welcome to ' . $siteName . '. Explore our courses, books, educational programs and resources online.';
    $pageMetaDesc = View::hasSection('meta_description') ? View::yieldContent('meta_description') : ($settings['meta_description'] ?? $defaultDesc);
    $pageOgImage = View::hasSection('og_image') ? View::yieldContent('og_image') : url(media($customizes['logo'] ?? $textToImageUrl));
    $pageKeywords = View::hasSection('meta_keywords') ? View::yieldContent('meta_keywords') : ($settings['meta_keywords'] ?? $siteName . ', school, coaching institute, education, courses, books');
    $pageTitle = View::hasSection('title') ? View::yieldContent('title') . ' • ' . $siteName : ($settings['meta_title'] ?? $siteName);
@endphp
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $pageMetaDesc }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="@yield('robots', 'index,follow')">
<link rel="alternate" hreflang="en" href="{{ url()->current() }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="manifest" href="{{ url('/manifest.webmanifest') }}">

<!-- Open Graph / Social Sharing -->
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageMetaDesc }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $pageOgImage }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageMetaDesc }}">
<meta name="twitter:image" content="{{ $pageOgImage }}">

<!-- Favicon & Icons -->
<link rel="icon" type="image/x-icon" href="{{ media($customizes['favicon'] ?? $textToImageUrl) }}">
<link rel="shortcut icon" href="{{ url(media($customizes['favicon'] ?? $textToImageUrl)) }}">
<link rel="apple-touch-icon" href="{{ url(media($customizes['favicon'] ?? $textToImageUrl)) }}">

<title>{{ $pageTitle }}</title>

<x-variables :customizes="$customizes" />

<!-- Performance preconnects -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- fontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

<meta name="theme-color" content="{{ $customizes['theme_color'] ?? '#ffffff' }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

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
        "logo" => url(media($customizes['logo'] ?? $textToImageUrl)),
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}
</script>

<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES) !!}
</script>

<!-- Chart Js & Sortable -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>