@php
    $user = Auth::guard('web')->user();
    $userTenants = $user ? $user->tenants : collect();
    $routeTenant = request()->route('tenant') ?? request()->route('subdomain') ?? request()->segment(2);
    $currentSubdomain = is_object($routeTenant) ? $routeTenant->subdomain : $routeTenant;
    $isIndexActive = request()->routeIs('tenants.index');
    $isCreateActive = request()->routeIs('tenants.create');
    $isShowActive = request()->routeIs('tenants.show');
    $isDomainsActive = request()->routeIs('tenants.domains');
    $isBillingActive = request()->routeIs('tenants.billing');
    $isStorageActive = request()->routeIs('tenants.storage');
    $isBackupsActive = request()->routeIs('tenants.backups');
    $isSecurityActive = request()->routeIs('tenants.security');
@endphp

<aside id="arzavoTenantsSidebar"
    class="fixed top-0 left-0 w-[260px] h-dvh bg-primary overflow-y-auto p-4 pb-8 z-40 -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out">

    <!-- Logo -->
    <a href="{{ route('tenants.index') }}" class="shrink-0 flex items-center gap-2 mb-6">
        <img class="theme-light-logo h-10 mb-3 w-auto object-contain" src="{{ media('images/logo/arzavo-dark.png') }}" alt="Logo">
        <img class="theme-dark-logo -mt-3 h-16 w-auto object-contain" src="{{ media('images/logo/arzavo-white.png') }}" alt="Logo">
    </a>

    <!-- Navigation List -->
    <nav class="space-y-5">
        <!-- 1. INSTITUTES & ACADEMIES -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Institutes & Academies
            </p>
            <ul class="space-y-1">
                <!-- All Institutes Link -->
                <li>
                    <a href="{{ route('tenants.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isIndexActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-xs w-4 text-center"></i>
                            <span>All Institutes</span>
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isIndexActive ? 'bg-primary/20 text-primary' : 'bg-secondary text-theme-text-muted' }}">
                            {{ $userTenants->count() }}
                        </span>
                    </a>
                </li>

                <!-- SUB-MENU: List of User Institutes -->
                @if($userTenants->count() > 0)
                    <li class="pt-1">
                        <div class="ml-4 pl-3 border-l border-secondary space-y-1">
                            @foreach($userTenants as $t)
                                @php
                                    $isThisActive = ($isShowActive && $currentSubdomain === $t->subdomain);
                                    $sub = $t->subscription;
                                    $isExp = $sub ? !$sub->isActive() : true;
                                    $dotColor = ($t->status === 'suspended' || $t->status === 'inactive' || $isExp || $t->status === 'expired')
                                        ? 'bg-danger'
                                        : ($t->isTrialActive() ? 'bg-info' : 'bg-success');
                                @endphp
                                <a href="{{ route('tenants.show', $t->subdomain) }}"
                                   title="{{ $t->name }} ({{ $t->subdomain }})"
                                   class="flex items-center justify-between px-3 py-2 rounded-full text-[11px] transition duration-150 {{ $isThisActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                                    <span class="truncate flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isThisActive ? 'bg-primary' : $dotColor }} shrink-0"></span>
                                        <span class="truncate">{{ $t->name }}</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-right text-[8px] {{ $isThisActive ? 'text-primary opacity-80' : 'opacity-40' }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endif

                <!-- Create Institute CTA -->
                <li class="pt-1">
                    <a href="{{ route('tenants.create') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isCreateActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-plus text-xs w-4 text-center"></i>
                        <span>Add Institute</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 2. FLEET MANAGEMENT HUBS -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Fleet Management
            </p>
            <ul class="space-y-1">
                <!-- Domains Hub -->
                <li>
                    <a href="{{ route('tenants.domains') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isDomainsActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-globe text-xs w-4 text-center"></i>
                        <span>Domains & DNS</span>
                    </a>
                </li>

                <!-- Unified Billing & Invoices -->
                <li>
                    <a href="{{ route('tenants.billing') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isBillingActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-credit-card text-xs w-4 text-center"></i>
                        <span>Plans & Invoices</span>
                    </a>
                </li>

                <!-- Storage & Quotas -->
                <li>
                    <a href="{{ route('tenants.storage') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isStorageActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-server text-xs w-4 text-center"></i>
                        <span>Storage & Quotas</span>
                    </a>
                </li>

                <!-- Database Backups Vault -->
                <li>
                    <a href="{{ route('tenants.backups') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isBackupsActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-database text-xs w-4 text-center"></i>
                        <span>Database Backups</span>
                    </a>
                </li>

                <!-- Security & Audit Logs -->
                <li>
                    <a href="{{ route('tenants.security') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs transition duration-150 {{ $isSecurityActive ? 'bg-brand text-primary font-semibold' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary font-normal' }}">
                        <i class="fa-solid fa-shield-halved text-xs w-4 text-center"></i>
                        <span>Security & Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 3. HELP & SUPPORT -->
        <div>
            <p class="text-[10px] font-normal tracking-wider text-theme-text-muted uppercase px-3 mb-1.5">
                Support & Guides
            </p>
            <ul class="space-y-1">
                <li>
                    <a href="https://docs.arzavo.com" target="_blank"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs text-theme-text-muted hover:text-theme-text hover:bg-secondary transition duration-150 font-normal">
                        <i class="fa-solid fa-book-open text-xs w-4 text-center"></i>
                        <span>Setup Documentation</span>
                    </a>
                </li>
                <li>
                    <a href="mailto:support@arzavo.com"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs text-theme-text-muted hover:text-theme-text hover:bg-secondary transition duration-150 font-normal">
                        <i class="fa-solid fa-headset text-xs w-4 text-center"></i>
                        <span>Help Desk</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
