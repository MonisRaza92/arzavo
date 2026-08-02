@php
    $user = Auth::guard('web')->user();
    $tab = request('tab', 'workspaces');
@endphp
<div id="arzavoTenantsSidebar" class="admin-sidebar -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out
    w-[260px] h-full fixed top-16 left-0 overflow-y-auto p-4 pt-6 pb-10 z-40
    scrollbar bg-primary border-r border-primary">

    {{-- =======================
    HEADER
    ======================== --}}
    <div class="mb-6 flex items-center gap-2">
        <x-profile-image :user="$user" />
        <div class="info">
            <h2 class="text-base font-semibold text-secondary">
                {{ $user->fname }} {{ $user->lname }}
            </h2>
            <p class="text-[11px] text-tertiary">
                {{ $user->email }}
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
                        'text' => 'Workspaces',
                        'icon' => 'fa-building-columns',
                        'url' => route('tenants.index', ['tab' => 'workspaces']),
                        'active' => $tab === 'workspaces' && request()->routeIs('tenants.index')
                    ],
                    [
                        'text' => 'Create Workspace',
                        'icon' => 'fa-plus',
                        'url' => route('tenants.create'),
                        'active' => request()->routeIs('tenants.create')
                    ]
                ]
            ],
            [
                'section' => 'Settings',
                'items' => [
                    [
                        'text' => 'Verify Domain',
                        'icon' => 'fa-globe',
                        'url' => route('tenants.index', ['tab' => 'domain']),
                        'active' => $tab === 'domain' && request()->routeIs('tenants.index')
                    ]
                ]
            ],
            [
                'section' => 'Billing & Payments',
                'items' => [
                    [
                        'text' => 'Plans & Billing',
                        'icon' => 'fa-credit-card',
                        'url' => route('tenants.index', ['tab' => 'billing']),
                        'active' => $tab === 'billing' && request()->routeIs('tenants.index')
                    ]
                ]
            ]
        ];
    @endphp

    <ul class="flex flex-col gap-1 arzavo-nav">
        @foreach($sidebar as $section)
            {{-- SECTION TITLE --}}
            <li class="section-title text-[10px] my-2 text-tertiary flex items-center gap-1 uppercase">
                <span class="shrink-0">{{ $section['section'] }}</span>
                <span class="h-[1px] w-full bg-slate-200 block"></span>
            </li>

            {{-- ITEMS --}}
            @foreach($section['items'] as $item)
                <li>
                    <a href="{{ $item['url'] }}" 
                       class="block p-2 border-rounded text-sm hover:bg-hover-secondary transition duration-200
                       {{ $item['active'] ? 'bg-invert text-invert' : 'text-secondary' }}">
                        <i class="fa-solid {{ $item['icon'] }} mr-2"></i>
                        <span>{{ $item['text'] }}</span>
                    </a>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
