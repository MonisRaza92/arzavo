<li id="block-{{ $block['id'] }}" class="block-item" data-block-id="{{ $block['id'] }}"
    data-section-id="{{ $section['id'] }}">
    <div
        class="relative group/nested bg-hover-secondary border-rounded cursor-pointer select-none py-1 pl-2 pr-1 my-1 flex justify-between items-center">
        @php
            $avail = collect($availableBlocks);
            $blockRule = $avail->firstWhere('schema', $block['schema'] ?? null) 
                      ?? $avail->firstWhere('type', $block['type'] ?? null) 
                      ?? [];
            $moveable = $blockRule['moveable'] ?? true;
            $deletable = $blockRule['deletable'] ?? true;
            $toggle = $blockRule['toggle'] ?? true;
        @endphp

        <div class="flex items-center grow">
            @if (!empty($blockRule['allowed_blocks']))
                <button type="button" id="block-btn-{{ $block['id'] }}"
                    class="text-tertiary bg-hover-secondary pt-0.5 pb-1.5 px-2 border-rounded toggle-block-btn"
                    data-id="{{ $block['id'] }}">
                    <i id="block-btn-arrow-{{ $block['id'] }}" class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            @else
                <span class="w-8.75"></span>
            @endif
            <i class="fa-solid {{ $block['icon'] ?? 'fa-cube' }} text-[13px] mr-2 text-tertiary"></i>
            <span class="text-sm cursor-pointer block-open-btn w-full py-1"
                data-block-id=" {{ $block['id'] }}">{{ $block['name'] }}</span>
        </div>

        <div
            class="flex items-center opacity-0 pointer-events-none group-hover/nested:opacity-100 group-hover/nested:pointer-events-auto transition-all duration-200">
            @if ($moveable)
                <button
                    class="cursor-drag text-hover-primary text-tertiary text-xs p-1 border-rounded nested-block-drag-handle">
                    <i class="fa-solid fa-up-down"></i>
                </button>
            @endif

            @if ($toggle)
                {{-- ACTIVE/INACTIVE --}}
                <button type="button"
                    class="toggle-block-active text-tertiary text-xs text-hover-primary p-1 border-rounded"
                    data-block-id="{{ $block['id'] }}" data-section-id="{{ $section['id'] }}">
                    @if($block['is_active'])
                        <i class="fa-solid fa-eye"></i>
                    @else
                        <i class="fa-solid fa-eye-slash"></i>
                    @endif
                </button>
            @endif

            @if ($deletable)
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
    <div id="nested-blocks-{{ $block['id'] }}" class="ml-[21px] hidden">
        {{-- List of blocks --}}
        <ul class="nested-block-list" id="nested-block-list-{{ $block['id'] }}"
            data-parent-block-id="{{ $block['id'] }}" data-section-id="{{ $section['id'] }}">
            @foreach($block['blocks'] ?? [] as $child)
                @include('tenant.admin.builder.blocks.nested-block', ['block' => $child])
            @endforeach
        </ul>
        @php
            $maxNestedBlocks = $blockRule['max_blocks'] ?? null;
            $currentNestedBlockCount = count($block['blocks'] ?? []);
        @endphp
        @if(is_null($maxNestedBlocks) || $currentNestedBlockCount < $maxNestedBlocks)
            <button type="button"
                class="text-blue-600 text-left text-sm bg-hover-secondary mt-1 w-full block p-2.5 border-rounded"
                onclick="document.getElementById('addNestedBlockContainer{{ $block['id'] }}').classList.remove('hidden')">
                <i class="fa-solid fa-circle-plus mr-1 ml-5 text-[13px]"></i> Add Block
            </button>
        @endif
    </div>
    @include('tenant.admin.builder.blocks.nested-block-add')
</li>
<script>
    // -----------------------------------------
    // BLOCK SORTING (Handle-based drag only)
    // -----------------------------------------
    document.addEventListener("turbo:load", function () {
        document.querySelectorAll(".nested-block-list").forEach(list => {
            Sortable.create(list, {
                animation: 150,
                ghostClass: "bg-gray-100",
                handle: ".nested-block-drag-handle",
                group: "nested-blocks",
                onEnd: function (evt) {
                    const blockId = list.dataset.parentBlockId;
                    const sectionId = list.dataset.sectionId;
                    const themeId = "{{ $theme->id }}";
                    const pageId = "{{ $page->id }}";
                    // Build block order
                    const order = {};
                    list.querySelectorAll("li[data-block-id]").forEach((el, index) => {
                        order[el.dataset.blockId] = index + 1;
                    });

                    // Send AJAX to backend
                    fetch(`/admin/builder/${themeId}/${pageId}/${sectionId}/${blockId}/nested/reorder`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            order
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
</script>