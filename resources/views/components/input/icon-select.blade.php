@props(['name', 'value' => 'none'])

@php
    $icons = config('icons');
    $uid = 'icon_' . md5($name . rand());
@endphp

<div class="relative w-full">

    <!-- SELECTED -->
    <button type="button" onclick="document.getElementById('{{ $uid }}_dropdown').classList.toggle('hidden')"
        class="w-full flex items-center justify-between border-primary border-rounded p-2 text-xs bg-primary">

        <span class="flex items-center gap-2">
            @if ($value !== 'none')
                <i class="fa-solid fa-{{ $value }}"></i>
            @endif
            {{ $value }}
        </span>

        <i class="fa-solid fa-angle-down text-xs"></i>
    </button>

    <!-- DROPDOWN -->
    <div id="{{ $uid }}_dropdown"
        class="hidden absolute z-50 mt-1 w-60 right-0 max-h-60 overflow-auto bg-primary border-primary border-rounded shadow-lg p-2">

        <input type="text" placeholder="Search icon..." class="w-full mb-2 p-1 text-xs border-rounded border-primary"
            oninput="filterIcons('{{ $uid }}',this.value)">

        <div id="{{ $uid }}_list" class="grid grid-cols-3 gap-2">

            @foreach ($icons as $icon)
                <button type="button" onclick="selectIcon('{{ $uid }}','{{ $icon }}')"
                    data-icon="{{ $icon }}"
                    class="flex flex-col items-center gap-1 p-2 border-rounded hover:bg-hover-secondary text-xs">

                    @if ($icon !== 'none')
                        <i class="fa-solid fa-{{ $icon }} text-base"></i>
                    @endif

                    <span class="truncate w-full text-center">{{ $icon }}</span>

                </button>
            @endforeach

        </div>

    </div>

    <!-- HIDDEN INPUT -->
    <input type="hidden" name="{{ $name }}" id="{{ $uid }}_input" value="{{ $value }}">

</div>


@once
    <script>
        function selectIcon(uid, icon) {

            const input = document.getElementById(uid + '_input');
            const dropdown = document.getElementById(uid + '_dropdown');

            if (!input) return;

            /* set value */
            input.value = icon;

            /* fire builder events */
            input.dispatchEvent(new Event('input', {
                bubbles: true
            }));
            input.dispatchEvent(new Event('change', {
                bubbles: true
            }));

            /* update button label safely */
            const wrapper = input.closest('.relative');
            const btn = wrapper.querySelector('button');

            if (btn) {
                btn.innerHTML = `
            <span class="flex items-center gap-2">
                ${icon!=='none'?'<i class="fa-solid fa-'+icon+'"></i>':''}
                ${icon}
            </span>
            <i class="fa-solid fa-angle-down text-xs"></i>
        `;
            }

            /* close dropdown */
            if (dropdown) dropdown.classList.add('hidden');
        }


        function filterIcons(uid, term) {

            term = term.toLowerCase();

            document.querySelectorAll('#' + uid + '_list [data-icon]').forEach(el => {
                const name = el.dataset.icon.toLowerCase();
                el.style.display = name.includes(term) ? 'flex' : 'none';
            });

        }
    </script>
@endonce
