<!DOCTYPE html>
<html lang="en">

<head>
    <x-header />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="bg-secondary text-primary antialiased">

    {{-- TOP NAVBAR --}}
    @include('tenant.student.includes.navbar')

    {{-- SIDEBAR NAV (OFF-CANVAS ON MOBILE, FIXED ON DESKTOP) --}}
    @include('tenant.student.includes.sidebar')

    {{-- ALERTS --}}
    <x-alert />
    <x-content-model />

    {{-- MAIN CONTENT AREA (MOBILE FIRST SPACING & BOTTOM MARGIN FOR MOBILE BAR) --}}
    <main class="main-admin-content relative lg:ml-[260px]! p-3 sm:p-5 mb-20 lg:mb-6">
        @yield('content')
    </main>

    {{-- MOBILE BOTTOM QUICK ACCESS NAV BAR (FOR SMARTPHONES) --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-primary border-top shadow-lg px-2 py-1.5 flex items-center justify-around">
        <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('student.dashboard') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-gauge-high text-base mb-0.5"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('student.courses') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('student.courses') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-graduation-cap text-base mb-0.5"></i>
            <span>Courses</span>
        </a>
        <a href="{{ route('student.fees') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('student.fees') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-wallet text-base mb-0.5"></i>
            <span>Fees</span>
        </a>
        <a href="{{ route('student.attendance') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('student.attendance') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-calendar-check text-base mb-0.5"></i>
            <span>Attendance</span>
        </a>
        <a href="{{ route('student.profile') }}" class="flex flex-col items-center justify-center p-1.5 text-[10px] font-bold {{ request()->routeIs('student.profile') ? 'text-primary' : 'text-tertiary' }}">
            <i class="fa-solid fa-user-gear text-base mb-0.5"></i>
            <span>Profile</span>
        </a>
    </div>

</body>

</html>
