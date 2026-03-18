<nav class="fixed top-0 w-full z-50 transition-all duration-300" x-data="{ scrolled: false, open: false }"
    @scroll.window="scrolled = window.scrollY > 50" :class="scrolled ? 'bg-white shadow-md' : 'bg-transparent'">

    <div class="container py-3">

        <div class="flex justify-between items-center">

            <!-- LOGO -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo/arzavo-white.png') }}" alt="ARZAVO" class="h-10 transition-all">
            </a>

            <!-- NAV LINKS (DESKTOP) -->
            <div class="hidden md:flex items-center space-x-6 text-sm font-medium">

                <a href="{{ route('home') }}" class="hover:text-primary transition">
                    Home
                </a>

                <a href="{{ route('features') }}" class="hover:text-primary transition">
                    Features
                </a>

                <a href="{{ route('pricing') }}" class="hover:text-primary transition">
                    Pricing
                </a>

                <a href="{{ route('documentation') }}" class="hover:text-primary transition">
                    Docs
                </a>

                <a href="{{ route('about') }}" class="hover:text-primary transition">
                    About
                </a>

                <a href="{{ route('contact') }}" class="hover:text-primary transition">
                    Contact
                </a>

            </div>

            <!-- RIGHT SIDE -->
            <div class="hidden md:flex items-center space-x-3">

                <a href="{{ route('login.form') }}"
                    class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 transition">
                    Login
                </a>

                <a href="{{ route('register.form') }}"
                    class="px-5 py-2 text-sm rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow hover:opacity-90 transition">
                    Get Started 🚀
                </a>

            </div>

            <!-- MOBILE MENU BUTTON -->
            <button @click="open = !open" class="md:hidden text-xl">
                ☰
            </button>

        </div>

        <!-- MOBILE MENU -->
        <div x-show="open" x-transition class="md:hidden mt-4 bg-white rounded-xl shadow p-4 space-y-3 text-sm">

            <a href="{{ route('home') }}" class="block">Home</a>
            <a href="{{ route('features') }}" class="block">Features</a>
            <a href="{{ route('pricing') }}" class="block">Pricing</a>
            <a href="{{ route('documentation') }}" class="block">Docs</a>
            <a href="{{ route('about') }}" class="block">About</a>
            <a href="{{ route('contact') }}" class="block">Contact</a>

            <hr>

            <a href="{{ route('login.form') }}" class="block">Login</a>
            <a href="{{ route('register.form') }}" class="block bg-primary text-white text-center py-2 rounded-lg">
                Get Started
            </a>

        </div>

    </div>
</nav>