@php
    $user = Auth::guard('web')->user();
@endphp
<header class="w-full sticky top-0 z-[99] flex gap-3 md:gap-6 items-center justify-between py-4 mb-4 bg-secondary">

    <!-- Mobile Menu Toggle -->
    <button type="button"
        onclick="document.getElementById('arzavoTenantsSidebar')?.classList.toggle('-translate-x-full')"
        class="lg:hidden text-theme-text p-2.5 rounded-full hover:bg-subtle transition shrink-0 flex items-center justify-center text-base"
        title="Toggle Menu">
        <i class="fa-solid fa-bars"></i>
    </button>



    <!-- Search Bar (Connected to Live Filtering) -->
    <div class="relative grow max-w-2xl">
        <i class="fa-solid fa-magnifying-glass absolute left-4.5 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs pointer-events-none"></i>
        <input type="text"
            x-model="search"
            @keydown.enter="if (!window.location.pathname.endsWith('/tenants')) { window.location.href = '{{ route('tenants.index') }}?search=' + encodeURIComponent(search); }"
            placeholder="Search institutes, schools, coaching centers..."
            class="w-full bg-primary text-theme-text placeholder:text-theme-text-muted pl-11 pr-10 py-3.5 text-xs rounded-full focus:outline-none focus:bg-subtle transition">
        <button x-show="search && search.length > 0"
            @click="search = ''"
            type="button"
            title="Clear search"
            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted hover:text-theme-text text-xs p-1 transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Actions & Profile -->
    <div class="flex items-center gap-4">
        <!-- Help -->
        <button title="Help" class="text-theme-text hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xl transition hidden md:flex">
            <i class="fa-regular fa-circle-question"></i>
        </button>

        <!-- Notifications -->
        <button title="Notifications" class="relative text-theme-text hover:text-theme-text hover:bg-subtle flex items-center justify-center text-xl transition">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute -top-0.5 -right-0.5 text-[9px] bg-danger text-white w-4 h-4 rounded-full flex items-center justify-center font-normal">
                1
            </span>
        </button>

        <!-- USER Profile Dropdown (Arzaq Ecosystem SDK) -->
        <div id="arzaq-ecosystem-bar"
            data-auth-url="{{ config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com')) }}"
            data-logout-url="{{ route('logout') }}" data-csrf="{{ csrf_token() }}"
            data-user-name="{{ $user?->name }}" data-user-email="{{ $user?->email }}"
            data-user-avatar="{{ $user?->avatar }}">
        </div>

        <script
            src="{{ rtrim(config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com')), '/') }}/sdk/nav.js"
            defer></script>
    </div>
</header>