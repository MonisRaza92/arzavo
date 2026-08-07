<div id="addBlockContainer{{ $section['id'] }}"
    class="hidden z-200 fixed w-full max-w-5xl h-full md:h-10/12 border-rounded border-primary top-1/2 mt-4 left-1/2 transform -translate-y-1/2 -translate-x-1/2 bg-primary md:shadow-2xl overflow-y-auto scrollbar">

    <h3
        class="flex text-sm absolute w-full top-0 bg-primary justify-between items-center font-bold p-4 text-primary border-bottom">
        <span>{{ $section['name'] }} Blocks</span>
        <i class="fa-solid fa-xmark cursor-pointer"
            onclick="document.getElementById('addBlockContainer{{ $section['id'] }}').classList.add('hidden')"></i>
    </h3>

    <div class="flex h-full overflow-y-hidden">
        @php
            $allowedBlocks = $rules['allowed_blocks'] ?? [];
            $groupedBlocks = collect($availableBlocks)
                ->groupBy('category')
                ->map(function ($blocks) use ($allowedBlocks) {
                    if (!empty($allowedBlocks)) {
                        return $blocks->filter(fn($block) => in_array($block['type'], $allowedBlocks));
                    }
                    return $blocks;
                });
        @endphp

        <div class="w-1/4 border-right pt-13 h-full overflow-y-auto scrollbar">

            @foreach ($groupedBlocks as $category => $blocks)
                @if ($blocks->count() > 0)
                    {{-- Empty category hide --}}
                    <div class="border-bottom Block-category" data-category="{{ $category }}">

                        <button type="button"
                            class="flex justify-between items-center w-full text-left p-4 uppercase text-xs font-semibold bg-hover-secondary category-toggle">
                            {{ $category }}
                            <i class="fa-solid fa-angle-down transition-all duration-300"></i>
                        </button>

                        <div class="category-items transition-all duration-300">
                            @foreach ($blocks->sortBy('order') as $s)
                                <form class="blockAddForm" id="blockAddForm{{ $section['id'] }}-{{ $s['type'] }}"
                                    method="POST"
                                    action="{{ route('admin.builder.sections.blocks.store', ['theme' => $theme->theme_slug, 'page' => $page->id, 'sectionId' => $section['id']]) }}">
                                    @csrf

                                    <input type="hidden" name="block_type" value="{{ $s['type'] }}">
                                    <input type="hidden" name="block_name" value="{{ $s['name'] }}">

                                    <button type="button"
                                        onclick="submitAddBlockForm('{{ $section['id'] }}', '{{ $s['type'] }}')"
                                        id="blockAddBtn{{ $section['id'] }}-{{ $s['type'] }}"
                                        class="blockAddBtn w-full text-xs text-left p-4 border-top bg-hover-secondary flex items-center gap-2">
                                        <i class="fa-solid {{ $s['icon'] }}"></i>
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