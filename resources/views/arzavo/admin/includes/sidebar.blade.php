<div id="arzavoAdminSidebar" class="admin-sidebar -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
    w-[260px] h-full fixed top-16 left-0 overflow-y-auto p-4 pt-6 pb-10 z-40
    scrollbar bg-primary border-r border-primary">

    {{-- =======================
    HEADER
    ======================== --}}
    @php
        $user = $user ?? auth('web')->user();
    @endphp
    <div class="mb-6 flex items-center gap-2">
        <x-profile-image :user="$user" />
        <div class="info">
            <h2 class="text-base font-semibold text-secondary">
                {{ $user->fname ?? 'Super' }} {{ $user->lname ?? 'Admin' }}
            </h2>
            <p class="text-[11px] text-tertiary">
                {{ $user->email ?? 'admin@arzavo.com' }}
            </p>
        </div>
    </div>

    {{-- =======================
    NAVIGATION
    ======================== --}}
    @php
        $sidebar = [
            [
                'section' => 'Overview',
                'items' => [
                    [
                        'text' => 'Dashboard',
                        'icon' => 'fa-chart-line',
                        'route' => 'arzavo.admin.dashboard',
                        'active' => 'dashboard'
                    ],
                ]
            ],
            [
                'section' => 'Management',
                'items' => [
                    [
                        'text' => 'Users',
                        'icon' => 'fa-users',
                        'route' => 'arzavo.admin.users.index',
                        'active' => 'admin/users*'
                    ],
                    [
                        'text' => 'Tenants',
                        'icon' => 'fa-building-columns',
                        'route' => 'arzavo.admin.tenants.index',
                        'active' => 'admin/tenants*'
                    ],
                ]
            ],
            [
                'section' => 'Billing',
                'items' => [
                    [
                        'text' => 'Plans',
                        'icon' => 'fa-credit-card',
                        'route' => 'arzavo.admin.plans.index',
                        'active' => 'admin/plans*'
                    ],
                    [
                        'text' => 'Subscriptions',
                        'icon' => 'fa-box',
                        'active' => 'admin/subscriptions*'
                    ],
                    [
                        'text' => 'Invoices',
                        'icon' => 'fa-file-invoice',
                        'active' => 'admin/invoices*'
                    ],
                    [
                        'text' => 'Payments',
                        'icon' => 'fa-money-bill',
                        'active' => 'admin/payments*'
                    ],
                ]
            ],
            [
                'section' => 'System',
                'items' => [
                    [
                        'text' => 'Addons',
                        'icon' => 'fa-puzzle-piece',
                        'active' => 'admin/addons*'
                    ],
                    [
                        'text' => 'Settings',
                        'icon' => 'fa-gear',
                        'active' => 'admin/settings*'
                    ],
                ]
            ],
        ];
    @endphp
    <ul class="flex flex-col gap-1 arzavo-nav">

        @foreach($sidebar as $section)

            {{-- 🔹 SECTION TITLE --}}
            <li class="section-title text-[10px] my-2 text-tertiary flex items-center gap-1 uppercase">
                {{ $section['section'] }}
                <div class="h-0.5 bg-tertiary w-full"></div>
            </li>

            {{-- 🔹 ITEMS --}}
            @foreach($section['items'] as $item)

                <li>
                    <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                        class=" text-secondary text-hover-invert bg-hover-invert p-2 border-rounded text-sm block w-full {{ (isset($item['route']) && request()->routeIs($item['route'].'*')) || (isset($item['active']) && (request()->is($item['active']) || request()->is(ltrim(str_replace('admin', '', $item['active']), '/')))) ? 'bg-invert text-invert' : '' }}">

                        <i class="fa-solid {{ $item['icon'] }} mr-2"></i>
                        {{ $item['text'] }}

                    </a>
                </li>

            @endforeach

        @endforeach

    </ul>

</div>