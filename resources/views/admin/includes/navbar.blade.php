<div class="admin-navbar w-full fixed top-0 right-0 z-1000 flex items-center justify-between px-4 py-3 bg-primary border-bottom">
    <a href="{{ route('home') }}">
        <img id="logo" src="{{ asset($customizes['logo'] ?? 'images/logo/arzavo-dark.png') }}" alt="Logo" class="logo">
    </a>
    <div class="search-bar relative hidden md:block text-sm lg:w-1/2">
        <input type="text" placeholder="Search..." class="search-input w-full border-rounded px-3 py-2 input-focus bg-primary border-primary">
        <button class="search-button absolute right-0 top-0 mt-1.5 mr-1.5 bg-secondary text-xs border-primary py-1 px-2 border-rounded text-tertiary">Ctrl + k</button>
    </div>
    <div class="admin-user-actions flex items-center gap-4">
        <div class="relative flex items-center gap-2">
            <a href="{{ route('admin.tenants.index') }}" title="Manage Tenants" class="bg-invert text-invert logo aspect-square border-rounded flex items-center justify-center"><i class="fas fa-building-columns text-base"></i></a>
            <button title="Notifications" class="bg-invert hidden lg:block text-invert logo aspect-square border-rounded"><i class="fas fa-bell text-base"></i></button>
            <button class="flex items-center gap-2 btn uppercase font-bold border-rounded" onclick="document.getElementById('adminDropdown').classList.toggle('hidden');">
                @if (Auth::user()->profile_picture) <img src="{{ asset(Auth::user()->profile_picture) }}" class="border-rounded logo aspect-square object-cover" alt="{{ Auth::user()->fname }}"> @else <h2 class="font-bold border-rounded text-xl flex justify-center items-center logo aspect-square bg-invert text-invert">{{ strtoupper(substr(Auth::user()->fname, 0, 1)) }}</h2> @endif
            </button>
            <div id="adminDropdown" class="absolute right-0 top-2 mt-2 w-48 p-2 rounded-md hidden z-10 bg-primary">
                <form id="logout-form" method="POST" action="{{ route('tenant.logout') }}" class="hidden">
                    @csrf
                </form>
            </div>
            <button id="adminSidebarToggle" class="lg:hidden bg-invert text-invert w-11 aspect-square border-rounded flex items-center justify-center text-2xl" onclick="document.getElementById('adminMobileMenu').classList.toggle('-translate-x-full')"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</div>