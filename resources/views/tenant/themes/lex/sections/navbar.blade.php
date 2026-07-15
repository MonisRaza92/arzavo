<nav {!! $section->attributes() !!} style="border-bottom-width: {{ $section->divider }}px;"
    class="z-50 {{ $section->navbar_behavior === 'sticky' ? 'sticky top-0 left-0' : '' }} arz-border-b transition-all duration-300">

    @if($section->link_row === 'bottom')

        {{-- 🔴 TWO ROW LAYOUT --}}
        <div class="{{ $section->container }} flex items-center justify-between
                {{ $section->navbar_size === 'compact' ? 'py-2' : 'py-3.5' }}"
            style="gap: {{ $section->blocks_spacing }}px;">

            {{-- Logo LEFT --}}
            {!! $section->blocks()->only('header_logo') !!}

            {{-- Icons RIGHT --}}
            <div class="flex items-center gap-4" style="color: var(--arz-heading);">
                <a href="{{ route_to('login') }}" class="lex-nav-link" aria-label="Login">
                    <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                </a>

                <button onclick="toggleMobileMenu()" class="lg:hidden" aria-label="Open menu">
                    <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                </button>
            </div>
        </div>

        {{-- MENU BELOW --}}
        <div class="{{ $section->container }} flex {{ $section->link_justify === 'left' ? 'justify-start' : ($section->link_justify === 'center' ? 'justify-center' : 'justify-end') }} arz-border-t py-2" style="border-top-width: {{ $section->link_divider }}px;">
            {!! $section->blocks()->only('header_menu') !!}
        </div>

    @else
        <div class="{{ $section->container }} flex items-center {{ $section->navbar_size === 'compact' ? 'py-2' : 'py-3.5' }}"
            style="gap: {{ $section->blocks_spacing }}px;">

            @if ($section->logo_position === 'left')

                {!! $section->blocks()->only('header_logo') !!}

                <div
                    class="flex-1 flex items-center {{ $section->link_position === 'left' ? 'justify-start' : 'justify-end' }}">

                    {!! $section->blocks()->only('header_menu') !!}

                </div>
                <div class="flex items-center gap-4" style="color: var(--arz-heading);">
                    <a href="{{ route_to('login') }}" class="lex-nav-link" aria-label="Login">
                        <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                    </a>

                    <button onclick="toggleMobileMenu()" class="lg:hidden" aria-label="Open menu">
                        <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                    </button>
                </div>

            @elseif ($section->logo_position === 'center')

                <div class="flex-1 flex items-center justify-start" style="gap: {{ $section->blocks_spacing }}px;">
                    {!! $section->blocks()->only('header_menu') !!}
                </div>

                {!! $section->blocks()->only('header_logo') !!}

                <div class="flex-1 flex items-center justify-end gap-4" style="color: var(--arz-heading);">
                    <a href="{{ route_to('login') }}" class="lex-nav-link" aria-label="Login">
                        <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                    </a>

                    <button onclick="toggleMobileMenu()" class="lg:hidden" aria-label="Open menu">
                        <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                    </button>
                </div>

            @else

                <div class="flex-1 flex items-center justify-start" style="gap: {{ $section->blocks_spacing }}px;">
                    {!! $section->blocks()->only('header_menu') !!}
                </div>

                <div class="flex items-center gap-4">
                    {!! $section->blocks()->only('header_logo') !!}

                    <div class="flex items-center gap-4" style="color: var(--arz-heading);">
                        <a href="{{ route_to('login') }}" class="lex-nav-link" aria-label="Login">
                            <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                        </a>

                        <button onclick="toggleMobileMenu()" class="lg:hidden" aria-label="Open menu">
                            <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                        </button>
                    </div>
                </div>

            @endif

        </div>
    @endif
</nav>

{{-- Mobile Menu Drawer --}}
<div class="lex-mobile-overlay"></div>
<div class="lex-mobile-drawer">
    <div class="flex items-center justify-between mb-4">
        <div>
            @if(logo())
                <img src="{{ render_logo() }}" alt="{{ tenant_name() }}" class="arz-logo">
            @else
                <span class="tenant-name font-bold" style="font-size: 20px;">{{ tenant_name() }}</span>
            @endif
        </div>
        <button onclick="toggleMobileMenu()" class="w-10 h-10 flex items-center justify-center rounded-full" style="background: rgba(0,0,0,0.05); color: var(--arz-heading);" aria-label="Close menu">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>
    <nav>

        @php
            $menuBlock = collect($section->getBlocks())->firstWhere('type', 'header_menu');
            $menuObj = $menuBlock ? block($menuBlock) : null;
            $mobileMenuObj = $menuObj ? ($menuObj->mobileMenu ?? $menuObj->menu) : null;
        @endphp
        @if($mobileMenuObj && $mobileMenuObj->items)
            @foreach($mobileMenuObj->items as $item)
                <a href="{{ $item->url ?? $item->link ?? '#' }}" class="lex-mobile-link">
                    {{ $item->label ?? $item->name ?? '' }}
                </a>
            @endforeach
        @endif
    </nav>

    <div class="mt-8 pt-6 absolute bottom-4 left-4 right-4">
        @if (Auth::check())
            <a href="{{ route_to('login') }}" class="arz-btn-primary w-full text-center">
                <i class="fa-regular fa-user mr-2"></i> Login
            </a>
        @else
            <a href="#" class="arz-btn-primary w-full text-center">
                <i class="fa-regular fa-user mr-2"></i> Dashboard
            </a>
        @endif
    </div>
</div>

<script>
    function toggleMobileCurriculum() {
        const list = document.getElementById('mobCurriculumList');
        const arrow = document.getElementById('mobCurriculumArrow');
        if (list && arrow) {
            const isHidden = list.classList.contains('hidden');
            if (isHidden) {
                list.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                list.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
    }
</script>

