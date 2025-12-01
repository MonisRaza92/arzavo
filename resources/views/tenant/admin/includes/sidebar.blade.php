<div id="adminMobileMenu"
    class="admin-sidebar -translate-x-full lg:-translate-x-0 transition-all duration-300 ease-in-out
     w-[260px] h-full fixed top-0 left-0 overflow-y-auto p-4 pt-0 pb-10 z-9 scrollbar bg-primary border-right"
    style="margin-top: calc(var(--logo-size) + 14px);">

    {{-- =======================
         Sidebar Header
    ======================== --}}
    <div class="sticky top-0 z-9 bg-primary pt-6">

        <!-- <div class="school-coaching-name relative mb-2">
            <label class="text-[10px] absolute left-1 -top-2 px-1 text-secondary bg-primary">School/Coaching</label>
            <select class="mt-2 block w-full border-rounded p-2 text-sm border-primary uppercase" disabled>
                <option selected>{{ $settings['site_name'] ?? config('app.name') }}</option>
            </select>
        </div> -->

        <div class="search-bar relative w-full my-2">
            <input id="searchInput" type="text" placeholder="Search..."
                class="search-input w-full text-sm border-rounded px-3 py-2 input-focus border-primary">
            <button class="search-button absolute text-tertiary right-0 top-0 mt-[7px] mr-2 text-teriary">
                <i class="fas fa-search"></i>
            </button>
        </div>

    </div>

    {{-- =======================
         MAIN NAVIGATION
    ======================== --}}
    <ul class="flex flex-col gap-1 mb-12 admin-nav">

        @foreach(config('sidebar') as $section)

        {{-- SECTION TITLE --}}
        <li class="text-tertiary text-[10px] py-2 font-medium flex gap-2 items-center"><span class="shrink-0 uppercase">{{ $section['section'] }}</span> <span class="h-[2px] w-full block bg-tertiary"></span></li>

        {{-- ITEMS --}}
        @foreach($section['items'] as $item)

        {{-- =======================
                     LINK ITEMS
                ======================== --}}
        @if($item['type'] === 'link')

        <li>
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                class="block p-2 border-rounded text-sm hover:bg-gray-100
                           {{ isset($item['active']) && request()->is($item['active'])
                                ? 'bg-invert text-invert'
                                : 'text-secondary' }}">
                <i class="fa-solid {{ $item['icon'] }} mr-2"></i>
                {{ $item['text'] }}
            </a>
        </li>

        @endif

        {{-- =======================
                     DROPDOWN MENU ITEMS
                ======================== --}}
        @if($item['type'] === 'menu')

        <li>

            {{-- MENU BUTTON --}}
            <button onclick="toggleMenu('{{ $item['id'] }}', 'arrow-{{ $item['id'] }}')"
                class="block relative w-full text-left p-2 border-rounded text-secondary hover:bg-gray-100 text-sm">

                <i class="fa-solid {{ $item['icon'] }} mr-2"></i>
                {{ $item['text'] }}

                <i id="arrow-{{ $item['id'] }}"
                    class="fas fa-angle-right absolute right-2 top-1/2 transform
                               -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>

            {{-- MENU LINKS --}}
            <ul id="{{ $item['id'] }}"
                class="ml-4 pl-2 border-left overflow-hidden max-h-0
                                   transition-all duration-300 ease-linear space-y-2">

                @foreach($item['links'] as $link)

                <li>
                    <a href="{{ $link['route'] === '#' ? '#' : route($link['route']) }}"
                        class="block p-2 border-rounded text-secondary text-sm {{ isset($link['active']) && request()->is($link['active'])
                            ? 'bg-invert text-invert'
                            : 'hover:bg-gray-100' }}">
                        <i class="fa-solid {{ $link['icon'] }} mr-1"></i>
                        {{ $link['text'] }}
                    </a>
                </li>

                @endforeach

            </ul>

        </li>

        @endif

        @endforeach

        @endforeach

    </ul>

</div>
<script>
    function toggleMenu(menuId, arrowId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);

        const closed = menu.classList.contains('max-h-0');

        if (closed) {
            menu.classList.remove('max-h-0');
            menu.classList.add('max-h-200', 'pt-2');
            arrow.classList.add('rotate-90');
            localStorage.setItem(menuId, 'open');
        } else {
            menu.classList.add('max-h-0');
            menu.classList.remove('max-h-200', 'pt-2');
            arrow.classList.remove('rotate-90');
            localStorage.setItem(menuId, 'closed');
        }
    }

    document.addEventListener("DOMContentLoaded", () => {

        // Restore dropdown state
        document.querySelectorAll('.admin-nav ul[id]').forEach(menu => {
            const state = localStorage.getItem(menu.id);
            const arrow = document.getElementById('arrow-' + menu.id);

            if (state === 'open') {
                menu.classList.remove('max-h-0');
                menu.classList.add('max-h-200', 'pt-2');
                arrow?.classList.add('rotate-90');
            }
        });

        // Search filter
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const allItems = document.querySelectorAll('.admin-nav li');

            // ---------------------------------------------------
            // 1. If search empty → restore everything normally
            // ---------------------------------------------------
            if (query.length === 0) {

                allItems.forEach(li => li.style.display = '');

                // restore dropdown states
                document.querySelectorAll('.admin-nav ul[id]').forEach(menu => {
                    const arrow = document.getElementById('arrow-' + menu.id);
                    const saved = localStorage.getItem(menu.id);

                    if (saved === 'open') {
                        menu.classList.remove('max-h-0');
                        menu.classList.add('max-h-200', 'pt-2');
                        arrow?.classList.add('rotate-90');
                    } else {
                        menu.classList.add('max-h-0');
                        menu.classList.remove('max-h-200', 'pt-2');
                        arrow?.classList.remove('rotate-90');
                    }
                });

                return;
            }

            // ---------------------------------------------------
            // 2. Hide EVERYTHING first
            // ---------------------------------------------------
            allItems.forEach(li => li.style.display = 'none');

            // ---------------------------------------------------
            // 3. Match search with li items
            // ---------------------------------------------------
            document.querySelectorAll('.admin-nav li').forEach(li => {
                const text = li.textContent.toLowerCase();

                // Matched item → show
                if (text.includes(query)) {
                    li.style.display = '';

                    // If this is a submenu item → open its parent menu
                    const submenu = li.closest('ul[id]');
                    if (submenu) {
                        const parentMenuLi = submenu.closest('li');
                        const arrow = parentMenuLi.querySelector('i[id^="arrow-"]');

                        // Show parent menu li
                        parentMenuLi.style.display = '';

                        // Open menu
                        submenu.classList.remove('max-h-0');
                        submenu.classList.add('max-h-200', 'pt-2');
                        arrow?.classList.add('rotate-90');
                    }
                }
            });
        });
    });
</script>