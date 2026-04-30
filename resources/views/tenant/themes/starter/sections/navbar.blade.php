@php
    $logoUrl = $section->blocks()->only('header_logo');
    $navHeight = $section->nav_height ?? 72;
@endphp

<nav {!! $section->attributes() !!}
    class="{{ $section->visibility }}"
    style="border-bottom: {{ $section->divider ?? 1 }}px solid var(--arz-border);">

    <div class="nav-inner {{ $section->container }}">

        {{-- Logo --}}
        <div class="nav-logo">
            {!! $logoUrl !!}
        </div>

        {{-- Desktop Menu --}}
        <div class="nav-menu-desktop">
            {!! $section->blocks()->only('header_menu') !!}
        </div>

        {{-- CTA Buttons --}}
        <div class="nav-actions">
            {!! $section->blocks()->only('button') !!}
        </div>

        {{-- Mobile Toggle --}}
        <button class="nav-toggle" onclick="toggleStarterMenu(this)" aria-label="Menu">
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
        </button>
    </div>

    {{-- Mobile Drawer --}}
    <div class="nav-mobile-drawer" data-mobile-nav style="display:none;">
        <div class="nav-mobile-inner {{ $section->container }}">
            {!! $section->blocks()->only('header_menu') !!}
            <div class="nav-mobile-actions">
                {!! $section->blocks()->only('button') !!}
            </div>
        </div>
    </div>
</nav>

<style>
    .arz-{{ $section->id }} {
        position: sticky;
        top: 0;
        z-index: 999;
        background: var(--arz-bg);
        backdrop-filter: blur(12px);
    }
    .arz-{{ $section->id }} .nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 32px;
        height: {{ $navHeight }}px;
    }
    .arz-{{ $section->id }} .nav-logo {
        flex-shrink: 0;
    }
    .arz-{{ $section->id }} .nav-menu-desktop {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        justify-content: center;
    }
    .arz-{{ $section->id }} .nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .arz-{{ $section->id }} .nav-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
    }
    .arz-{{ $section->id }} .toggle-bar {
        width: 22px;
        height: 2px;
        background: var(--arz-heading);
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .arz-{{ $section->id }} .nav-toggle.active .toggle-bar:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    .arz-{{ $section->id }} .nav-toggle.active .toggle-bar:nth-child(2) {
        opacity: 0;
    }
    .arz-{{ $section->id }} .nav-toggle.active .toggle-bar:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }
    .arz-{{ $section->id }} .nav-mobile-drawer {
        border-top: 1px solid var(--arz-border);
        background: var(--arz-bg);
    }
    .arz-{{ $section->id }} .nav-mobile-inner {
        padding: 24px 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .arz-{{ $section->id }} .nav-mobile-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-top: 16px;
        border-top: 1px solid var(--arz-border);
    }

    @media (max-width: 767px) {
        .arz-{{ $section->id }} .nav-menu-desktop { display: none; }
        .arz-{{ $section->id }} .nav-actions { display: none; }
        .arz-{{ $section->id }} .nav-toggle { display: flex; }
    }
</style>

<script>
    if (typeof toggleStarterMenu !== 'function') {
        window.toggleStarterMenu = function(btn) {
            btn.classList.toggle('active');
            var drawer = btn.closest('nav').querySelector('[data-mobile-nav]');
            if (drawer.style.display === 'none') {
                drawer.style.display = 'block';
            } else {
                drawer.style.display = 'none';
            }
        };
    }
</script>