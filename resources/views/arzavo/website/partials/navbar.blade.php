{{-- Navbar --}}
@php $isDocs = request()->is('documentation*'); @endphp

<nav class="fixed top-0 w-full z-50 bg-primary border-b transition-all duration-300">
    <div class="container">
        <div class="flex justify-between items-center h-18">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo/arzavo-dark.png') }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo/arzavo-white.png') }}'"
                     alt="Arzavo" class="h-10">
            </a>

            {{-- NAV LINKS (DESKTOP) --}}
            <div class="hidden lg:flex items-center space-x-8">
                @php
                $links = [
                    ['name' => 'Home',     'route' => 'home'],
                    ['name' => 'Features', 'route' => 'features'],
                    ['name' => 'Pricing',  'route' => 'pricing'],
                    ['name' => 'Docs',     'route' => 'documentation.index'],
                    ['name' => 'About',    'route' => 'about'],
                    ['name' => 'Contact',  'route' => 'contact'],
                ];
                @endphp
                @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="relative text-dark hover:text-dark/80 transition-colors py-1 group">
                    {{ $link['name'] }}
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent rounded-full transition-all duration-300 group-hover:w-full"></span>
                </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="hidden lg:flex items-center gap-2">
                <x-button url="{{ route('login.form') }}">Log In <i class="fa-solid fa-right-to-bracket"></i></x-button>
                <x-button url="{{ route('register.form') }}" variant="accent">Get Started <i class="fa-solid fa-arrow-right -rotate-45"></i></x-button>
            </div>

            {{-- MOBILE BUTTON --}}
            <button @click="open = !open" class="lg:hidden p-2 text-dark hover:text-dark/80 transition-colors">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        class="lg:hidden bg-white border-t border-gray-100"
        style="display:none;">
        <div class="container py-4 space-y-1">
            @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-accent hover:bg-red-50 rounded-lg transition-colors">
                {{ $link['name'] }}
            </a>
            @endforeach
            <div class="pt-3 border-t border-gray-100 space-y-2">
                <a href="{{ route('login.form') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-colors">Log in</a>
                <a href="{{ route('register.form') }}" class="block px-3 py-2 bg-accent text-white text-sm font-semibold rounded-lg text-center hover:opacity-90 transition-opacity">Get Started Free</a>
            </div>
        </div>
    </div>
</nav>

<style>
:root {
    --color-accent: #920000;
    --color-accent-secondary: #c58400;
}
.bg-accent { background-color: #920000 !important; }
.text-accent { color: #920000 !important; }
.hover\:text-accent:hover { color: #920000 !important; }
.hover\:bg-red-50:hover { background-color: #fff5f5; }
</style>