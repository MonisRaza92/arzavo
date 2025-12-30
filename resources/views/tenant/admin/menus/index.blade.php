@extends('layouts.admin')
@section('title', 'Menus Manage')

@section('content')
@include('tenant.admin.menus.partials.header')

@if($menus->count())
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">

    @foreach($menus as $menu)
    <div class="bg-primary border-rounded border-primary overflow-hidden">

        <div class="flex items-center justify-between p-4 border-bottom">

            {{-- LEFT: NAME / INPUT --}}
            <div class="flex items-center gap-2 w-full">

                <span
                    id="menu-name-text-{{ $menu->id }}"
                    class="font-semibold text-peimary text-sm">
                    {{ $menu->name }}
                </span>

                <input
                    type="text"
                    id="menu-name-input-{{ $menu->id }}"
                    value="{{ $menu->name }}"
                    name="name"
                    class="hidden text-sm border-primary px-2 py-1 border-rounded w-full max-w-50" />
            </div>

            {{-- RIGHT: ACTIONS --}}
            <div class="flex items-center gap-3 ml-4">

                {{-- EDIT / SAVE --}}
                <button
                    type="button"
                    onclick="toggleMenuEdit({{ $menu->id }})"
                    class="text-tertiary text-hover-primary"
                    id="menu-edit-btn-{{ $menu->id }}"
                    title="Edit name">
                    <i class="fa-solid fa-edit"></i>
                </button>

                {{-- DELETE --}}
                <form
                    action="{{ route('admin.menus.destroy', $menu->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        onclick="return confirm('Delete this menu?')"
                        class="text-tertiary text-hover-primary"
                        title="Delete menu">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>

            </div>
        </div>
        @include('tenant.admin.menus.partials.menu-items')
    </div>
    @endforeach

</div>
@else
<div class="flex items-center justify-center p-4">
    <p class="text-sm text-tertiary">
        No menus created yet.
    </p>
</div>
@endif
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

        // 👉 VALIDATION (FIXED PLACE)
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