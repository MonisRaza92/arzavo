<li id="section-{{ $section['id'] }}" class="cursor-pointer select-none" data-id="{{ $section['id'] }}">
    <div class="flex justify-between items-center py-0.5 px-1 border-rounded relative group bg-hover-secondary">
        <div class="flex items-center grow section-items">
            @php
                $rules = $availableSections->firstWhere('type', $section['type'] ?? null) ?? [];
            @endphp
            @if (!empty($rules['allowed_blocks']))
                <button id="section-btn-{{ $section['id'] }}" type="button"
                    class="text-primary bg-hover-secondary border-rounded pt-0.5 pb-1.5 px-2 transition-all">
                    <i class="fa-solid fa-chevron-right text-tertiary text-[9px]" id="arrow-{{ $section['id'] }}"></i>
                </button>
            @else
                <span class="w-8 h-8"></span>
            @endif
            <h2 class="text-[13px] w-full section-header" data-id="{{ $section['id'] }}">
                <i
                    class="fa-solid {{ $section['icon'] ?? 'fa-braille' }} text-xs text-tertiary mr-2"></i>{{ $section['name'] }}
            </h2>
        </div>
        <div
            class="flex items-center section-items opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
            @if ($rules['moveable'] ?? true)
                <button
                    class="cursor-drag text-hover-primary text-tertiary text-xs p-1 border-rounded section-drag-handle">
                    <i class="fa-solid fa-up-down"></i>
                </button>
            @endif
            <button type="button"
                class="toggle-active-btn text-tertiary text-[13px] text-hover-primary p-1 border-rounded"
                data-section-id="{{ $section['id'] }}">
                @if($section['is_active'])
                    <i class="fa-solid fa-eye"></i>
                @else
                    <i class="fa-solid fa-eye-slash"></i>
                @endif
            </button>
            <form class="delete-section-form" data-section-id="{{ $section['id'] }}"
                action="{{ route('admin.builder.sections.destroy', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => $section['id']]) }}"
                method="POST">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="delete-btn text-tertiary text-hover-primary p-1 border-rounded text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    {{-- BLOCK LIST CONTAINER --}}
    <div id="blocks-{{ $section['id'] }}" class="ml-7 hidden">
        {{-- List of blocks --}}
        <ul class="block-list" id="block-list-{{ $section['id'] }}">
            @foreach($section['blocks'] as $block)
                @include('tenant.admin.builder.blocks.block-list', ['block' => $block])
            @endforeach
        </ul>
        {{-- Add Block Button --}}
        @php
            $maxBlocks = $rules['max_blocks'] ?? null;
            $currentBlockCount = $section['blocks'] ? count($section['blocks']) : 0;
        @endphp
        @if(is_null($maxBlocks) || $currentBlockCount < $maxBlocks)
            <button type="button"
                class="text-blue-600 text-left text-sm bg-hover-secondary w-full block p-2 border-rounded"
                onclick="document.getElementById('addBlockContainer{{ $section['id'] }}').classList.remove('hidden')">
                <i class="fa-solid fa-circle-plus mr-1 ml-6 text-[13px]"></i> Add Block
            </button>
        @endif

    </div>
    {{-- EDIT SECTION FORM --}}
    @include('tenant.admin.builder.blocks.block-add', ['rules' => $rules])
    @include('tenant.admin.builder.sections.section-edit')
</li>