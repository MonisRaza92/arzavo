<div class="bg-primary p-2 space-y-1">
    <ul class="space-y-1 text-sm relative overflow-hidden" id="menu-items-list-{{ $menu->id }}" data-menu-id="{{ $menu->id }}">

        @foreach($menu->items as $item)
        <li class="flex items-center justify-between border-primary bg-hover-secondary px-4 py-3 border-rounded"
            data-item-id="{{ $item->id }}">
            <span class="item-text">{{ $item->name }}</span>

            <div class="flex items-center gap-3 text-xs">
                <button class="text-tertiary text-hover-primary menu-item-reorder">
                    <i class="fa-solid fa-up-down"></i>
                </button>

                <button onclick="deleteMenuItem({{ $item->id }}, this)"
                    class="text-tertiary text-hover-primary">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </li>
        @endforeach
    </ul>
    {{-- ADD BUTTON --}}
    <button
        type="button"
        onclick="addMenuItemRow({{ $menu->id }})"
        class="text-blue-500 hover:text-blue-700 w-full border-rounded border-primary flex items-center gap-1 px-4 py-2.75 bg-hover-secondary text-sm">
        <i class="fa-regular fa-square-plus text-[13px]"></i>
        Add new
    </button>

</div>
<script>
    function addMenuItemRow(menuId) {
        const list = document.getElementById(`menu-items-list-${menuId}`);

        const li = document.createElement('li');
        li.className = "list-none";

        li.innerHTML = `
        <div class="bg-hover-primary border-rounded flex items-center gap-2">

            <input
                type="text"
                placeholder="Name"
                class="text-sm border-primary px-4 py-3 w-[calc(40%-4px)] border-rounded item-name">

            <input
                type="text"
                placeholder="Link"
                class="text-sm border-primary px-4 py-3 w-[calc(40%-4px)] border-rounded item-link">

            <button
                onclick="saveMenuItem(${menuId}, this)"
                class="text-sm font-medium text-invert bg-invert w-[calc(20%-4px)] py-3 text-center border-primary border-rounded">
                Save
            </button>

        </div>
    `;

        list.append(li);
    }


    function saveMenuItem(menuId, btn) {
        const li = btn.closest('li');
        const name = li.querySelector('.item-name').value.trim();
        const link = li.querySelector('.item-link').value.trim();

        if (!name) {
            alert('Name is required');
            return;
        }

        fetch("{{ route('admin.menu-items.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    name: name,
                    link: link,
                })
            })
            .then(res => res.json())
            .then(data => {
                li.outerHTML = `
<li class="flex items-center justify-between border-primary bg-hover-secondary px-4 py-3 border-rounded"
    data-item-id="${data.id}">

        <span class="item-text">${data.name}</span>

    <div class="flex items-center gap-3 text-xs">      
        <button class="text-tertiary text-hover-primary menu-item-reorder">
            <i class="fa-solid fa-up-down"></i>
        </button>

        <button onclick="deleteMenuItem(${data.id}, this)"
            class="text-tertiary text-hover-primary">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>
</li>
`;
            })
            .catch(() => alert('Failed to save menu item'));
    }

    function deleteMenuItem(itemId, btn) {
        fetch(`/admin/menu-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Delete failed');
                }
                // remove li from UI
                const li = btn.closest('li');
                li.remove();
            })
            .catch(() => {
                alert('Failed to delete menu item');
            });
    }

    document.addEventListener('turbo:load', function() {

        document.querySelectorAll('[id^="menu-items-list-"]').forEach(list => {

            new Sortable(list, {
                animation: 150,
                handle: '.menu-item-reorder', // drag icon
                ghostClass: 'opacity-50',
                chosenClass: 'bg-hover-primary',

                onEnd: function() {
                    saveMenuOrder(list);
                }
            });

        });

        function saveMenuOrder(list) {
            const menuId = list.dataset.menuId;

            const order = Array.from(list.children)
                .filter(li => li.dataset.itemId)
                .map((li, index) => ({
                    id: li.dataset.itemId,
                    order: index + 1
                }));

            fetch(`/admin/menu-items/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        menu_id: menuId,
                        order: order
                    })
                })
                .catch(() => {
                    alert('Failed to save menu order');
                });
        }

    });
</script>
