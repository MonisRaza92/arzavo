@php
    $user = Auth::guard('web')->user();
    $isDashboardActive = request()->routeIs('arzavo.admin.dashboard');
    $isTenantsActive = request()->routeIs('arzavo.admin.tenants.*');
    $isUsersActive = request()->routeIs('arzavo.admin.users.*');
    $isPlansActive = request()->routeIs('arzavo.admin.plans.*');
@endphp

<aside id="arzavoAdminSidebar"
    class="fixed top-0 left-0 w-[260px] h-dvh bg-primary overflow-y-auto p-4 pb-8 z-40 -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out">

    <!-- Brand Logo -->
    <a href="{{ route('arzavo.admin.dashboard') }}" class="shrink-0 flex items-center gap-2 mb-4">
        <img class="theme-light-logo h-10 mb-3 w-auto object-contain" src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo">
        <img class="theme-dark-logo -mt-3 h-16 w-auto object-contain" src="{{ media('images/logo/arzavo-white.png') }}" alt="Logo">
    </a>
    <!-- Navigation List -->
    <nav class="space-y-5">
        <!-- 1. OVERVIEW -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Overview
            </p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('arzavo.admin.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-sm font-normal transition duration-150 {{ $isDashboardActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary' }}">
                        <i class="fa-solid fa-chart-line text-sm w-4 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 2. ECOSYSTEM MANAGEMENT -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Management
            </p>
            <ul class="space-y-1">
                <!-- Tenants / Institutes -->
                <li>
                    <a href="{{ route('arzavo.admin.tenants.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-full text-sm font-normal transition duration-150 {{ $isTenantsActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-building-columns text-sm w-4 text-center"></i>
                            <span>Tenants</span>
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $isTenantsActive ? 'bg-primary text-brand' : 'bg-secondary text-theme-text-muted' }}">
                            {{ \App\Models\Arzavo\Tenant::count() }}
                        </span>
                    </a>
                </li>

                <!-- Platform Users -->
                <li>
                    <a href="{{ route('arzavo.admin.users.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-full text-sm font-normal transition duration-150 {{ $isUsersActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-users text-sm w-4 text-center"></i>
                            <span>Users</span>
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $isUsersActive ? 'bg-primary text-brand' : 'bg-secondary text-theme-text-muted' }}">
                            {{ \App\Models\Arzavo\User::count() }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 3. BILLING & REVENUE -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Billing & Growth
            </p>
            <ul class="space-y-1">
                <!-- Subscription Plans -->
                <li>
                    <a href="{{ route('arzavo.admin.plans.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-full text-sm font-normal transition duration-150 {{ $isPlansActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-credit-card text-sm w-4 text-center"></i>
                            <span>Pricing Plans</span>
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $isPlansActive ? 'bg-primary text-brand' : 'bg-secondary text-theme-text-muted' }}">
                            {{ \App\Models\Arzavo\Plan::count() }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 4. QUICK SWITCH PORTALS -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Quick Navigation
            </p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('tenants.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-sm font-normal text-theme-text-muted hover:text-theme-text hover:bg-secondary transition duration-150">
                        <i class="fa-solid fa-graduation-cap text-sm w-4 text-center"></i>
                        <span>Tenant Cockpit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-sm font-normal text-theme-text-muted hover:text-theme-text hover:bg-secondary transition duration-150">
                        <i class="fa-solid fa-arrow-up-right-from-square text-sm w-4 text-center"></i>
                        <span>Live Website</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>