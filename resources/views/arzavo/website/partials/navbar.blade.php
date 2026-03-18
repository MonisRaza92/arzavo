<!-- Ultra-Modern Glassmorphism Navbar -->
@php
$isDocs = request()->is('documentation*');
@endphp
<nav class="fixed top-0 w-full z-100 transition-all duration-500 border-b border-transparent"
    x-data="{ scrolled: false, open: false }"
    @scroll.window="scrolled = window.scrollY > 50"
    :class="scrolled ? 'shadow-lg py-4 bg-slate-900/80 backdrop-blur-sm border-b border-white/10' : '{{ $isDocs ? 'bg-slate-900/80 backdrop-blur-sm border-b border-white/10' : 'bg-transparent' }} py-4'">

    <div class="container mx-auto px-4 md:px-6 relative">
        <div class="flex justify-between items-center">

            <!-- LOGO -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group relative z-10">
                <img src="{{ asset('images/logo/arzavo-white.png') }}" alt="ARZAVO" class="h-10 transition-all duration-500 group-hover:scale-105 relative z-10 drop-shadow-lg">
            </a>

            <!-- NAV LINKS (DESKTOP) -->
            <div class="hidden lg:flex items-center space-x-8">
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

                @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                    class="relative font-medium text-[15px] tracking-wide transition-colors duration-300 group"
                    :class="scrolled ? 'text-gray-200 hover:text-white' : 'text-gray-200 hover:text-white'">
                    {{ $link['name'] }}
                    <!-- Animated Underline -->
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-linear-to-r from-accent to-accent-secondary rounded-full transition-all duration-300 ease-out group-hover:w-full"></span>
                </a>
                @endforeach
            </div>

            <!-- RIGHT SIDE CTA (DESKTOP) -->
            <div class="hidden lg:flex items-center space-x-4">
                <a href="{{ route('login.form') }}"
                    class="font-medium text-[15px] transition duration-300 hover:-translate-y-0.5"
                    :class="scrolled ? 'text-gray-200 hover:text-white' : 'text-gray-200 hover:text-white'">
                    Log in
                </a>

                <a href="{{ route('register.form') }}"
                    class="relative group px-6 py-2.5 rounded-full overflow-hidden font-medium text-sm text-white transition-all duration-300 hover:scale-105 hover:shadow-[0_0_20px_rgba(var(--accent-rgb),0.5)]">
                    <!-- Button Background -->
                    <span class="absolute inset-0 w-full h-full bg-linear-to-br from-gray-800 to-gray-900"></span>
                    <!-- Shimmer Effect -->
                    <span class="absolute bottom-0 left-0 w-full h-full bg-linear-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></span>
                    <span class="relative flex items-center gap-2">
                        Get Started <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                    </span>
                </a>
            </div>

            <!-- MOBILE MENU BUTTON -->
            <button @click="open = !open"
                class="lg:hidden relative z-50 p-2 rounded-full focus:outline-none transition-colors duration-300"
                :class="scrolled ? 'text-primary hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                <div class="w-6 h-5 flex flex-col justify-between items-end relative">
                    <span class="w-full h-0.5 rounded-full transition-all duration-300 origin-left"
                        :class="[scrolled ? 'bg-primary' : 'bg-white', open ? 'rotate-45 translate-x-px -translate-y-0.5' : '']"></span>
                    <span class="w-3/4 h-0.5 rounded-full transition-all duration-300"
                        :class="[scrolled ? 'bg-primary' : 'bg-white', open ? 'opacity-0' : '']"></span>
                    <span class="w-full h-0.5 rounded-full transition-all duration-300 origin-left"
                        :class="[scrolled ? 'bg-primary' : 'bg-white', open ? '-rotate-45 translate-x-px translate-y-0.5' : '']"></span>
                </div>
            </button>

        </div>
    </div>

    <!-- MOBILE MENU FULLSCREEN OVERLAY -->
    <div x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden absolute top-full left-0 w-full bg-white shadow-2xl border-t border-gray-100 overflow-hidden rounded-b-3xl"
        style="display: none;">

        <div class="px-6 py-8 space-y-6">
            <div class="flex flex-col space-y-4">
                @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" class="text-xl font-medium text-secondary hover:text-accent transition-colors border-b border-gray-50 pb-4">
                    {{ $link['name'] }}
                </a>
                @endforeach
            </div>

            <div class="flex flex-col space-y-4 pt-4">
                <a href="{{ route('login.form') }}" class="text-center font-medium text-secondary py-3 hover:text-primary transition-colors">
                    Log in to your account
                </a>

                <a href="{{ route('register.form') }}"
                    class="w-full py-4 rounded-xl bg-linear-to-r from-accent to-accent-secondary text-white text-center font-semibold text-lg shadow-lg shadow-accent/20 active:scale-95 transition-transform">
                    Get Started Free
                </a>
            </div>
        </div>
    </div>
</nav>