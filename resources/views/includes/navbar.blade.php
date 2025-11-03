<nav class="w-full flex items-center z-50 transition-all duration-300 
    {{ $customizes['navbar_type'] === 'fixed' ? 'fixed top-0 left-0' : 'relative' }} {{ $customizes['navbar_type'] === 'floating' ? 'border-primary' : 'border-bottom' }}
    bg-primary {{ $customizes['navbar_size'] === 'compact' ? 'py-1' : 'py-2' }}">
    <div class="container mx-auto flex items-center gap-6 justify-between {{ $customizes['navbar_type'] === 'floating' ? 'fixed py-2 bg-primary top-4 w-full left-1/2 transform -translate-x-1/2 border-rounded border-primary' : '' }}">
        {{-- Logo --}}
        <div class="flex items-center {{ $customizes['logo_position'] === 'center' ? 'justify-center flex-1' : '' }}">
            <a href="{{ route('home') }}">
                <img src="{{ asset($customizes['logo'] ?? 'images/ARZAQ-dark-logo.png') }}" alt="Logo"
                    class="w-auto logo">
            </a>
        </div>

        {{-- Nav Links --}}
        <div class="hidden lg:flex flex-1
            {{ $customizes['navlinks_position'] === 'center' ? 'justify-center' : '' }}
            {{ $customizes['navlinks_position'] === 'right' ? 'justify-end' : 'justify-start' }}
            items-center gap-4">
            <ul class="flex items-center gap-4">
                @foreach (['Home'=>'/', 'Courses'=>'/courses', 'Notes'=>'/test-series', 'Books'=>'/books', 'Blogs'=>'/blogs'] as $name => $link)
                <li>
                    <a href="{{ url($link) }}"
                        class="px-4 py-2 rounded-md transition-all
                            {{ $customizes['nav_link_style'] === 'bold' ? 'font-semibold bg-secondary hover:bg-invert hover:text-invert' : 'font-normal border-b-2 border-transparent hover:border-primary hover:text-primary' }}
                            {{ request()->is($link) ? 'bg-invert text-invert' : '' }}">
                        {{ strtoupper($name) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Auth + Mobile Button --}}
        <div class="flex items-center gap-2">
            @if (!Auth::check())
            <a href="{{ route('login-form') }}" class="hidden md:block px-3 py-1 bg-invert text-invert rounded-md font-bold">Login</a>
            <a href="{{ route('register-form') }}" class="hidden md:block px-3 py-1 border rounded-md font-bold">Register</a>
            @else
            <x-theme-toggle-btn />
            <div class="relative">
                <button onclick="document.getElementById('Dropdown').classList.toggle('hidden');" class="flex items-center gap-2">
                    @if(Auth::user()->profile_picture)
                    <img src="{{ asset(Auth::user()->profile_picture) }}" class="w-10 h-10 rounded-md object-cover">
                    @else
                    <div class="w-10 h-10 rounded-md bg-invert flex items-center justify-center text-invert font-bold">
                        {{ strtoupper(substr(Auth::user()->fname,0,1)) }}
                    </div>
                    @endif
                </button>
                <div id="Dropdown" class="hidden absolute right-0 mt-2 w-40 bg-secondary p-2 rounded-md shadow-lg">
                    <a href="{{ route('profile') }}" class="block p-2 hover:bg-gray-100">Profile</a>
                    <a href="{{ route('logout') }}" class="block p-2 text-red-500 hover:bg-gray-100"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
            @endif

            {{-- Mobile Menu Button --}}
            <button class="lg:hidden text-3xl" onclick="document.getElementById('mobileMenu').classList.toggle('-translate-x-full')">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div id="mobileMenu" class="fixed top-0 left-0 w-3/4 h-full bg-secondary -translate-x-full transition-transform duration-300 z-50 shadow-md">
    <div class="p-4 flex items-center justify-between">
        <a href="{{ route('home') }}">
            <img src="{{ asset($customizes['logo'] ?? 'images/ARZAQ-dark-logo.png') }}" alt="Logo"
                class="w-auto" style="height: {{ $customizes['logo_size'] ?? 50 }}px;">
        </a>
        <button onclick="document.getElementById('mobileMenu').classList.add('-translate-x-full')" class="text-3xl">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <ul class="flex flex-col gap-2 p-4">
        @foreach (['Home'=>'/', 'Courses'=>'/courses', 'Notes'=>'/test-series', 'Books'=>'/books', 'Blogs'=>'/blogs'] as $name => $link)
        <li>
            <a href="{{ url($link) }}" class="block px-4 py-2 rounded-md hover:bg-primary hover:text-invert">
                {{ strtoupper($name) }}
            </a>
        </li>
        @endforeach
    </ul>
</div>