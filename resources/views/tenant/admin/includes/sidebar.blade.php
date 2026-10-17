@php
    $globalUser = $user->globalUser ?? null;
    $tenants = $globalUser ? $globalUser->tenants : collect();
    $currentTenant = app()->bound('currentTenant') ? app('currentTenant') : null;
@endphp

<aside id="adminMobileMenu"
       class="admin-sidebar transition-all duration-300 ease-in-out w-[280px] h-[calc(100vh-57px)] fixed top-[57px] left-0 overflow-y-auto scrollbar px-3.5 py-4 z-40 bg-primary scrollbar"
       :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- 1. INSTITUTE CONTEXT / SWITCHER --}}
    <div class="mb-3 relative" x-data="{ tenantDropdown: false }">
        <button @click="tenantDropdown = !tenantDropdown"
                @click.outside="tenantDropdown = false"
                class="w-full flex items-center justify-between p-2.5 rounded-lg bg-secondary text-sm text-theme-text transition hover:opacity-90 focus:outline-none">
            <div class="flex items-center gap-2.5 truncate">
                <div class="w-8 h-8 rounded-full bg-brand text-primary text-xs flex items-center justify-center shrink-0 uppercase">
                    {{ substr($currentTenant->name ?? 'I', 0, 1) }}
                </div>
                <div class="text-left truncate">
                    <p class="truncate leading-tight text-theme-text text-sm">{{ $currentTenant->name ?? 'Institute' }}</p>
                    <p class="text-[11px] text-theme-text-muted leading-tight mt-0.5">Admin Workspace</p>
                </div>
            </div>
            <i class="fa-solid fa-chevron-down text-xs text-theme-text-muted"></i>
        </button>

        {{-- Dropdown for quick switching --}}
        <div x-show="tenantDropdown"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
             class="absolute left-0 mt-2 w-full rounded-lg bg-primary shadow-xl p-2 z-50 text-sm"
             style="display: none;">
            @forelse($tenants as $tenant)
                <a href="{{ $tenant->url }}/admin/dashboard"
                   class="flex items-center gap-2 px-3 py-2 rounded-full text-sm text-theme-text hover:bg-secondary transition {{ $currentTenant && $currentTenant->id === $tenant->id ? 'bg-secondary text-brand' : '' }}">
                    <i class="fa-solid fa-building-columns text-xs text-theme-text-muted"></i>
                    <span class="truncate">{{ $tenant->name }}</span>
                </a>
            @empty
                <p class="px-3 py-2 text-xs text-theme-text-muted">No other institutes</p>
            @endforelse
            <div class="my-1 h-px bg-secondary"></div>
            <a href="{{ route('tenants.create') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-full text-sm text-brand hover:bg-secondary transition">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Create Institute</span>
            </a>
        </div>
    </div>

    {{-- 2. SIDEBAR INSTANT FILTER --}}
    <!-- <div class="relative mb-3">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-theme-text-muted text-xs pointer-events-none"></i>
        <input id="sidebarSearchInput"
               type="text"
               placeholder="Jump to menu..."
               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-sm pl-9 pr-3 py-2 rounded-full focus:outline-none">
    </div> -->

    {{-- 3. NAVIGATION ITEMS --}}
    <ul class="flex flex-col gap-1 mb-6 admin-nav">
        @foreach(config('sidebar', []) as $section)
            <li class="section-header px-3 pt-3 pb-1 text-[11px] uppercase tracking-wider text-theme-text-muted/70">
                {{ $section['section'] }}
            </li>

            @foreach($section['items'] as $item)
                @if($item['type'] === 'link')
                    @php
                        $isActive = isset($item['active']) && request()->is($item['active']);
                    @endphp
                    <li class="nav-item">
                        <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                           class="px-3.5 py-2.5 rounded-full text-sm transition flex items-center gap-2.5 {{ $isActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary/60 font-normal' }}">
                            <i class="fa-solid {{ $item['icon'] }} w-4 text-center text-sm"></i>
                            <span class="truncate">{{ $item['text'] }}</span>
                        </a>
                    </li>
                @elseif($item['type'] === 'menu')
                    @php
                        $hasActive = collect($item['links'] ?? [])->contains(function ($l) {
                            return isset($l['active']) && request()->is($l['active']);
                        });
                    @endphp
                    <li class="nav-item" x-data="{ open: {{ $hasActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="w-full px-3.5 py-2.5 rounded-full text-sm transition flex items-center justify-between {{ $hasActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary/60 font-normal' }} focus:outline-none">
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="fa-solid {{ $item['icon'] }} w-4 text-center text-sm"></i>
                                <span class="truncate">{{ $item['text'] }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs transition-transform duration-200" :class="open ? 'rotate-90' : ''"></i>
                        </button>

                        <ul x-show="open" x-collapse class="pl-4 pr-1 py-1 space-y-1">
                            @foreach($item['links'] as $link)
                                @php
                                    $isLinkActive = isset($link['active']) && request()->is($link['active']);
                                @endphp
                                <li>
                                    <a href="{{ $link['route'] === '#' ? '#' : route($link['route']) }}"
                                       class="px-3.5 py-2 rounded-full text-sm transition flex items-center gap-2.5 {{ $isLinkActive ? 'bg-secondary text-brand' : 'text-theme-text-muted hover:text-theme-text hover:bg-secondary/40 font-normal' }}">
                                        <i class="fa-solid {{ $link['icon'] }} text-xs w-4 text-center"></i>
                                        <span class="truncate">{{ $link['text'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        @endforeach
    </ul>

    {{-- 4. BOTTOM PLAN & SUBSCRIPTION WIDGET --}}
    <div class="rounded-lg bg-secondary p-4 text-xs mt-auto">
        <div class="flex items-center gap-2 mb-1.5">
            <div class="w-6 h-6 rounded-full bg-brand text-primary flex items-center justify-center text-[10px]">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <h4 class="text-xs text-theme-text">Plan & Quotas</h4>
        </div>
        <p class="text-[11px] text-theme-text-muted leading-relaxed mb-3">
            Manage your subscription, student capacity, and storage.
        </p>
        <a href="{{ route('admin.billing.index') }}"
           class="w-full py-2 bg-brand text-primary rounded-full text-center font-normal text-xs inline-flex items-center justify-center gap-1.5 hover:opacity-90 transition">
            Manage Subscription <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

</aside>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('sidebarSearchInput');
        if (!searchInput) return;

        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();
            const items = document.querySelectorAll('.admin-nav .nav-item');
            const headers = document.querySelectorAll('.admin-nav .section-header');

            if (!query) {
                items.forEach(el => el.style.display = '');
                headers.forEach(el => el.style.display = '');
                return;
            }

            headers.forEach(el => el.style.display = 'none');
            items.forEach(el => {
                const text = el.textContent.toLowerCase();
                if (text.includes(query)) {
                    el.style.display = '';
                    const parentMenu = el.closest('[x-data]');
                    if (parentMenu && parentMenu.__x) {
                        parentMenu.__x.$data.open = true;
                    }
                } else {
                    el.style.display = 'none';
                }
            });
        });
    });
</script>