<li id="block-{{ $block->id }}" class="block-item"
    data-block-id="{{ $block->id }}">
    <div class="relative group bg-hover-secondary border-rounded cursor-pointer select-none py-0.5 pl-2 pr-1 my-1 flex justify-between items-center">

        <div class="flex items-center grow">
            @if (!empty($blockRules[$block->type]['allowed_blocks']))
            <button type="button" id="block-btn-{{ $block->id }}"
                class="text-tertiary bg-hover-secondary pt-0.5 pb-1.5 px-1 mr-1 border-rounded toggle-block-btn"
                data-id="{{ $block->id }}">
                <i id="block-btn-arrow-{{ $block->id }}" class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
            @else
            <span class="w-8"></span>
            @endif
            <i class="fa-solid {{ $block->icon ?? 'fa-cube' }} text-xs mr-2 text-tertiary"></i>
            <span class="text-sm cursor-pointer block-open-btn w-full" data-block-id="{{ $block->id }}">{{ $block->name }}</span>
        </div>

        <div class="flex items-center opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
            <button class="cursor-drag text-hover-primary text-tertiary text-xs py-2 px-1 border-rounded nested-block-drag-handle">
                <i class="fa-solid fa-up-down"></i>
            </button>
            {{-- ACTIVE/INACTIVE --}}
            <button type="button"
                class="toggle-block-active text-tertiary text-xs text-hover-primary py-2 px-1 border-rounded"
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
    <div id="nested-blocks-{{ $block->id }}" class="ml-[21px] hidden">
        {{-- List of blocks --}}
        <ul class="nested-child-block-list" id="nested-block-list-{{ $block->id }}">
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
            <i class="fa-regular fa-square-plus mr-1 ml-5 text-[13px]"></i> Add Block
            </button>
            @endif
    </div>
    @include('tenant.admin.builder.blocks.nested-block-add')
</li>
<script>
    // -----------------------------------------
    // BLOCK SORTING (Handle-based drag only)
    // -----------------------------------------
    document.addEventListener("turbo:load", function() {
        document.querySelectorAll(".nested-block-list").forEach(list => {
            Sortable.create(list, {
                animation: 150,
                ghostClass: "bg-gray-100",
                handle: ".nested-block-drag-handle",
                group: "nested-blocks",
                onEnd: function(evt) {
                    const blockId = list.id.replace("nested-block-list-", "");

                    // Build block order
                    const order = {};
                    list.querySelectorAll("li[data-block-id]").forEach((el, index) => {
                        order[el.dataset.blockId] = index + 1;
                    });

                    // Send AJAX to backend
                    fetch(`/admin/builder/sections/blocks/nested/${blockId}`, {
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
</script>