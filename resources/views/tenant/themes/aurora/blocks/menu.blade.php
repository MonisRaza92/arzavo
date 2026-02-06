@php
    $s = $block['settings'] ?? [];

    $menuId = $s['menu_id'] ?? 1;
    $separatorMobileMenu = $s['separate_mobile_menu'] ?? 1;
    $mobileMenuId = $s['mobile_menu_id'] ?? 1;
    $itemSpacing = $s['item_spacing'] ?? 16;
    $fontSize = $s['text_size'] ?? 'small';
    $dropdownStyle = $s['dropdown_style'] ?? 'hover';
    $textTransform = $s['text_transform'] ?? 'capitalize';
    $fontWeight = $s['font_weight'] ?? 'normal';
    $pl = $s['padding_left'] ?? '16';
    $pr = $s['padding_right'] ?? '16';
    $iconStyle = $s['icon_style'] ?? 'regular';

    $menu = $menus->firstWhere('id', $menuId);
    if ($separatorMobileMenu === "1") {
        $mobileMenu = $menus->firstWhere('id', $mobileMenuId);
    } else {
        $mobileMenu = $menu;
    }

    $size = match ($fontSize) {
        'small' => 'text-sm',
        'medium' => 'text-base',
        'large' => 'text-lg'
    };

@endphp

@if($menu)
    <div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}">
        <ul class="hidden md:flex
        font-{{ $fontWeight }}"
            style="text-transform: {{ $textTransform }}; gap: {{ $itemSpacing }}px; padding-left: {{ $pl }}px; padding-right: {{ $pr }}px;">

            @foreach($menu->items as $item)
                <li class="relative group">
                    <a href="{{ $item->link }}" class="inline-flex items-center {{ $size }} arzavo-link arzavo-menu" @if ($item->children->count()) onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul class="absolute top-full left-0 hidden group-hover:block min-w-48 arzavo-border border-rounded mt-2"
                            style="background: var(--arzavo-background)">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arzavo-link">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        <button class="text-xl md:hidden" onclick="toggleMobileMenu()"><i class="fa-solid fa-bars"></i></button>
        <ul class="flex md:hidden flex-col fixed right-0 transform translate-x-full transition-all duration-300 top-0 w-3/4 h-dvh arzavo-background z-30 overflow-y-auto scrollbar"
            id="mobileMenu">
            <div class="header flex px-4 py-3.5 justify-between arzavo-border-bottom">
                <img src="{{ media($customizes['logo'] ?? '') }}" alt="logo" class="h-8 shrink-0">
                <button class="text-2xl" onclick="toggleMobileMenu()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @foreach($mobileMenu->items as $item)
                <li class="relative group px-4 py-3 arzavo-border-bottom">
                    <a href="{{ $item->link }}" class="inline-flex items-center arzavo-link {{ $size }}" @if ($item->children->count()) onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul
                            class="absolute top-full left-0 hidden group-hover:block min-w-48 arzavo-border border-rounded mt-2 arzavo-background">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arzavo-link">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            <div class="absolute p-4 arzavo-border-top bottom-0 left-0 w-full">
                @if (!Auth::guard('tenant')->check())
                    <a href="{{ route('tenant.login') }}"><i class="fa-{{ $iconStyle }} fa-user text-xl"></i></a>
                @else
                    <div class="menu relative" onclick="toggleModel('authMenuMobile')">
                        <i class="fa-{{ $iconStyle }} fa-user text-xl"></i>
                        <div class="auth-menu hidden arzavo-background absolute bottom-full left-0 border-rounded border-primary min-w-50"
                            id="authMenuMobile">
                            <div class=" user-info arzavo-border-bottom py-2 px-4">
                                <h4 class="text-base font-semibold">{{ $user->fname ?? 'Guest' }} {{ $user->lname ?? '' }}
                                </h4>
                                <p class="text-xs">{{ $user->email ?? 'N/A' }}</p>
                            </div>
                            <div class="links py-2 px-4 space-y-2">
                                <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-user"></i>Profile</a>
                                <a href="" class="flex gap-2 items-center"><i
                                        class="fa-solid fa-bars-progress"></i>Dashboard</a>
                                <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-video"></i>Courses</a>
                                <a href="" class="flex gap-2 items-center"><i class="fa-solid fa-file-pdf"></i>Notes
                                    & Book</a>
                                <a href="" class="flex gap-2 items-center text-red-500 arzavo-border-top pt-2"><i
                                        class="fa-solid fa-right-from-bracket"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </ul>
    </div>
@endif
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById("mobileMenu");

        if (menu.classList.contains("hidden")) {
            // Show
            menu.classList.remove("hidden");

            // force reflow so transition works
            menu.offsetHeight;

            menu.classList.remove("translate-x-full");
            menu.classList.add("translate-x-0");
            menu.classList.add("shadow-2xl");
        } else {
            // Hide
            menu.classList.add("translate-x-full");
            menu.classList.remove("shadow-2xl");

            // after animation, hide
            setTimeout(() => {
                menu.classList.add("hidden");
                menu.classList.remove("translate-x-0");
            }, 300); // must match transition duration
        }
    }
</script>