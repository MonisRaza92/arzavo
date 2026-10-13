<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Arzavo — Multi-Tenant Educational Operating System & ERP LMS Platform')</title>
    <meta name="description" content="@yield('description', 'Arzavo is an enterprise multi-tenant education management platform. Manage admissions, courses, fees, exams, attendance, faculty, custom domains, and student operations with dedicated white-label branding.')">
    <meta name="keywords" content="school erp, lms platform, education management system, multi tenant lms, institute software, coaching management, student portal, white label lms, arzavo, arzaq insights">
    <meta name="author" content="Arzavo Team • Arzaq Insights">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Arzavo">
    <meta property="og:title" content="@yield('title', 'Arzavo — Multi-Tenant Educational Operating System & ERP LMS Platform')">
    <meta property="og:description" content="@yield('description', 'Arzavo is an enterprise multi-tenant education management platform. Manage admissions, courses, fees, exams, attendance, faculty, custom domains, and student operations with dedicated white-label branding.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/website/hero-showcase.jpg') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Arzavo — Multi-Tenant Educational Operating System & ERP LMS Platform')">
    <meta name="twitter:description" content="@yield('description', 'Arzavo is an enterprise multi-tenant education management platform. Manage admissions, courses, fees, exams, attendance, faculty, custom domains, and student operations with dedicated white-label branding.')">
    <meta name="twitter:image" content="{{ asset('images/website/hero-showcase.jpg') }}">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/icon-dark.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/icon-dark.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/icon-dark.png') }}">
    <meta name="theme-color" content="#0e0e0e">

    <!-- Performance Preconnects -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

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

    <!-- Theme & App Scripts (theme.css only - no app.css) -->
    @vite(['resources/css/theme.css', 'resources/js/app.js'])

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />

    <!-- Static Structured Data (JSON-LD) for SEO Rich Snippets -->
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'SoftwareApplication',
      'name' => 'Arzavo',
      'applicationCategory' => 'EducationalApplication',
      'operatingSystem' => 'Web, iOS, Android',
      'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'INR'
      ],
      'description' => 'Multi-Tenant Educational ERP & LMS operating system for schools, academies, and coaching institutes.'
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Organization',
      'name' => 'Arzavo',
      'url' => 'https://arzavo.com',
      'logo' => 'https://arzavo.com/images/logo/icon-dark.png',
      'sameAs' => [
        'https://twitter.com/arzavo',
        'https://linkedin.com/company/arzaq-insights'
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
</head>

<body class="bg-secondary text-theme-text font-normal min-h-screen selection:bg-brand selection:text-primary overflow-x-hidden antialiased" x-data="{ mobileNav: false }">

    @yield('content')

    @stack('scripts')
</body>

</html>
