<nav class="fixed top-0 w-full z-50 transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50" :class="scrolled ? 'bg-invert' : 'bg-transparent'">
    <div class="container py-3">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <img src="{{ asset('images/logo/arzavo-white.png') }}" alt="ARZAVO" class="h-12">
            </a>
            <div class="flex items-center space-x-4">
                @if(!Auth::check())
                <x-button :url="route('login.form')" variant="secondary" class="bg-white!">Login</x-button>
                <x-button :url="route('register.form')" class="hidden md:flex">Register Now</x-button>
                @else
                <x-button :url="route('tenants.index')" variant="icon" class="text-invert text-2xl md:text-xl" icon="bars-progress"></x-button>
                @endif
            </div>
        </div>
    </div>
</nav>