{{-- Website Navbar (TENANTS_DESIGN_SYSTEM Compliant) --}}
@php
    $links = [
        ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Features', 'route' => 'features'],
        ['name' => 'Pricing', 'route' => 'pricing'],
        ['name' => 'Docs', 'route' => 'documentation.index'],
        ['name' => 'About', 'route' => 'about'],
        ['name' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header class="fixed top-0 left-0 right-0 z-50 bg-primary transition-colors"
    x-data="{ mobileOpen: false }">
    <div class="container">
        <div class="flex items-center justify-between h-18">

            <!-- Logo -->
            <a href="{{ route('tenants.index') }}" class="shrink-0 flex items-center gap-2 ">
                <img class="theme-light-logo h-10 w-auto object-contain"
                    src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo">
                <img class="theme-dark-logo h-10 w-auto object-contain"
                    src="{{ media('images/logo/arzavo-white.png') }}" alt="Logo">
            </a>

            {{-- Desktop Nav Links --}}
            <nav class="hidden lg:flex items-center gap-8 rounded-full">
                @foreach ($links as $link)
                    @php $isActive = request()->routeIs($link['route']) || (request()->is(ltrim(route($link['route'], [], false), '/') . '*')); @endphp
                    <a href="{{ route($link['route']) }}"
                        class="rounded-full text-base transition {{ $isActive ? 'text-brand ' : '  text-theme-text-muted hover:text-theme-text ' }}">
                        {{ $link['name'] }}
                    </a>
                @endforeach
            </nav>
            <a href="{{ route('arzaq.redirect') }}"
                class="px-5 py-2.5 bg-brand hidden text-primary rounded-full font-normal text-sm transition hover:opacity-90 md:inline-flex items-center gap-2">
                <span>Get Started</span>
                <i class="fa-solid fa-arrow-right -rotate-45"></i>
            </a>

            {{-- Mobile Menu Trigger --}}
            <div class="lg:hidden flex items-center gap-2">
                <button type="button" @click="mobileOpen = !mobileOpen"
                    class="w-12 h-12 rounded-full bg-primary text-brand flex items-center justify-center text-xs transition"
                    aria-label="Toggle navigation">
                    <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Dropdown Drawer --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100" @click.outside="mobileOpen = false"
        class="lg:hidden bg-primary px-4 pt-2 pb-6 space-y-3" style="display: none;">
        <div class="space-y-1">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="block px-4 py-2.5 rounded-full text-xs text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal transition">
                    {{ $link['name'] }}
                </a>
            @endforeach
        </div>
        <div class="pt-2 flex flex-col gap-2">
            @if (auth('web')->check())
                <a href="{{ route('tenants.index') }}"
                    class="w-full text-center px-4 py-3 bg-brand text-primary rounded-full font-normal text-xs">
                    Dashboard
                </a>
            @else
                <a href="{{ route('arzaq.redirect') }}"
                    class="w-full text-center px-4 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle">
                    Log In
                </a>
                <a href="{{ route('arzaq.redirect') }}"
                    class="w-full text-center px-4 py-3 bg-brand text-primary rounded-full font-normal text-xs">
                    Get Started Free
                </a>
            @endif
        </div>
    </div>
</header>