<nav class="navbar sticky top-0 w-full z-50 transition-all duration-300 border-b border-white/10 bg-white/70 backdrop-blur-xl">
    <div class="container flex justify-between items-center py-5">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
            <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="ARZAVO" class="h-10">
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-10">
            <a href="{{ route('home') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ Request::is('/') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-colors">Home</a>
            <a href="{{ route('features') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ Request::is('features') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-colors">Features</a>
            <a href="{{ route('home') }}#solutions" class="text-xs font-black uppercase tracking-[0.2em] text-primary hover:text-accent transition-colors">Solutions</a>
            <a href="{{ route('pricing') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ Request::is('pricing') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-colors">Pricing</a>
            <a href="{{ route('contact') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ Request::is('contact') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-colors">Contact</a>
        </div>

        <!-- Auth Buttons -->
        <div class="flex items-center gap-6">
            @if (!Auth::check())
            <a href="{{ route('login.form') }}" class="hidden sm:block text-xs font-black uppercase tracking-[0.2em] text-primary hover:text-accent transition-all">
                Login
            </a>
            <a href="{{ route('register.form') }}" class="bg-invert text-invert px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-widest hover-lift shadow-2xl transition-all">
                Get Started
            </a>
            @else
            <a href="{{ route('tenants.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-tertiary border-rounded-full text-xs font-black uppercase tracking-widest transition-all hover:bg-white border-primary shadow-sm">
                <i class="fa-regular fa-user"></i>
                <span>Dashboard</span>
            </a>
            @endif
            
            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-primary p-2 hover:bg-tertiary rounded-full transition-all" onclick="toggleMobileMenu()">
                <i class="fa-solid fa-bars-staggered text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobileMenu" class="lg:hidden bg-white/95 backdrop-blur-3xl border-t border-primary hidden animate-fade-in">
        <div class="container py-10 flex flex-col gap-6">
            <a href="{{ route('home') }}" class="text-xl font-black outfit-font p-2 {{ Request::is('/') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-all">Home</a>
            <a href="{{ route('features') }}" class="text-xl font-black outfit-font p-2 {{ Request::is('features') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-all">Features</a>
            <a href="{{ route('home') }}#solutions" class="text-xl font-black outfit-font p-2 hover:text-accent transition-all">Solutions</a>
            <a href="{{ route('pricing') }}" class="text-xl font-black outfit-font p-2 {{ Request::is('pricing') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-all">Pricing</a>
            <a href="{{ route('contact') }}" class="text-xl font-black outfit-font p-2 {{ Request::is('contact') ? 'text-accent' : 'text-primary' }} hover:text-accent transition-all">Contact</a>
            
            @if (!Auth::check())
            <div class="pt-8 mt-4 border-t border-primary flex flex-col gap-4">
                <a href="{{ route('login.form') }}" class="text-center py-4 font-black uppercase tracking-widest text-xs border-primary border-rounded-lg">Login</a>
                <a href="{{ route('register.form') }}" class="text-center py-4 font-black uppercase tracking-widest text-xs bg-accent text-invert border-rounded-lg shadow-xl">Get Started</a>
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
