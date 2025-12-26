@php
// Example JSON data (usually DB se load hoga)
$navbar = $section->settings ?? [];
$colors = $section->colorScheme->scheme_colors;


$navBehavior = $navbar['navbar_behavior'] ?? 'sticky';
$border =$navbar['divider'] ?? 'enable';
$navHeight = $navbar['navbar_height'] ?? 'standard';
$logoSize = $navbar['logo_size'] ?? '35';
$linkPosition = $navbar['links_position'] ?? 'left';
$linkSpace = $navbar['links_spacing'] ?? 'normal';
$fontSize = $navbar['font_size'] ?? 'small';
$fontWeight = $navbar['font_weight'] ?? 'normal';
$textTransform = $navbar['text_transform'] ?? 'capitalize';
$iconStyle = $navbar['icon_style'] ?? 'regular';
$admissionIcon = $navbar['admission_form_icon'] ?? 'enable';


$mobileMenu = $navbar['mobile_menu'] ?? 'enable';
$logoPosition = $navbar['logo_position'] ?? 'left';
$socialIcons = $navbar['social_icons'] ?? 'enable';


// Filter only active links
$linksData = $navbar['navlinks'] ?? ['home', 'about', 'contact'];

// Agar string hai (JSON format me)
if (is_string($linksData)) {
$decoded = json_decode($linksData, true);
if (json_last_error() === JSON_ERROR_NONE) {
$linksData = $decoded;
} else {
// Agar normal string hai (jaise "home"), to array bana lo
$linksData = [$linksData];
}
}

// Safety ke liye ensure karo ke hamesha array hi ho
if (!is_array($linksData)) {
$linksData = [];
}

$mobileLinks = $navbar['navlinks_mobile'] ?? ['home', 'about', 'contact'];

// Agar string hai (JSON format me)
if (is_string($mobileLinks)) {
$decoded = json_decode($mobileLinks, true);
if (json_last_error() === JSON_ERROR_NONE) {
$mobileLinks = $decoded;
} else {
// Agar normal string hai (jaise "home"), to array bana lo
$mobileLinks = [$mobileLinks];
}
}

// Safety ke liye ensure karo ke hamesha array hi ho
if (!is_array($mobileLinks)) {
$mobileLinks = [];
}

@endphp
<nav style="--arzavo-border-color: {{ $colors->border ?? '#d4d4d4d' }}; background: {{ $colors->background ?? '#ffffff' }};" data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" class="preview-section hidden md:block py-0 z-20 {{ $navBehavior === 'sticky' ? 'sticky top-0 left-0' : '' }} {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">
    <div class="container navbar flex justify-between items-center w-full {{ $navHeight === 'compact' ? 'py-2' : ($navHeight === 'standard' ? 'py-3' : 'py-4') }}">
        <div class="logo-links-icons w-full flex justify-between gap-8 items-center">
            <a href="{{ route('home') }}" class="w-fit shrink-0">
                @if ($customizes['logo'])
                <img src="{{ asset($customizes['logo']) }}" alt="Logo" class="w-auto logo" style="height: {{ $logoSize }}px;">
                @else
                <h2 class="text-xl font-semibold" style="color: {{ $colors->subheading ?? '#000000' }};">{{ app('currentTenant')->name }}</h2>
                @endif
            </a>
            <div class="links flex {{ $linkSpace === 'normal' ? 'gap-6' : ($linkSpace === 'wide' ? 'gap-9' : 'gap-3') }} w-full shrink items-center {{ $linkPosition ===  'center' ? 'justify-center' : ($linkPosition === 'right' ? 'justify-end' : 'justify-start') }}" style="color: {{ $colors->subheading ?? '#000000' }};">
                @foreach ($linksData as $link)
                <a href="/{{ $link }}" class="font-{{ $fontWeight }} {{ $textTransform }} {{ $fontSize === 'medium' ? 'text-lg' : ($fontSize === 'large' ? 'text-xl' : 'text-md') }}">{{ $link }}</a>
                @endforeach
            </div>
            @if (!Auth::guard('tenant')->check())
            <div class="menu flex items-center gap-6 w-fit shrink-0" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-{{ $iconStyle }} fa-file-lines text-lg {{ $admissionIcon === 'enable' ? '' : 'hidden!' }}" title="Admission Form"></i>
                <a href="{{ route('tenant.login.form') }}"><i class="fa-{{ $iconStyle }} fa-user text-lg"></i></a>
            </div>
            @else
            <div class="menu flex items-center gap-6 w-fit shrink-0" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-{{ $iconStyle }} fa-file-lines text-lg {{ $admissionIcon === 'enable' ? '' : 'hidden!' }} {{ $user->role === 'student' ? 'hidden!' : '' }}" title="Admission Form"></i>
                <i class="fa-{{ $iconStyle }} fa-bell text-lg" title="Notifications"></i>
                <i class="fa-{{ $iconStyle }} fa-compass text-lg" title="Dashboard"></i>
                <i class="fa-{{ $iconStyle }} fa-user text-lg"></i>
            </div>
            @endif
        </div>
    </div>
</nav>

<!-- Mobile Navbar -->

<nav data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" style="--arzavo-border-color: {{ $colors->border ?? '#d4d4d4d' }}; background: {{ $colors->background ?? '#ffffff' }};" class="md:hidden py-0 z-20 {{ $navBehavior === 'sticky' ? 'sticky top-0 left-0' : '' }} {{ $border === 'enable' ? 'arzavo-border-bottom' : '' }}">
    <div class="container navbar flex justify-between items-center w-full {{ $navHeight === 'compact' ? 'py-1' : ($navHeight === 'standard' ? 'py-2' : 'py-4') }}">
        <div class="logo-links-icons w-full flex justify-between gap-8 items-center">
            @if ($logoPosition === 'left')
            <div class="logo-and-menu flex items-center gap-4" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-solid fa-bars text-xl {{ $mobileMenu === 'enable' ? '' : 'hidden!' }}" onclick="document.getElementById('mobileMenu').classList.remove('-translate-x-full'); document.getElementById('mobileMenu').classList.add('arzavo-border-right')"></i>
                <a href="{{ route('home') }}">
                    @if ($customizes['logo'])
                    <img src="{{ asset($customizes['logo'] ?? '') }}" alt="Logo" class="w-auto logo" style="height: {{ $logoSize }}px;">
                    @else
                    <h2 class="text-xl font-semibold" style="color: {{ $colors->subheading ?? '#000000' }};">{{ app('currentTenant')->name }}</h2>
                    @endif
                </a>
            </div>
            @else
            <div class="flex-1 text-left flex items-center gap-4" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-solid fa-bars text-lg {{ $mobileMenu === 'enable' ? '' : 'hidden!' }}" onclick="document.getElementById('mobileMenu').classList.remove('-translate-x-full'); document.getElementById('mobileMenu').classList.add('arzavo-border-right')"></i>
            </div>
            <a href="{{ route('home') }}" class="shrink-0">
                @if ($customizes['logo'])
                <img src="{{ asset($customizes['logo'] ?? '') }}" alt="Logo" class="w-auto logo" style="height: {{ $logoSize }}px;">
                @else
                <h2 class="text-xl font-semibold" style="color: {{ $colors->subheading ?? '#000000' }};">{{ app('currentTenant')->name }}</h2>
                @endif
            </a>
            @endif
            @if (!Auth::guard('tenant')->check())
            <div class="menu flex items-center justify-end gap-4 w-fit flex-1" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-{{ $iconStyle }} fa-file-lines text-lg {{ $admissionIcon === 'enable' ? '' : 'hidden!' }}" title="Admission Form"></i>
                <a href="{{ route('tenant.login.form') }}"><i class="fa-{{ $iconStyle }} fa-user text-lg"></i></a>
            </div>
            @else
            <div class="menu flex items-center justify-end gap-4 w-fit flex-1" style="color: {{ $colors->subheading ?? '#000000' }};">
                <i class="fa-{{ $iconStyle }} fa-user text-lg"></i>
            </div>
            @endif
        </div>
    </div>
</nav>
<div data-section-id="{{ $section->id }}" data-name="{{ $section->name }}" style="--arzavo-border-color: {{ $colors->border ?? '#d4d4d4d' }}; background: {{ $colors->background ?? '#ffffff' }};" id="mobileMenu" class="w-3/4 h-dvh md:hidden z-21 -translate-x-full fixed transform transition-all duration-300 top-0 left-0">
    <div class="logo-and-menu flex items-center arzavo-border-bottom justify-between px-4 {{ $navHeight === 'compact' ? 'py-1' : ($navHeight === 'standard' ? 'py-2' : 'py-4') }}" style="color: {{ $colors->subheading ?? '#000000' }};">
        <a href="{{ route('home') }}">
            @if ($customizes['logo'])
            <img src="{{ asset($customizes['logo'] ?? '') }}" alt="Logo" class="w-auto logo" style="height: {{ $logoSize }}px;">
            @else
            <h2 class="text-xl font-semibold" style="color: {{ $colors->subheading ?? '#000000' }};">{{ app('currentTenant')->name }}</h2>
            @endif
        </a>
        <i class="fa-solid fa-xmark text-xl" onclick="document.getElementById('mobileMenu').classList.add('-translate-x-full'); document.getElementById('mobileMenu').classList.remove('arzavo-border-right')"></i>
    </div>
    <div class="links flex flex-col w-full items-center" style="color: {{ $colors->subheading ?? '#000000' }};">
        @foreach ($mobileLinks as $link)
        <a href="/{{ $link }}" class="w-full arzavo-border-bottom py-2 px-4 relative font-{{ $fontWeight }} {{ $textTransform }} {{ $fontSize === 'medium' ? 'text-lg' : ($fontSize === 'large' ? 'text-xl' : 'text-md') }}">{{ $link }} <i class="fa-solid fa-chevron-right text-xs absolute right-4 top-3.5"></i></a>
        @endforeach
    </div>
    <div class="social-icons {{ $socialIcons === 'enable' ? 'flex' : 'hidden!' }} flex-row justify-between p-4 absolute bottom-0 left-0 w-full arzavo-border-top" style="color: {{ $colors->subheading ?? '#000000' }};">
        <i class="fa-brands fa-facebook"></i>
        <i class="fa-brands fa-whatsapp"></i>
        <i class="fa-brands fa-instagram"></i>
        <i class="fa-brands fa-twitter"></i>
        <i class="fa-brands fa-linkedin"></i>
    </div>
</div>