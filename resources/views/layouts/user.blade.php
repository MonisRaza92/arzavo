<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="bg-secondary text-primary antialiased">

    {{-- TOP NAVBAR --}}
    @include('tenant.user.includes.navbar')

    {{-- SIDEBAR NAV (OFF-CANVAS ON MOBILE, FIXED ON DESKTOP) --}}
    @include('tenant.user.includes.sidebar')

    {{-- ALERTS --}}
    <x-alert />
    <x-content-model />

    {{-- MAIN CONTENT AREA (MOBILE FIRST SPACING & BOTTOM MARGIN FOR MOBILE BAR) --}}
    <main class="main-admin-content relative lg:ml-[260px]! p-3 sm:p-5 mb-20 lg:mb-6">
        @yield('content')
    </main>

    {{-- MOBILE BOTTOM QUICK ACCESS NAV BAR (FOR SMARTPHONES) --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-primary border-top shadow-lg px-2 py-1.5 flex items-center justify-around">
        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('user.dashboard') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-gauge text-base mb-0.5"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('user.orders') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('user.orders') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-bag-shopping text-base mb-0.5"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('user.downloads') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('user.downloads') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-book-open text-base mb-0.5"></i>
            <span>Downloads</span>
        </a>
        <a href="{{ route('user.invoices') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('user.invoices') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-file-invoice text-base mb-0.5"></i>
            <span>Invoices</span>
        </a>
        <a href="{{ route('user.profile') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('user.profile') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-user-gear text-base mb-0.5"></i>
            <span>Profile</span>
        </a>
    </div>

</body>

</html>
