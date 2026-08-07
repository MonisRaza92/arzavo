<div id="addNestedBlockContainer{{ $block['id'] }}"
    class="hidden z-200 fixed w-full max-w-5xl h-full md:h-10/12 border-rounded border-primary top-1/2 mt-4 left-1/2 transform -translate-y-1/2 -translate-x-1/2 bg-primary md:shadow-2xl overflow-y-auto scrollbar">

    <h3
        class="flex block-editor-header text-sm absolute w-full top-0 bg-primary justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>{{ $block['name'] }} Blocks</span>
        <i class="fa-solid fa-xmark cursor-pointer"
            onclick="document.getElementById('addNestedBlockContainer{{ $block['id'] }}').classList.add('hidden')"></i>
    </h3>

    <div class="flex h-full overflow-y-hidden">
        @php
            $allowedNestedBlocks = $blockRule['allowed_blocks'] ?? [];
            $groupedNestedBlocks = collect($availableBlocks)
                ->groupBy('category')
                ->map(function ($nestedBlocks) use ($allowedNestedBlocks) {
                    if (!empty($allowedNestedBlocks)) {
                        return $nestedBlocks->filter(fn($block) => in_array($block['type'], $allowedNestedBlocks));
                    }
                    return $nestedBlocks;
                });
        @endphp

        <div class="w-1/4 border-right pt-13 h-full overflow-y-auto scrollbar">

            @foreach ($groupedNestedBlocks as $category => $nestedBlocks)
                @if ($nestedBlocks->count() > 0)
                    {{-- Empty category hide --}}
                    <div class="border-bottom Block-category" data-category="{{ $category }}">

                        <button type="button"
                            class="flex justify-between items-center w-full text-left p-4 text-sm font-semibold bg-hover-secondary category-toggle">
                            {{ $category }}
                            <i class="fa-solid fa-angle-down transition-all duration-300"></i>
                        </button>

                        <div class="category-items transition-all duration-300">
                            @foreach ($nestedBlocks->sortBy('order') as $s)
                                <form class="blockAddForm" id="nestedBlockAddForm{{ $block['id'] }}{{ $s['type'] }}"
                                    method="POST"
                                    action="{{ route('admin.builder.sections.blocks.nested.store', ['theme' => $theme->theme_slug, 'page' => $page->id, 'sectionId' => $section['id'], 'blockId' => $block['id']]) }}">
                                    @csrf

                                    <input type="hidden" name="block_type" value="{{ $s['type'] }}">
                                    <input type="hidden" name="block_name" value="{{ $s['name'] }}">

                                    <button type="button"
                                        onclick="handleNestedBlockAdd(event, '{{ $block['id'] }}', '{{ $s['type'] }}')"
                                        id="nestedBlockAddBtn{{ $block['id'] }}{{ $s['type'] }}"
                                        class="blockAddBtn w-full font-semibold text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
                                        <i class="fa-solid {{ $s['icon'] ?? 'fa-code' }}"></i>
                                        {{ $s['name'] }}
                                    </button>
                                </form>
                            @endforeach
                        </div>

                    </div>
                @endif
            @endforeach

        </div>
        <div class="preview w-3/4 p-4 flex justify-center items-center">
            <div class="text-center text-sm text-gray-200">
                <i class="fa-solid fa-photo-film text-9xl mb-2"></i>
                <p class="text-lg">Preview Not Available</p>
            </div>
        </div>
    </div>
</div>
<script>
    async function handleNestedBlockAdd(event, blockId, blockType) {
        event.preventDefault();

        const form = document.getElementById(`nestedBlockAddForm${blockId}${blockType}`);
        const submitBtn = document.getElementById(`nestedBlockAddBtn${blockId}${blockType}`);
        const originalBtnContent = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Adding...`;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                },
                body: formData,
            });

            if (response.ok) {
                const newBlockHtml = await response.text();
                const blockList = document.getElementById(`nested-block-list-${blockId}`);
                if (blockList) {
                    blockList.insertAdjacentHTML('beforeend', newBlockHtml);
                }

                document.getElementById(`addNestedBlockContainer${blockId}`).classList.add('hidden');
                reloadPreview();
                if (window.initBlockForms) {
                    window.initBlockForms();
                }
            } else {
                let errMsg = 'Error adding block. Please try again.';
                try {
                    const errData = await response.json();
                    if (errData.error || errData.message) {
                        errMsg = errData.error || errData.message;
                    }
                } catch(e) {}
                alert(errMsg);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error adding block. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
        }
    }
</script>
