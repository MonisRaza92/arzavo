<nav class="navbar sticky top-0 bg-primary w-full border-bottom z-50 backdrop-blur-sm bg-opacity-95">
    <div class="container flex justify-between items-center py-4">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo" class="logo">
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">Home</a>
            <a href="{{ route('about') }}" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">About</a>
            <a href="{{ route('home') }}#features" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">Features</a>
            <a href="{{ route('home') }}#pricing" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">Pricing</a>
            <a href="{{ route('home') }}#contact" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">Contact</a>
            <a href="{{ route('documentation') }}" class="text-primary bg-hover-secondary px-3 py-2 font-medium transition-all duration-300">Documentation</a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center gap-2 sm:gap-3">
            @if (!Auth::check())
            <a href="{{ route('login.form') }}" class="text-primary border-primary hover-primary px-4 py-2 border-rounded font-medium transition-all duration-300">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>Login
            </a>
            <a href="{{ route('register.form') }}" class="bg-accent border-accent hidden md:block text-invert px-6 py-2 border-rounded font-semibold hover-invert transition-all duration-300 shadow-lg">
                <i class="fa-solid fa-user-plus mr-2"></i>Get Started
            </a>
            @else
            <a href="{{ route('tenants.index') }}" class="text-primary text-xl font-medium transition-all duration-300">
                <i class="fa-regular fa-user mr-2"></i>
            </a>
            @endif
            <!-- Mobile Menu Button -->
            <button class="md:hidden text-primary text-2xl hover-primary border-rounded transition-all duration-300" onclick="toggleMobileMenu()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobileMenu" class="md:hidden bg-primary border-top hidden">
        <div class="container py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">Home</a>
            <a href="{{ route('about') }}" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">About</a>
            <a href="{{ route('home') }}#features" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">Features</a>
            <a href="{{ route('home') }}#pricing" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">Pricing</a>
            <a href="{{ route('home') }}#contact" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">Contact</a>
            <a href="{{ route('documentation') }}" class="block text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">Documentation</a>

            @if (!Auth::check())
            <div class="pt-3 border-top space-y-2">
                <a href="{{ route('login.form') }}" class="block border-primary text-center text-primary hover-primary px-3 py-2 border-rounded font-medium transition-all duration-300">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>Login
                </a>
                <a href="{{ route('register.form') }}" class="block border-accent text-center bg-accent text-invert px-3 py-2 border-rounded font-semibold hover-invert transition-all duration-300">
                    <i class="fa-solid fa-user-plus mr-2"></i>Get Started
                </a>
            </div>
            @endif
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>