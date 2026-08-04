<div id="adminMobileMenu" class="admin-sidebar -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
     w-[260px] h-full fixed top-0 left-0 overflow-y-auto p-4 pr-4.5 pt-0 pb-10 z-30 scrollbar bg-primary border-right"
    style="margin-top: calc(var(--logo-size) + 14px);">

    <div class="sticky top-0 z-10 bg-primary pt-4 pb-2 mb-2 border-bottom">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center justify-center font-bold text-sm uppercase">
                {{ substr(Auth::guard('tenant')->user()->fname ?? 'S', 0, 1) }}{{ substr(Auth::guard('tenant')->user()->lname ?? 'T', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <h4 class="text-xs font-bold text-primary truncate">{{ Auth::guard('tenant')->user()->fname ?? 'Student' }} {{ Auth::guard('tenant')->user()->lname ?? '' }}</h4>
                <p class="text-[10px] text-tertiary truncate">Roll #: STU-{{ str_pad(Auth::guard('tenant')->user()->id ?? 1, 5, '0', STR_PAD_LEFT) }}</p>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase">
                    Student Portal
                </span>
            </div>
        </div>
    </div>

    {{-- NAVIGATION ITEMS --}}
    <ul class="flex flex-col gap-1.5 mb-12">

        <li class="text-tertiary text-[10px] py-1.5 font-bold uppercase tracking-wider">LMS Learning</li>

        <li>
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.dashboard') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-gauge-high text-sm w-4"></i>
                <span>Dashboard Overview</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.courses') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.courses') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-graduation-cap text-sm w-4"></i>
                <span>My Courses & Batches</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.assignments') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.assignments') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-pen-ruler text-sm w-4"></i>
                <span>Assignments & Quizzes</span>
            </a>
        </li>

        <li class="text-tertiary text-[10px] py-1.5 font-bold uppercase tracking-wider mt-4">Academics & Fees</li>

        <li>
            <a href="{{ route('student.fees') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.fees') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-wallet text-sm w-4"></i>
                <span>Fee Installments & Dues</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.attendance') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.attendance') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-calendar-check text-sm w-4"></i>
                <span>Attendance & Schedule</span>
            </a>
        </li>

        <li>
            <a href="{{ route('student.certificates') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.certificates') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-award text-sm w-4"></i>
                <span>My Certificates</span>
            </a>
        </li>

        <li class="text-tertiary text-[10px] py-1.5 font-bold uppercase tracking-wider mt-4">Settings</li>

        <li>
            <a href="{{ route('student.profile') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold transition {{ request()->routeIs('student.profile') ? 'bg-invert text-invert' : 'text-secondary hover:bg-hover-secondary' }}">
                <i class="fa-solid fa-user-gear text-sm w-4"></i>
                <span>Profile & Guardian Info</span>
            </a>
        </li>

        <li>
            <a href="{{ route('tenant.logout') }}" 
               class="flex items-center gap-2.5 p-2.5 border-rounded text-xs font-semibold text-rose-600 hover:bg-rose-500/10 transition mt-2">
                <i class="fa-solid fa-right-from-bracket text-sm w-4"></i>
                <span>Logout Account</span>
            </a>
        </li>

    </ul>
</div>
