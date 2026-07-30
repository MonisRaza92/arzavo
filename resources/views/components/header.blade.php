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
    $pageMetaDesc = View::hasSection('meta_description') ? html_entity_decode(View::yieldContent('meta_description'), ENT_QUOTES, 'UTF-8') : ($settings['meta_description'] ?? $defaultDesc);
    $pageOgImage = View::hasSection('og_image') ? View::yieldContent('og_image') : url(media($customizes['favicon'] ?? $textToImageUrl));
    $pageKeywords = View::hasSection('meta_keywords') ? html_entity_decode(View::yieldContent('meta_keywords'), ENT_QUOTES, 'UTF-8') : ($settings['meta_keywords'] ?? $siteName . ', school, coaching institute, education, courses, books');
    $pageTitle = View::hasSection('title') ? html_entity_decode(View::yieldContent('title'), ENT_QUOTES, 'UTF-8') . ' • ' . $siteName : ($settings['meta_title'] ?? $siteName);
@endphp
@php
    $allowIndexing = $settings['allow_indexing'] ?? 1;
    $robotsDefault = ($allowIndexing == 0) ? 'noindex, nofollow' : ($settings['robots_meta'] ?? 'index, follow');
    $robotsDirectives = View::hasSection('robots') ? View::yieldContent('robots') : $robotsDefault;
@endphp
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $pageMetaDesc }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="{{ $robotsDirectives }}">
<link rel="alternate" hreflang="en" href="{{ url()->current() }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="manifest" href="{{ url('/manifest.webmanifest') }}">

@if(!empty($settings['google_site_verification']))
<meta name="google-site-verification" content="{{ $settings['google_site_verification'] }}">
@endif
@if(!empty($settings['bing_site_verification']))
<meta name="msvalidate.01" content="{{ $settings['bing_site_verification'] }}">
@endif

@if(!empty($settings['google_tag_manager']))
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $settings['google_tag_manager'] }}');</script>
<!-- End Google Tag Manager -->
@endif

@if(!empty($settings['google_analytics']))
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics'] }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $settings['google_analytics'] }}');
</script>
@endif

@if(!empty($settings['facebook_pixel_id']))
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $settings['facebook_pixel_id'] }}');
fbq('track', 'PageView');
</script>
@endif

@if(!empty($settings['microsoft_clarity_id']))
<!-- Microsoft Clarity Code -->
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "{{ $settings['microsoft_clarity_id'] }}");
</script>
@endif

@if(!empty($settings['schema_org_json']))
<!-- Custom Local Schema -->
<script type="application/ld+json">
{!! $settings['schema_org_json'] !!}
</script>
@endif

@if(!empty($settings['custom_head_tags']))
<!-- Custom Customizations Head Code -->
{!! $settings['custom_head_tags'] !!}
@endif

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