@php
    $user = $user ?? auth('web')->user();
@endphp
<div
    class="admin-navbar w-full sticky top-0 right-0 z-99 flex gap-8 items-center justify-between px-4 py-3 bg-primary border-bottom">
    <a href="{{ route('home') }}" class="shrink-0">
        <img id="logo" src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo" class="logo">
    </a>
    <div class="search-bar relative hidden md:block text-sm grow">
        <input type="text" placeholder="Search..."
            class="search-input w-full border-rounded px-3 py-2.25 bg-primary border-primary">
        <button
            class="search-button absolute right-0 top-0 mt-1.5 mr-1.5 bg-secondary text-xs border-primary py-1.25 px-2 border-rounded text-tertiary">Ctrl
            + k</button>
    </div>
    <div class="flex items-center gap-4">

        <!-- Help -->
        <button title="Help" class="text-2xl text-secondary">
            <i class="fa-regular fa-circle-question"></i>
        </button>

        <!-- Notifications -->
        <button class="relative text-2xl text-secondary">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute -top-1 -right-1 text-[10px] bg-accent text-invert px-1 border-rounded">
                2
            </span>
        </button>

        <!-- USER Profile Dropdown -->
        <!-- 1. Arzaq Ecosystem Mount Container (Renders Profile, App Grid & Wallet) -->
        <div id="arzaq-ecosystem-bar"
            data-auth-url="{{ config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com')) }}"
            data-logout-url="/logout"
            data-csrf="{{ csrf_token() }}"
            data-user-name="{{ Auth::user()?->name }}"
            data-user-email="{{ Auth::user()?->email }}"
            data-user-avatar="{{ Auth::user()?->avatar }}"
            data-device-id="{{ request()->cookie('arzaq_device_id') }}">
        </div>

        <!-- 2. Universal Ecosystem SDK Script (Auto-initializes UI & Session Sync) -->
        <script
            src="{{ config('services.arzaq.auth_url', env('ARZAQ_AUTH_URL', 'https://auth.arzaqinsights.com')) }}/sdk/nav.js"
            defer></script>

    </div>
</div>