@if($block->menu)
    <div {!! $block->attributes() !!} class="w-auto" style="{{ $block->spacing }}">
        <ul class=" hidden md:flex font-{{ $block->font_weight }}" style="{{ $block->flexStyle . $block->spacing }}">

            @foreach($block->menu->items as $item)
                <li class="relative group">
                    <a href="{{ $item->link }}" class="inline-flex items-center arz-link" @if ($item->children->count())
                    onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul class="absolute top-full left-0 hidden group-hover:block min-w-48 border-rounded mt-2 arz-background">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arz-link">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        <ul class="flex md:hidden flex-col fixed right-0 border-0 transform translate-x-full hidden transition-all duration-300 ease-out top-0 w-3/4 h-dvh arzavo-background z-30 overflow-y-auto scrollbar"
            id="mobileMenu">
            @foreach($block->mobileMenu->items as $item)
                <li class="relative group px-4 py-3 arzavo-border-bottom">
                    <a href="{{ $item->link }}" class="inline-flex items-center arz-link" @if ($item->children->count())
                    onclick="event.preventDefault()" @endif>
                        {{ $item->name }}
                    </a>

                    @if($item->children->count())
                        <ul
                            class="absolute top-full left-0 hidden group-hover:block min-w-48 arzavo-border border-rounded mt-2 arzavo-background">
                            @foreach($item->children->where('parent_id', $item->id) as $child)
                                <li>
                                    <a href="{{ $child->link }}" class="block px-4 py-2 arz-link">
                                        {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            <a href="{{ route_to('login') }}" class="absolute bottom-0 left-0 w-full p-4 arz-border-t" style="color: var(--arz-heading);">
                <i class="fa-regular fa-user" style="font-size: {{ $section->icon_size ?? 20 }}px;"></i>
            </a>
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

    document.addEventListener("click", function (event) {
        const menu = document.getElementById("mobileMenu");
        const menuToggle = document.querySelector('[onclick*="toggleMobileMenu"]');

        if (!menu.classList.contains("hidden") && !menu.contains(event.target) && !menuToggle?.contains(event.target)) {
            toggleMobileMenu();
        }
    });
</script>