@php
    $authUser = Auth::guard('tenant')->user();
@endphp
<div class="admin-navbar w-full sticky top-0 right-0 z-99 flex gap-8 items-center justify-between px-4 py-3 bg-primary border-bottom">
    <a href="{{ route('tenant.home') }}" class="shrink-0">
        <img id="logo" src="{{ media($customizes['logo'] ?? 'images/logo/arzavo-dark.png') }}" alt="Logo" class="logo">
    </a>
    
    <div class="search-bar relative hidden md:block text-sm grow">
        <input type="text" placeholder="Search courses, lessons..."
            class="search-input w-full border-rounded px-3 py-2.25 bg-primary border-primary">
        <button class="search-button absolute right-0 top-0 mt-1.5 mr-1.5 bg-secondary text-xs border-primary py-1.25 px-2 border-rounded text-tertiary">
            Ctrl + k
        </button>
    </div>

    <div class="flex items-center gap-2 md:gap-4">
        <!-- Help -->
        <button title="Help" class="text-2xl text-secondary hidden md:block">
            <i class="fa-regular fa-circle-question"></i>
        </button>

        <!-- Notifications -->
        <button class="relative text-2xl text-secondary">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute -top-1 -right-1 text-[10px] bg-accent text-invert px-1 border-rounded">
                3
            </span>
        </button>

        <!-- STUDENT PROFILE DROPDOWN -->
        <div class="relative ml-2">
            <button onclick="toggleModel('tenantUserAction')" class="flex items-center gap-2">
                @if($authUser && $authUser->profile_picture)
                    <img src="{{ asset($authUser->profile_picture) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-primary">
                @else
                    <div class="w-8 h-8 rounded-full bg-hover-secondary border border-primary flex items-center justify-center font-bold text-xs uppercase text-primary">
                        {{ substr($authUser->fname ?? 'S', 0, 1) }}{{ substr($authUser->lname ?? 'T', 0, 1) }}
                    </div>
                @endif
            </button>

            <!-- DROPDOWN CONTENT -->
            <div id="tenantUserAction" class="absolute hidden right-0 mt-2 w-64 bg-primary border-primary border-rounded shadow-md overflow-hidden">
                <!-- Student Info -->
                <div class="px-3 py-3 border-bottom">
                    <p class="text-sm font-semibold">
                        {{ ($authUser->fname ?? '') . ' ' . ($authUser->lname ?? '') }}
                    </p>
                    <p class="text-[10px] text-tertiary font-mono">Roll #: STU-{{ str_pad($authUser->id ?? 1, 5, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Custom Portal Links -->
                <div class="py-1">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-gauge-high text-tertiary text-sm w-4"></i>
                        Dashboard Overview
                    </a>
                    <a href="{{ route('student.courses') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-graduation-cap text-tertiary text-sm w-4"></i>
                        My Courses
                    </a>
                    <a href="{{ route('student.assignments') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-pen-ruler text-tertiary text-sm w-4"></i>
                        Assignments
                    </a>
                    <a href="{{ route('student.fees') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-wallet text-tertiary text-sm w-4"></i>
                        Fees & Billing
                    </a>
                    <a href="{{ route('student.attendance') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-calendar-check text-tertiary text-sm w-4"></i>
                        Attendance Log
                    </a>
                    <a href="{{ route('student.certificates') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-medal text-tertiary text-sm w-4"></i>
                        My Certificates
                    </a>
                    <a href="{{ route('student.profile') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-user-gear text-tertiary text-sm w-4"></i>
                        Profile Settings
                    </a>
                </div>

                <!-- LOGOUT -->
                <div class="border-top mt-1">
                    <a href="{{ route('tenant.logout') }}" class="flex items-center gap-2 px-3 py-3 text-xs font-semibold text-secondary hover:bg-hover-secondary">
                        <i class="fa-solid fa-right-from-bracket text-tertiary text-sm"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile hamburger menu -->
        <button onclick="document.getElementById('adminMobileMenu').classList.toggle('-translate-x-full')" class="lg:hidden border-rounded text-xl flex justify-center items-center logo aspect-square bg-invert text-invert">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>
