{{-- SECTION 2: USERS & COMMUNITY DIRECTORY (ROLE-BASED BREAKDOWN) --}}
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex bg-primary p-4 rounded-lg items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-theme-text flex items-center gap-2">
                <i class="fa-solid fa-users text-brand text-sm"></i>
                Users & Directory
            </h2>
            <p class="text-[11px] text-theme-text-muted mt-0.5">
                Segregated accounts across students, teachers, staff, normal users, and admins
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-secondary text-xs text-theme-text font-normal">
                {{ $totalUsers }} Total Accounts
            </span>
            <a href="{{ route('admin.users') }}"
               class="px-3.5 py-1.5 rounded-full bg-secondary hover:bg-subtle text-xs text-theme-text font-normal transition inline-flex items-center gap-1.5">
                <span>All Users</span>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
            </a>
        </div>
    </div>

    {{-- Role-Based Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">

        {{-- 1. Students Card --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Students</span>
                    <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalStudents }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Enrolled institute learners
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="font-medium text-success">{{ $activeStudents }} active</span>
                </div>
                <a href="{{ route('admin.students') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Directory <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 2. Teachers Card --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Teachers</span>
                    <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalTeachers }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Teaching staff & instructors
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <span class="font-medium text-success">{{ $activeTeachers }} Active</span>
                </div>
                <a href="{{ route('admin.users') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Faculty <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 3. Staff Card --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Staff</span>
                    <div class="w-8 h-8 rounded-full bg-sky-500/10 text-sky-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalStaff }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Support & operations staff
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <span class="text-theme-text-muted text-[11px]">Support Staff</span>
                <a href="{{ route('admin.users') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Manage <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 4. Normal Users Card --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Normal Users</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalNormalUsers }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    General registered accounts
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <span class="text-theme-text-muted text-[11px]">Registered Members</span>
                <a href="{{ route('admin.users') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    View <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

        {{-- 5. Admins Card --}}
        <div class="rounded-lg bg-primary p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-theme-text-muted font-normal">Admins</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>
                <h3 class="text-2xl sm:text-3xl font-semibold text-theme-text tracking-tight">
                    {{ $totalAdmins }}
                </h3>
                <p class="text-[11px] text-theme-text-muted mt-1">
                    Portal administrators
                </p>
            </div>

            <div class="mt-4 p-3 rounded-lg bg-secondary flex items-center justify-between text-xs text-theme-text">
                <span class="text-theme-text-muted text-[11px]">System Managers</span>
                <a href="{{ route('admin.users') }}" class="text-[11px] text-brand hover:underline font-normal inline-flex items-center gap-1">
                    Admins <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>

    </div>
</div>
