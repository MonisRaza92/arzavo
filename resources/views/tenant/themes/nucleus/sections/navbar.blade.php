<nav {!! $section->attributes() !!} style="border-bottom-width: {{ $section->divider }}px;"
    class="z-50 {{ $section->navbar_behavior === 'sticky' ? 'sticky top-0 left-0' : '' }} arz-border-b transition-all duration-300">

    @if($section->link_row === 'bottom')

        {{-- 🔴 FORCE STRUCTURE (logo_position ignore) --}}
        <div class="{{ $section->container }} flex items-center justify-between
                {{ $section->navbar_size === 'compact' ? 'py-2' : 'py-3.5' }}"
            style="gap: {{ $section->blocks_spacing }}px;">

            {{-- Logo LEFT --}}
            {!! $section->blocks()->only('header_logo') !!}

            {{-- Icons RIGHT --}}
            <div class="flex items-center gap-4" style="color: var(--arz-heading);">
                <a href="{{ route_to('login') }}">
                    <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                </a>

                <button onclick="toggleMobileMenu()" class="lg:hidden">
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
                    <a href="{{ route_to('login') }}">
                        <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                    </a>

                    <button onclick="toggleMobileMenu()" class="lg:hidden">
                        <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                    </button>
                </div>

            @elseif ($section->logo_position === 'center')

                <div class="flex-1 flex items-center justify-start" style="gap: {{ $section->blocks_spacing }}px;">
                    {!! $section->blocks()->only('header_menu') !!}
                </div>

                {!! $section->blocks()->only('header_logo') !!}

                <div class="flex-1 flex items-center justify-end gap-4" style="color: var(--arz-heading);">
                    <a href="{{ route_to('login') }}">
                        <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                    </a>

                    <button onclick="toggleMobileMenu()" class="lg:hidden">
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
                        <a href="{{ route_to('login') }}">
                            <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
                        </a>

                        <button onclick="toggleMobileMenu()" class="lg:hidden">
                            <i class="fa-solid fa-bars" style="font-size: calc({{ $section->icon_size ?? 20 }}px + 5px);"></i>
                        </button>
                    </div>
                </div>

            @endif

        </div>
    @endif
</nav>