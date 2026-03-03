<li id="block-{{ $block['id'] }}" class="block-item" data-block-id="{{ $block['id'] }}"
    data-section-id="{{ $section['id'] }}">
    <div
        class="bg-hover-secondary relative group/block border-rounded cursor-pointer select-none py-0.5 px-1 mt-1 flex justify-between items-center">

        @php
            $blockRules = $availableBlocks->firstWhere('schema', $block['schema'] ?? null) ?? $availableBlocks->firstWhere('type', $block['type'] ?? null) ?? [];
        @endphp
        <div class="flex items-center grow">

            {{-- NESTED TOGGLER --}}
            @if (!empty($blockRules['allowed_blocks']))
                <button type="button" id="block-btn-{{ $block['id'] }}"
                    class="text-tertiary bg-hover-secondary pt-0.5 pb-1.5 px-2 border-rounded toggle-block-btn"
                    data-id="{{ $block['id'] }}">
                    <i id="block-btn-arrow-{{ $block['id'] }}" class="fa-solid fa-chevron-right text-[9px]"></i>
                </button>
            @else
                <span class="w-8 h-8"></span>
            @endif
            <span class="text-[13px] cursor-pointer block-open-btn w-full" data-block-id="{{ $block['id'] }}"><i
                    class="fa-solid {{ $block['icon'] ?? 'fa-shapes' }} text-xs mr-2 text-tertiary"></i>{{ $block['name'] }}</span>
        </div>

        <div
            class="flex items-center opacity-0 pointer-events-none group-hover/block:opacity-100 group-hover/block:pointer-events-auto transition-all duration-200">
            @if ($blockRules['moveable'] ?? true)
                <button
                    class="cursor-drag text-hover-primary text-tertiary text-xs p-1 border-rounded block-drag-handle">
                    <i class="fa-solid fa-up-down"></i>
                </button>
            @endif

            @if ($blockRules['toggle'] ?? true)
            {{-- ACTIVE/INACTIVE --}}
            <button type="button"
            class="toggle-block-active text-tertiary text-[13px] text-hover-primary p-1 border-rounded"
            data-block-id="{{ $block['id'] }}" data-section-id="{{ $section['id'] }}">
            @if($block['is_active'])
                    <i class="fa-solid fa-eye"></i>
                @else
                    <i class="fa-solid fa-eye-slash"></i>
                @endif
            </button>
            @endif

            @if ($blockRules['deletable'] ?? true)
            {{-- DELETE --}}
            <form class="delete-block-form" data-block-id="{{ $block['id'] }}"
                action="{{ route('admin.builder.sections.blocks.destroy', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => $section['id'], 'blockId' => $block['id']]) }}"
                method="POST">
                @csrf
                @method('DELETE')
                
                <button type="button"
                class="delete-block-btn text-tertiary text-hover-primary p-1 border-rounded text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
            @endif

        </div>
        
        @include('tenant.admin.builder.blocks.block-edit')
    </div>

    {{-- Nested Blocks --}}
    <div id="nested-blocks-{{ $block['id'] }}" class="ml-5.25 hidden">
        {{-- List of blocks --}}
        <ul class="nested-block-list" id="nested-block-list-{{ $block['id'] }}" data-parent-block-id="{{ $block['id'] }}" data-section-id="{{ $section['id'] }}">
            @foreach($block['blocks'] ?? [] as $child)
                @include('tenant.admin.builder.blocks.nested-block', ['block' => $child])
            @endforeach
        </ul>
        @php
            $maxNestedBlocks = $blockRules['max_blocks'] ?? null;
            $currentNestedBlockCount = count($block['blocks'] ?? []);
        @endphp
        @if(is_null($maxNestedBlocks) || $currentNestedBlockCount < $maxNestedBlocks)
            <button type="button"
                class="text-blue-600 text-left text-sm bg-hover-secondary w-full block p-2 border-rounded"
                onclick="document.getElementById('addNestedBlockContainer{{ $block['id'] }}').classList.remove('hidden')">
                <i class="fa-jelly fa-regular fa-circle-plus mr-1 ml-6 text-[13px]"></i> Add Block
            </button>
        @endif
    </div>
    @include('tenant.admin.builder.blocks.nested-block-add',['blockRule' => $blockRules])
</li>


<script>
    // ---------------------------------------------
    // BLOCK MANAGEMENT SCRIPTS - ADD ONCE
    // ---------------------------------------------
    (function () {
        'use strict';

        // Prevent multiple initializations
        if (window.blockScriptsInitialized) {
            return; // Silently exit if already initialized
        }
        window.blockScriptsInitialized = true;

        // Nested Block list Open Function
        const nestedBlocksState = JSON.parse(localStorage.getItem("nestedBlocksState") || "{}");

        // Restore saved state on page load
        document.addEventListener("turbo:load", function () {
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
        document.addEventListener("click", function (e) {
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
        document.addEventListener("turbo:load", function () {
            document.querySelectorAll(".block-list").forEach(list => {
                Sortable.create(list, {
                    animation: 150,
                    ghostClass: "bg-gray-100",
                    handle: ".block-drag-handle",
                    group: "blocks",
                    onEnd: function (evt) {
                        const sectionId = list.id.replace("block-list-", "");
                        const themeId = "{{ $theme->id }}";
                        const pageId = "{{ $page->id }}";

                        // Build block order
                        const order = {};
                        list.querySelectorAll("li[data-block-id]").forEach((el, index) => {
                            order[el.dataset.blockId] = index + 1;
                        });

                        // Send AJAX to backend
                        fetch(`/admin/builder/${themeId}/${pageId}/${sectionId}/blockId/reorder`, {
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
                                    reloadPreview()
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
        document.addEventListener("click", function (e) {
            const btn = e.target.closest(".toggle-block-active");
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const blockEl = btn.closest("[data-block-id]");
            if (!blockEl) return;

            const blockId = blockEl.dataset.blockId;
            const sectionId = blockEl.dataset.sectionId;

            const themeId = "{{ $theme->id }}";
            const pageId = "{{ $page->id }}";

            fetch(`/admin/builder/${themeId}/${pageId}/${sectionId}/${blockId}/toggle-active`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status !== "success") return;

                    btn.innerHTML = data.is_active
                        ? '<i class="fa-solid fa-eye"></i>'
                        : '<i class="fa-solid fa-eye-slash"></i>';

                    reloadPreview()
                });
        });

        // -----------------------------------------
        // DELETE BLOCK (fixed for nested + main)
        // -----------------------------------------
        document.addEventListener("click", function (e) {
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
                    // Remove block from DOM
                    const blockEl = document.getElementById(`block-${blockId}`);
                    if (blockEl) blockEl.remove();
                    // Small delay before reloading preview
                    setTimeout(() => {
                        // reload preview iframe
                        const iframe = document.getElementById("livePreviewContent");
                        if (iframe) iframe.contentWindow.location.reload();

                    }, 50);
                })
                .catch(() => {
                    alert("Failed to delete block. Please try again.");
                });
        });
    })();
</script>