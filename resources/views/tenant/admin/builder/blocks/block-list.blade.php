<li id="block-{{ $block->id }}" class="block-item"
    data-block-id="{{ $block->id }}">
    <div class="bg-hover-secondary relative group/block border-rounded cursor-pointer select-none py-0.5 px-1 mt-1 flex justify-between items-center">

        @php
        $blockRules = collect($availableBlocks)->mapWithKeys(function($block) {
        return [
        $block['type'] => [
        'max_blocks' => $block['max_blocks'] ?? null,
        'allowed_blocks' => $block['allowed_blocks'] ?? [],
        'moveable' => $block['moveable'] ?? "allow"
        ]
        ];
        });
        @endphp
        <div class="flex items-center grow">

            {{-- NESTED TOGGLER --}}
            @if (!empty($blockRules[$block->type]['allowed_blocks']))
            <button type="button" id="block-btn-{{ $block->id }}"
                class="text-tertiary bg-hover-secondary pt-0.5 pb-1.5 px-1.5 mr-1 border-rounded toggle-block-btn"
                data-id="{{ $block->id }}">
                <i id="block-btn-arrow-{{ $block->id }}" class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
            @else
            <span class="w-8"></span>
            @endif
            <span class="text-sm cursor-pointer block-open-btn w-full" data-block-id="{{ $block->id }}"><i class="fa-solid {{ $block->icon ?? 'fa-shapes' }} text-xs mr-2 text-tertiary"></i>{{ $block->name }}</span>
        </div>

        <div class="flex items-center opacity-0 pointer-events-none group-hover/block:opacity-100 group-hover/block:pointer-events-auto transition-all duration-200">
            @php
            $blockRule = $blockRules[$block->type] ?? null;
            $moveable = $blockRule['moveable'] ?? "allow";
            @endphp
            @if ($moveable === "allow")
            <button class="cursor-drag text-hover-primary text-tertiary text-xs py-2 px-1 border-rounded block-drag-handle">
                <i class="fa-solid fa-up-down"></i>
            </button>
            @else
            <i class="fa-solid fa-lock text-tertiary text-xs my-2 mx-1"></i>
            @endif
            {{-- ACTIVE/INACTIVE --}}
            <button type="button"
                class="toggle-block-active text-tertiary text-[13px] text-hover-primary py-2 px-1 border-rounded"
                data-block-id="{{ $block->id }}">
                @if($block->is_active)
                <i class="fa-solid fa-eye"></i>
                @else
                <i class="fa-solid fa-eye-slash"></i>
                @endif
            </button>

            {{-- DELETE --}}
            <form class="delete-block-form" data-block-id="{{ $block->id }}"
                action="{{ route('admin.builder.sections.blocks.destroy', $block->id) }}"
                method="POST">
                @csrf
                @method('DELETE')

                <button type="button"
                    class="delete-block-btn text-tertiary text-hover-primary py-2 px-1 border-rounded text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

        </div>
        @include('tenant.admin.builder.blocks.block-edit')
    </div>

    {{-- Nested Blocks --}}
    <div id="nested-blocks-{{ $block->id }}" class="ml-[21px] hidden">
        {{-- List of blocks --}}
        <ul class="nested-block-list" id="nested-block-list-{{ $block->id }}">
            @foreach($block->children as $child)
            @include('tenant.admin.builder.blocks.nested-block', ['block' => $child])
            @endforeach
        </ul>
        @php
        $blockRule = $blockRules[$block->type] ?? null;
        $maxNestedBlocks = $blockRule['max_blocks'] ?? null;
        $currentNestedBlockCount = $block->children->count();
        @endphp
        @if(is_null($maxNestedBlocks) || $currentNestedBlockCount < $maxNestedBlocks)
            <button type="button"
            class="text-blue-600 text-left text-sm bg-hover-secondary mt-1 w-full block p-2 border-rounded"
            onclick="openAddNestedBlock({{ $block->id }})">
            <i class="fa-regular fa-square-plus mr-1 ml-6 text-[13px]"></i> Add Block
            </button>
            @endif
    </div>
    @include('tenant.admin.builder.blocks.nested-block-add')
</li>


<script>
    // Open Add Block Modal
    function openAddNestedBlock(blockId) {
        const container = document.getElementById(`addNestedBlockContainer${blockId}`);
        if (container) {
            container.classList.remove("hidden");
        }
    }
    // ---------------------------------------------
    // BLOCK MANAGEMENT SCRIPTS - ADD ONCE
    // ---------------------------------------------
    (function() {
        'use strict';

        // Prevent multiple initializations
        if (window.blockScriptsInitialized) {
            return; // Silently exit if already initialized
        }
        window.blockScriptsInitialized = true;

        // Nested Block list Open Function
        const nestedBlocksState = JSON.parse(localStorage.getItem("nestedBlocksState") || "{}");

        // Restore saved state on page load
        document.addEventListener("turbo:load", function() {
            document.querySelectorAll("[id^='block-btn-']").forEach(el => {
                const blockId = el.id.replace("block-btn-", "");
                const arrow = document.getElementById("block-btn-arrow-" + blockId);
                const container = document.getElementById(`nested-blocks-${blockId}`);

                if (nestedBlocksState[blockId]) {
                    container?.classList.remove("hidden");
                    arrow?.classList.add("rotate-90");
                }
            });
        });

        // Toggle blocks inside section + save state
        document.addEventListener("click", function(e) {
            const btn = e.target.closest(".toggle-block-btn");
            if (!btn) return;

            e.stopPropagation();

            const blockId = btn.dataset.id;
            const container = document.getElementById(`nested-blocks-${blockId}`);
            const arrow = document.getElementById(`block-btn-arrow-${blockId}`);

            if (!container) return;

            const isNowOpen = !container.classList.contains("hidden");
            container.classList.toggle("hidden");
            arrow?.classList.toggle("rotate-90");

            // Save state
            nestedBlocksState[blockId] = !isNowOpen;
            localStorage.setItem("nestedBlocksState", JSON.stringify(nestedBlocksState));
        });
        

        // -----------------------------------------
        // BLOCK SORTING (Handle-based drag only)
        // -----------------------------------------
        document.addEventListener("turbo:load", function() {
            document.querySelectorAll(".block-list").forEach(list => {
                Sortable.create(list, {
                    animation: 150,
                    ghostClass: "bg-gray-100",
                    handle: ".block-drag-handle",
                    group: "blocks",
                    onEnd: function(evt) {
                        const sectionId = list.id.replace("block-list-", "");

                        // Build block order
                        const order = {};
                        list.querySelectorAll("li[data-block-id]").forEach((el, index) => {
                            order[el.dataset.blockId] = index + 1;
                        });

                        // Send AJAX to backend
                        fetch(`/admin/builder/sections/blocks/${sectionId}`, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    order: order
                                })
                            })
                            .then(res => {
                                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                                return res.json();
                            })
                            .then(data => {
                                if (data.status === 'success') {
                                    const iframe = document.getElementById("livePreviewContent");
                                    if (iframe) iframe.contentWindow.location.reload();
                                }
                            })
                            .catch(err => {
                                console.error('Reorder failed:', err);
                                alert('Failed to reorder blocks. Please try again.');
                            });
                    }
                });
            });
        });

        // -----------------------------------------
        // TOGGLE BLOCK ACTIVE/INACTIVE
        // -----------------------------------------
        document.addEventListener("click", function(e) {
            const btn = e.target.closest(".toggle-block-active");
            if (btn) {
                e.stopPropagation();

                const blockId = btn.dataset.blockId;

                fetch(`/admin/builder/sections/blocks/${blockId}/toggle-active`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            btn.innerHTML = data.is_active ?
                                '<i class="fa-solid fa-eye"></i>' :
                                '<i class="fa-solid fa-eye-slash"></i>';

                            const iframe = document.getElementById("livePreviewContent");
                            if (iframe) iframe.contentWindow.location.reload();
                        }
                    })
                    .catch(err => {
                        console.error('Toggle active failed:', err);
                        alert('Failed to toggle block. Please try again.');
                    });
            }
        });

        // -----------------------------------------
        // DELETE BLOCK (fixed for nested + main)
        // -----------------------------------------
        document.addEventListener("click", function(e) {
            const btn = e.target.closest(".delete-block-btn");
            if (!btn) return;

            e.stopPropagation();

            const form = btn.closest(".delete-block-form");
            if (!form) return;

            const blockId = form.dataset.blockId;
            if (!blockId) return;

            fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "X-HTTP-Method-Override": "DELETE",
                    },
                })
                .then((res) => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then((data) => {
                    if (data.status !== "success") return;

                    const li = document.getElementById(`block-${blockId}`);
                    const nestedContainer = document.getElementById(`nested-blocks-${blockId}`);
                    const editForm = document.getElementById(`edit-block-form-${blockId}`);

                    // --- DETECT REAL PARENT LIST ---
                    const parentList = form.closest("ul");
                    const isNested = parentList.classList.contains("nested-block-list");

                    // parent node id (either sectionId OR parent block id)
                    const parentId = parentList.id.replace(/\D/g, "");

                    // fade out
                    if (li) {
                        li.style.transition = "opacity .25s";
                        li.style.opacity = "0";
                    }

                    setTimeout(() => {
                        if (li) li.remove();
                        if (nestedContainer) nestedContainer.remove();
                        if (editForm) editForm.remove();

                        // -----------------------------------------
                        // HANDLE ADD BLOCK BUTTON (CORRECT PARENT)
                        // -----------------------------------------

                        const currentCount = parentList.querySelectorAll("li.block-item").length;

                        // get limit rule
                        let maxLimit = null;

                        if (isNested) {
                            maxLimit = window.blockRules?.[parentId]?.max_blocks ?? null;
                        } else {
                            maxLimit = window.sectionRules?.[parentId]?.max_blocks ?? null;
                        }

                        const parentWrapper = parentList.parentElement;
                        const existingBtn = parentWrapper.querySelector(".add-block-btn");

                        if (!maxLimit || currentCount < maxLimit) {
                            if (!existingBtn) {
                                const addBtn = document.createElement("button");
                                addBtn.className =
                                    "add-block-btn text-blue-600 text-left text-sm bg-hover-secondary w-full mt-1 block p-2 border-rounded";

                                // nested or main?
                                if (isNested) {
                                    addBtn.setAttribute("onclick", `openAddNestedBlock(${parentId})`);
                                } else {
                                    addBtn.setAttribute("onclick", `openAddBlock(${parentId})`);
                                }

                                addBtn.innerHTML =
                                    `<i class="fa-regular fa-square-plus mr-1 ml-5.5 text-[13px]"></i> Add Block`;

                                parentWrapper.appendChild(addBtn);
                            }
                        } else {
                            if (existingBtn) existingBtn.remove();
                        }

                        // reload preview iframe
                        const iframe = document.getElementById("livePreviewContent");
                        if (iframe) iframe.contentWindow.location.reload();

                    }, 250);
                })
                .catch(() => {
                    alert("Failed to delete block. Please try again.");
                });
        });
    })();
</script>