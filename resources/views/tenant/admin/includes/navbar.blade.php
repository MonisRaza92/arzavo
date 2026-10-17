@php
    $currentTenant = app()->bound('currentTenant') ? app('currentTenant') : null;
    $customizes = $customizes ?? [];
    $siteName = $currentTenant?->name ?? 'Arzavo';
    $user = auth('tenant')->user() ?? ($user ?? null);
@endphp

<div class="admin-navbar w-full sticky top-0 right-0 z-50 flex items-center justify-between px-4 sm:px-6 py-3 bg-primary">
    {{-- Left: Institute Brand / Logo --}}
    <a href="{{ route('tenant.home') }}" class="shrink-0 flex items-center gap-2.5 focus:outline-none">
        @if(!empty($customizes['logo']))
            <img id="logo" src="{{ media($customizes['logo']) }}" alt="{{ $siteName }}" class="h-10 max-h-10 max-w-auto object-contain">
        @else
            <div class="h-8 px-3.5 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center tracking-wide uppercase">
                {{ $siteName }}
            </div>
        @endif
    </a>

    {{-- Center: Live Filter / Global Search --}}
    <div class="search-bar relative hidden md:flex items-center flex-1 mx-6">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-theme-text-muted text-xs pointer-events-none"></i>
        <input type="text"
               placeholder="Search students, faculty, courses, records..."
               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs pl-9 pr-12 py-3 rounded-full focus:outline-none">
        <span class="absolute right-3 px-2 py-1 rounded-full bg-primary text-[10px] text-theme-text-muted font-normal tracking-wide">
            ⌘K
        </span>
    </div>

    {{-- Right: Actions & User Cockpit --}}
    <div class="flex items-center gap-1 sm:gap-2">
        {{-- View Public Institute Site (Icon only, no bg) --}}
        <a href="{{ route('tenant.home') }}"
           target="_blank"
           title="View Public Website"
           class="text-theme-text-muted hidden hover:text-theme-text transition w-10 h-10 text-base sm:flex bg-secondary rounded-full items-center justify-center focus:outline-none">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>

        {{-- Billing & Plans (Icon only, no bg) --}}
        <a href="{{ route('admin.billing.index') }}"
           title="Billing & Quotas"
           class="text-theme-text-muted hidden hover:text-theme-text transition w-10 h-10 text-base sm:flex bg-secondary rounded-full items-center justify-center focus:outline-none">
            <i class="fa-solid fa-receipt"></i>
        </a>

        {{-- Theme Mode Toggle (Single Dynamic Icon, no bg) --}}
        <button type="button"
                x-data="{
                    isDark: document.documentElement.classList.contains('dark') || document.documentElement.getAttribute('data-theme') === 'dark',
                    toggle() {
                        this.isDark = !this.isDark;
                        var mode = this.isDark ? 'dark' : 'light';
                        document.documentElement.setAttribute('data-theme', mode);
                        if (this.isDark) {
                            document.documentElement.classList.add('dark');
                            document.documentElement.classList.remove('light');
                        } else {
                            document.documentElement.classList.add('light');
                            document.documentElement.classList.remove('dark');
                        }
                        localStorage.setItem('arzaq_theme', mode);
                    }
                }"
                @click="toggle()"
                title="Toggle Theme Mode"
                class="text-theme-text-muted w-10 h-10 bg-secondary rounded-full hover:text-theme-text transition p-2 text-base flex items-center justify-center focus:outline-none cursor-pointer">
            <i :class="isDark ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-theme-text-muted'"></i>
        </button>

        {{-- USER COCKPIT DROPDOWN --}}
        @if($user)
            <div class="relative ml-1" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                        @click.outside="userMenuOpen = false"
                        class="flex items-center gap-2 p-1 rounded-full hover:bg-secondary transition focus:outline-none">
                    @if(!empty($user->profile_picture))
                        <img src="{{ media($user->profile_picture) }}" alt="{{ $user->fname }}" class="w-9 h-9 rounded-full object-cover">
                    @else
                        <div class="w-9 h-9 rounded-full bg-brand text-primary font-semibold text-xs flex items-center justify-center uppercase shrink-0">
                            {{ strtoupper(substr($user->fname ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden sm:block text-left pr-1">
                        <p class="text-xs font-semibold text-theme-text leading-tight">{{ $user->fname }} {{ $user->lname }}</p>
                        <p class="text-[10px] text-theme-text-muted capitalize leading-none mt-0.5">{{ $user->role ?? 'Admin' }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-theme-text-muted hidden sm:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute right-0 mt-2 w-64 rounded-lg bg-primary shadow-xl p-2.5 z-50 text-xs"
                     style="display: none;">

                    <!-- User Header Card -->
                    <div class="p-3 rounded-lg bg-secondary mb-2">
                        <p class="font-semibold text-theme-text">{{ $user->fname }} {{ $user->lname }}</p>
                        <p class="text-[11px] text-theme-text-muted truncate mt-0.5">{{ $user->email }}</p>
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] bg-brand text-primary font-normal uppercase tracking-wider">
                            {{ $user->role ?? 'Tenant Admin' }}
                        </span>
                    </div>

                    <!-- Tenant Switcher Section -->
                    @php
                        $globalUser = $user->globalUser ?? null;
                        $tenants = $globalUser ? $globalUser->tenants : collect();
                    @endphp

                    @if($tenants->count() > 0)
                        <div class="px-2 py-1 text-[10px] text-theme-text-muted uppercase tracking-wider font-medium">Switch Institute</div>
                        <div class="max-h-36 overflow-y-auto space-y-1 mb-2">
                            @foreach($tenants as $t)
                                <a href="{{ $t->url }}/admin/dashboard"
                                   class="flex items-center justify-between p-2 rounded-full hover:bg-secondary text-theme-text transition {{ $currentTenant && $currentTenant->id === $t->id ? 'bg-secondary text-brand font-medium' : '' }}">
                                    <span class="truncate flex items-center gap-2">
                                        <i class="fa-solid fa-building-columns text-[11px] text-theme-text-muted"></i>
                                        {{ $t->name }}
                                    </span>
                                    @if($currentTenant && $currentTenant->id === $t->id)
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand"></span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-1 pt-1">
                        <a href="{{ route('tenants.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-full hover:bg-secondary text-theme-text transition">
                            <i class="fa-solid fa-plus text-theme-text-muted text-[11px]"></i>
                            <span>Create New Institute</span>
                        </a>
                        <a href="{{ route('tenants.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-full hover:bg-secondary text-theme-text transition">
                            <i class="fa-solid fa-layer-group text-theme-text-muted text-[11px]"></i>
                            <span>Manage All Institutes</span>
                        </a>
                        <a href="{{ route('admin.billing.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-full hover:bg-secondary text-theme-text transition">
                            <i class="fa-solid fa-credit-card text-theme-text-muted text-[11px]"></i>
                            <span>Billing & Subscription</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-full hover:bg-secondary text-theme-text transition">
                            <i class="fa-solid fa-gear text-theme-text-muted text-[11px]"></i>
                            <span>Institute Settings</span>
                        </a>
                        <div class="my-1.5 h-px bg-secondary"></div>
                        <a href="{{ route('tenant.logout') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-full hover:bg-danger-bg text-danger transition">
                            <i class="fa-solid fa-right-from-bracket text-[11px]"></i>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mobile Hamburger Toggle (Icon only, no bg) --}}
        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                class="lg:hidden text-theme-text-muted hover:text-theme-text p-2 text-base flex items-center justify-center focus:outline-none cursor-pointer">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>