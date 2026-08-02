@extends('layouts.admin')
@section('title', 'Menus Manage')

@section('content')
@include('tenant.admin.menus.partials.header')

<div class="bg-primary border-rounded border-primary mt-4">
    <table class="w-full">
        <thead>
            <tr class="bg-tertiary">
                <th class="p-2 pl-4 text-left">Menu Name</th>
                <th class="p-2 text-left">Slug / Location Code</th>
                <th class="p-2 text-center">Links Count</th>
                <th class="p-2 text-right pr-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr class="border-top">
                <td class="p-2 pl-4 text-left align-middle">
                    <div class="flex items-center gap-1">
                        <span id="menu-name-text-{{ $menu->id }}" class="font-semibold text-primary text-sm">{{ $menu->name }}</span>
                        <input type="text" id="menu-name-input-{{ $menu->id }}" value="{{ $menu->name }}" class="hidden p-1 text-xs border-primary border-rounded bg-primary text-primary" style="max-width: 150px;">
                        <button type="button" onclick="toggleMenuEdit({{ $menu->id }})" id="menu-edit-btn-{{ $menu->id }}" class="text-tertiary hover:text-primary transition-colors p-1" title="Edit Name">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </button>
                    </div>
                </td>
                <td class="p-2 text-left">/{{ $menu->slug }}</td>
                <td class="p-2 text-center">{{ $menu->allItems->count() }}</td>
                <td class="p-2 text-right pr-4">
                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-sm text-tertiary mr-2" title="Manage Menu Links">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this menu?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="text-sm text-tertiary" title="Delete Menu">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="border-top">
                <td colspan="4" class="p-4 text-center text-tertiary text-sm">
                    No menus created yet. Click "Add New" to get started.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleMenuEdit(menuId) {
        const text = document.getElementById(`menu-name-text-${menuId}`);
        const input = document.getElementById(`menu-name-input-${menuId}`);
        const btn = document.getElementById(`menu-edit-btn-${menuId}`);
        const icon = btn.querySelector('i');

        const isEditing = !input.classList.contains('hidden');

        // 👉 ENTER EDIT MODE
        if (!isEditing) {
            text.classList.add('hidden');
            input.classList.remove('hidden');
            input.focus();
            icon.classList.remove('fa-pen');
            icon.classList.add('fa-check');
            return;
        }

        // 👉 VALIDATION
        if (!input.value.trim()) {
            alert('Menu name cannot be empty');
            input.focus();
            return;
        }

        // 👉 SAVE
        fetch(`{{ url('/admin/menus') }}/${menuId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: input.value.trim()
                })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Request failed');
                }
                return res.json();
            })
            .then(data => {
                text.innerText = data.name;
                input.value = data.name;

                // exit edit mode
                text.classList.remove('hidden');
                input.classList.add('hidden');
                icon.classList.remove('fa-check');
                icon.classList.add('fa-pen');
            })
            .catch(err => {
                alert('Failed to update menu name');
                console.error(err);
            });
    }
</script>
@endsection
