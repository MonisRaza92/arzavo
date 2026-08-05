<div
    class="admin-navbar w-full sticky top-0 right-0 z-99 flex gap-8 items-center justify-between px-4 py-3 bg-primary border-bottom">
    <a href="{{ route('tenant.home') }}" class="shrink-0">
        <img id="logo" src="{{ media($customizes['logo'] ?? 'images/logo/arzavo-dark.png') }}" alt="Logo" class="logo">
    </a>
    <div class="search-bar relative hidden md:block text-sm grow">
        <input type="text" placeholder="Search..."
            class="search-input w-full border-rounded px-3 py-2.25 bg-primary border-primary">
        <button
            class="search-button absolute right-0 top-0 mt-1.5 mr-1.5 bg-secondary text-xs border-primary py-1.25 px-2 border-rounded text-tertiary">Ctrl
            + k</button>
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
                2
            </span>
        </button>

        <!-- USER -->
        <div class="relative ml-2">

            <button onclick="toggleModel('tenantUserAction')" class="flex items-center gap-2">
                <x-profile-image :user="$user" />
            </button>

            <!-- DROPDOWN -->
            <div id="tenantUserAction"
                class="absolute hidden right-0 mt-2 w-64 bg-primary border-primary border-rounded shadow-md overflow-hidden">

                <!-- USER INFO -->
                <div class="px-3 py-3 border-bottom">
                    <p class="text-sm font-semibold">
                        {{ ($user->fname ?? '') . ' ' . ($user->lname ?? '') }}
                    </p>
                    <p class="text-xs text-tertiary">{{ $user->email }}</p>
                </div>

                <!-- TENANTS SECTION -->
                <div>
                    @php
                        $globalUser = $user->globalUser ?? null;
                        $tenants = $globalUser ? $globalUser->tenants : collect();
                        $currentTenant = app('currentTenant');
                    @endphp

                    <div class="max-h-40 overflow-auto">

                        @forelse($tenants as $tenant)
                                            <a href="{{ $tenant->url }}/admin/dashboard"
                                                class="flex items-center justify-between gap-2 p-3 text-sm border-bottom bg-hover-secondary">
                                                <span class="truncate"><i
                                                        class="fa-solid fa-building-columns text-tertiary text-sm mr-1"></i>
                                                    {{ $tenant->name }}</span>
                                                @if ($currentTenant->id === $tenant->id)
                                                    <span class="flex items-center gap-1 text-xs text-green-600">
                                                        <i class="fa-solid fa-check"></i>
                                                        Active
                                                    </span>
                                                @endif
                                            </a>
                        @empty
                            <p class="px-3 py-2 text-xs text-tertiary">
                                No tenants found
                            </p>
                        @endforelse

                    </div>

                    <!-- CREATE TENANT -->
                    <a href="{{ route('tenants.create') }}"
                        class="flex items-center gap-2 p-3 text-sm text-secondary bg-hover-secondary border-bottom">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Create New
                    </a>
                </div>

                <!-- ACCOUNT SECTION -->
                <div class="pt-2">


                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-solid fa-user text-tertiary text-sm"></i>
                        Profile
                    </a>

                    <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-solid fa-gear text-tertiary text-sm"></i>
                        Account Settings
                    </a> -->

                    <a href="{{ route('tenants.index') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-solid fa-building-columns text-tertiary text-sm"></i>
                        Manage Tenants
                    </a>

                    <a href="{{ route('admin.billing.index') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-solid fa-credit-card text-tertiary text-sm"></i>
                        Billing & Plans
                    </a>

                    <!-- <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-regular fa-clock text-tertiary text-sm"></i>
                        Activity
                    </a>

                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm hover-primary">
                        <i class="fa-regular fa-circle-question text-tertiary text-sm"></i>
                        Help & Support
                    </a> -->

                </div>

                <!-- LOGOUT -->
                <div class="border-top mt-2">
                    <a href="{{ route('tenant.logout') }}"
                        class="flex items-center gap-2 px-3 py-3 text-sm hover-primary">
                        <i class="fa-solid fa-right-from-bracket text-tertiary text-sm"></i>
                        Logout
                    </a>
                </div>

            </div>
        </div>
        <button onclick="document.getElementById('adminMobileMenu').classList.toggle('-translate-x-full')" class="lg:hidden border-rounded text-xl flex justify-center items-center logo aspect-square bg-invert text-invert"><i
                class="fa-solid fa-bars"></i></button>

    </div>
</div>