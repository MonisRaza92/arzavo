@extends('layouts.admin')
@section('title', 'Edit Menu: ' . $menu->name)

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary mb-6">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1">
            <i class="fa fa-link mr-1 text-base"></i> Edit Menu: {{ $menu->name }}
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Manage menu links, add sub-links, and edit properties inline</p>
    </div>

    <a href="{{ route('admin.menus.index') }}"
        class="px-3 py-2 text-sm bg-secondary text-secondary bg-hover-tertiary border-primary border-rounded flex items-center gap-1">
        <i class="fa fa-arrow-left"></i> Back to Menus
    </a>
</div>

{{-- Menu Items List Card (Full Width) --}}
<div class="bg-primary border-primary border-rounded">
    <h3 class="text-base font-bold text-primary border-bottom p-4 bg-tertiary flex justify-between items-center">
        <span>Menu Links Structure</span>
        <span class="text-[11px] text-tertiary font-normal"><i class="fa-solid fa-info-circle"></i> Drag handles <i class="fa-solid fa-up-down text-[10px]"></i> to sort</span>
    </h3>

    <div class="p-4">
        <ul id="menu-items-tree" class="space-y-1">
            @foreach($menu->items as $item)
                {{-- Render Item Row --}}
                <li class="menu-item-node" data-id="{{ $item->id }}">
                    <div id="item-view-{{ $item->id }}" class="border-bottom py-2 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="cursor-grab text-tertiary menu-item-reorder text-xs"><i class="fa-solid fa-up-down"></i></span>
                            <span class="font-semibold text-primary text-sm cursor-pointer hover:underline" onclick="startEditItem({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->link) }}')">
                                {{ $item->name }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="showAddChildRow({{ $item->id }})" class="px-2 py-1 text-xs bg-secondary text-secondary bg-hover-tertiary border border-primary/20 rounded font-semibold flex items-center gap-1" title="Add Sub-link">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add Child
                            </button>
                            <button type="button" onclick="startEditItem({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->link) }}')" class="text-xs text-tertiary hover:text-primary transition-colors p-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" onclick="deleteMenuItem({{ $item->id }})" class="text-xs text-tertiary hover:text-red-500 transition-colors p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>

                    {{-- Item Inline Editor --}}
                    <div id="item-edit-{{ $item->id }}" class="hidden pt-2 pb-4 bg-secondary/20 border-bottom rounded">
                        <div class="flex flex-wrap items-end gap-3 text-sm">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-tertiary text-xs font-semibold mb-1">Label Name</label>
                                <input type="text" id="edit-name-{{ $item->id }}" class="w-full p-2 bg-primary border-primary border-rounded text-sm">
                            </div>
                            <div id="edit-url-container-{{ $item->id }}" class="flex-1 min-w-[250px]">
                                <x-input.url name="link" label="URL / Destination" placeholder="Select or type URL..." />
                            </div>
                            <div class="flex gap-2 pb-0.5">
                                <button type="button" onclick="submitEditItem({{ $item->id }}, null)" class="px-3 py-2 bg-invert text-invert border-rounded text-xs font-semibold hover:opacity-90">Save</button>
                                <button type="button" onclick="cancelEditItem({{ $item->id }})" class="px-3 py-2 bg-secondary text-secondary bg-hover-tertiary border border-primary/20 border-rounded text-xs font-semibold">Cancel</button>
                            </div>
                        </div>
                    </div>

                    {{-- Inline Child Add Form Row (Appears right below parent) --}}
                    <div id="child-add-row-{{ $item->id }}" class="hidden pt-2 pb-4 border-bottom bg-secondary/10 rounded ml-8">
                        <div class="flex flex-wrap items-end gap-3 text-sm">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-tertiary text-xs font-semibold mb-1">Child Label Name</label>
                                <input type="text" id="child-add-name-{{ $item->id }}" placeholder="e.g. Physics" class="w-full p-2 bg-primary border-primary border-rounded text-sm">
                            </div>
                            <div id="child-add-url-container-{{ $item->id }}" class="flex-1 min-w-[250px]">
                                <x-input.url name="link" label="URL / Destination" placeholder="Select or type URL..." />
                            </div>
                            <div class="flex gap-2 pb-0.5">
                                <button type="button" onclick="submitAddChild({{ $item->id }})" class="px-3 py-2 bg-invert text-invert border-rounded text-xs font-semibold hover:opacity-90">Save</button>
                                <button type="button" onclick="hideAddChildRow({{ $item->id }})" class="px-3 py-2 bg-secondary text-secondary bg-hover-tertiary border border-primary/20 border-rounded text-xs font-semibold">Cancel</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Children Nested List --}}
                    <ul class="nested-list pl-8 space-y-1" data-parent-id="{{ $item->id }}">
                        @foreach($item->children as $child)
                            <li class="menu-item-node" data-id="{{ $child->id }}">
                                <div id="item-view-{{ $child->id }}" class="py-2 border-bottom flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="cursor-grab text-tertiary menu-item-reorder text-xs"><i class="fa-solid fa-up-down"></i></span>
                                        <span class="font-normal text-primary text-sm cursor-pointer hover:underline" onclick="startEditItem({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ addslashes($child->link) }}')">
                                            {{ $child->name }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="startEditItem({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ addslashes($child->link) }}')" class="text-xs text-tertiary hover:text-primary transition-colors p-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button type="button" onclick="deleteMenuItem({{ $child->id }})" class="text-xs text-tertiary hover:text-red-500 transition-colors p-1" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>

                                {{-- Child Inline Editor --}}
                                <div id="item-edit-{{ $child->id }}" class="hidden py-2 bg-secondary/20 px-3 rounded-md">
                                    <div class="flex flex-wrap items-end gap-3 text-sm">
                                        <div class="flex-1 min-w-[200px]">
                                            <label class="block text-tertiary text-xs font-semibold mb-1">Label Name</label>
                                            <input type="text" id="edit-name-{{ $child->id }}" class="w-full p-2 bg-primary border-primary border-rounded text-sm">
                                        </div>
                                        <div id="edit-url-container-{{ $child->id }}" class="flex-1 min-w-[250px]">
                                            <x-input.url name="link" label="URL / Destination" placeholder="Select or type URL..." />
                                        </div>
                                        <div class="flex gap-2 pb-0.5">
                                            <button type="button" onclick="submitEditItem({{ $child->id }}, {{ $item->id }})" class="px-3 py-2 bg-invert text-invert border-rounded text-xs font-semibold hover:opacity-90">Save</button>
                                            <button type="button" onclick="cancelEditItem({{ $child->id }})" class="px-3 py-2 bg-secondary text-secondary bg-hover-tertiary border border-primary/20 border-rounded text-xs font-semibold">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

        {{-- Row for Adding Top Level Link --}}
        <div id="top-add-row" class="hidden pt-2 pb-4 border-bottom bg-secondary/15 rounded mt-4">
            <div class="flex flex-wrap items-end gap-3 text-sm">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-tertiary text-xs font-semibold mb-1">Link Label Name</label>
                    <input type="text" id="top-add-name" placeholder="e.g. About Us" class="w-full p-2 bg-primary border-primary border-rounded text-sm">
                </div>
                <div id="top-add-url-container" class="flex-1 min-w-[250px]">
                    <x-input.url name="link" label="URL / Destination" placeholder="Select or type URL..." />
                </div>
                <div class="flex gap-2 pb-0.5">
                    <button type="button" onclick="submitAddTopLink()" class="px-4 py-2.5 bg-invert text-invert border-rounded text-sm font-semibold hover:opacity-90">Save</button>
                    <button type="button" onclick="hideTopAddRow()" class="px-4 py-2.5 bg-secondary text-secondary bg-hover-tertiary border border-primary/20 border-rounded text-sm font-semibold">Cancel</button>
                </div>
            </div>
        </div>

        @if(!$menu->items->count())
            <div id="empty-state" class="text-center py-8 text-sm text-tertiary">
                No links in this menu yet. Click "Add New Link" below to get started.
            </div>
        @endif

        {{-- Add New Link Button --}}
        <div class="mt-4 pt-2">
            <button type="button" onclick="showTopAddRow()" class="px-4 py-2 bg-secondary text-secondary bg-hover-tertiary border border-primary/20 border-rounded text-sm font-semibold flex items-center gap-1">
                <i class="fa-solid fa-plus text-xs"></i> Add New Link
            </button>
        </div>
    </div>
</div>

<script>
    let sortableInstances = [];

    // Helper to reload the Menu Tree HTML via AJAX without page reload
    function reloadMenuTree() {
        return fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTree = doc.getElementById('menu-items-tree');
                const oldTree = document.getElementById('menu-items-tree');
                if (newTree && oldTree) {
                    oldTree.innerHTML = newTree.innerHTML;
                }
                
                // Hide empty state if tree has children
                const emptyState = document.getElementById('empty-state');
                if (emptyState) {
                    const hasItems = document.querySelectorAll('#menu-items-tree > li').length > 0;
                    if (hasItems) {
                        emptyState.classList.add('hidden');
                    } else {
                        emptyState.classList.remove('hidden');
                    }
                }

                // Re-initialize Sortable
                initSortable();
            });
    }

    // -------------------------------------------------------------
    // Inline Editor Handlers
    // -------------------------------------------------------------
    function startEditItem(itemId, name, currentLink) {
        cancelAllEditing();

        document.getElementById(`item-view-${itemId}`).classList.add('hidden');
        document.getElementById(`item-edit-${itemId}`).classList.remove('hidden');

        document.getElementById(`edit-name-${itemId}`).value = name;
        
        const urlInput = document.querySelector(`#edit-url-container-${itemId} input`);
        if (urlInput) {
            urlInput.value = currentLink;
        }
    }

    function cancelEditItem(itemId) {
        document.getElementById(`item-view-${itemId}`).classList.remove('hidden');
        document.getElementById(`item-edit-${itemId}`).classList.add('hidden');
    }

    function submitEditItem(itemId, parentId) {
        const name = document.getElementById(`edit-name-${itemId}`).value.trim();
        const urlInput = document.querySelector(`#edit-url-container-${itemId} input`);
        const link = urlInput ? urlInput.value.trim() : '';

        if (!name) {
            alert('Please enter a link name');
            return;
        }

        // Target button and set "Saving..." status
        const btn = document.querySelector(`#item-edit-${itemId} button[onclick^="submitEditItem"]`);
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Saving...';

        fetch(`/admin/menu-items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                link: link,
                parent_id: parentId
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Update failed');
            return res.json();
        })
        .then(() => {
            cancelAllEditing();
            return reloadMenuTree();
        })
        .catch(err => {
            alert('Failed to update link');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
    }

    // -------------------------------------------------------------
    // Inline Child Link Form Handlers
    // -------------------------------------------------------------
    function showAddChildRow(parentId) {
        cancelAllEditing();
        document.getElementById(`child-add-row-${parentId}`).classList.remove('hidden');
        
        document.getElementById(`child-add-name-${parentId}`).value = '';
        const urlInput = document.querySelector(`#child-add-url-container-${parentId} input`);
        if (urlInput) {
            urlInput.value = '';
        }
    }

    function hideAddChildRow(parentId) {
        document.getElementById(`child-add-row-${parentId}`).classList.add('hidden');
    }

    function submitAddChild(parentId) {
        const name = document.getElementById(`child-add-name-${parentId}`).value.trim();
        const urlInput = document.querySelector(`#child-add-url-container-${parentId} input`);
        const link = urlInput ? urlInput.value.trim() : '';

        if (!name) {
            alert('Please enter a link name');
            return;
        }

        // Target button and set "Saving..." status
        const btn = document.querySelector(`#child-add-row-${parentId} button[onclick^="submitAddChild"]`);
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Saving...';

        fetch('{{ route("admin.menu-items.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                menu_id: {{ $menu->id }},
                name: name,
                link: link,
                parent_id: parentId
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Create failed');
            return res.json();
        })
        .then(() => {
            cancelAllEditing();
            return reloadMenuTree();
        })
        .catch(err => {
            alert('Failed to add sub-link');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
    }

    // -------------------------------------------------------------
    // Inline Top-Level Link Form Handlers
    // -------------------------------------------------------------
    function showTopAddRow() {
        cancelAllEditing();
        document.getElementById('top-add-row').classList.remove('hidden');
        if (document.getElementById('empty-state')) {
            document.getElementById('empty-state').classList.add('hidden');
        }
        document.getElementById('top-add-name').value = '';
        const urlInput = document.querySelector('#top-add-url-container input');
        if (urlInput) {
            urlInput.value = '';
        }
    }

    // Helper to close all forms before showing a new one
    function cancelAllEditing() {
        document.querySelectorAll('[id^="item-edit-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="item-view-"]').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('[id^="child-add-row-"]').forEach(el => el.classList.add('hidden'));
        hideTopAddRow();
    }

    function hideTopAddRow() {
        document.getElementById('top-add-row').classList.add('hidden');
        if (document.getElementById('empty-state') && document.querySelectorAll('#menu-items-tree > li').length === 0) {
            document.getElementById('empty-state').classList.remove('hidden');
        }
    }

    function submitAddTopLink() {
        const name = document.getElementById('top-add-name').value.trim();
        const urlInput = document.querySelector('#top-add-url-container input');
        const link = urlInput ? urlInput.value.trim() : '';

        if (!name) {
            alert('Please enter a link name');
            return;
        }

        // Target button and set "Saving..." status
        const btn = document.querySelector('#top-add-row button[onclick^="submitAddTopLink"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Saving...';

        fetch('{{ route("admin.menu-items.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                menu_id: {{ $menu->id }},
                name: name,
                link: link,
                parent_id: null
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Create failed');
            return res.json();
        })
        .then(() => {
            cancelAllEditing();
            return reloadMenuTree();
        })
        .catch(err => {
            alert('Failed to add link');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = originalText;
        });
    }

    // -------------------------------------------------------------
    // Delete Menu Item
    // -------------------------------------------------------------
    function deleteMenuItem(itemId) {
        if (!confirm('Are you sure you want to delete this menu item and all its sub-links?')) {
            return;
        }

        fetch(`/admin/menu-items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Delete failed');
            return reloadMenuTree();
        })
        .catch(err => {
            alert('Failed to delete item');
            console.error(err);
        });
    }

    // -------------------------------------------------------------
    // Reorder Menu Items
    // -------------------------------------------------------------
    function saveItemsOrder() {
        const order = [];
        let index = 1;

        document.querySelectorAll('#menu-items-tree > li').forEach((topLi) => {
            const topId = topLi.dataset.id;
            if (topId) {
                order.push({ id: topId, order: index++ });
            }

            topLi.querySelectorAll('.nested-list > li').forEach((childLi) => {
                const childId = childLi.dataset.id;
                if (childId) {
                    order.push({ id: childId, order: index++ });
                }
            });
        });

        fetch(`/admin/menu-items/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                menu_id: {{ $menu->id }},
                order: order
            })
        })
        .then(res => res.json())
        .catch(err => {
            alert('Failed to save order');
            console.error(err);
        });
    }

    // Initialize Sortable on drag-and-drop lists
    function initSortable() {
        sortableInstances.forEach(inst => {
            try { inst.destroy(); } catch(e){}
        });
        sortableInstances = [];

        if (typeof Sortable === 'undefined') return;

        const tree = document.getElementById('menu-items-tree');
        if (tree) {
            const topInst = new Sortable(tree, {
                animation: 150,
                handle: '.menu-item-reorder',
                ghostClass: 'opacity-50',
                onEnd: function() {
                    saveItemsOrder();
                }
            });
            sortableInstances.push(topInst);
        }

        document.querySelectorAll('.nested-list').forEach(list => {
            const childInst = new Sortable(list, {
                animation: 150,
                handle: '.menu-item-reorder',
                ghostClass: 'opacity-50',
                onEnd: function() {
                    saveItemsOrder();
                }
            });
            sortableInstances.push(childInst);
        });
    }

    document.addEventListener('turbo:load', initSortable);
    
    // Fallback initialization if page is loaded normally
    document.addEventListener('DOMContentLoaded', initSortable);
    </script>
@endsection
