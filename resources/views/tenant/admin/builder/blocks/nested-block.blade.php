<li id="block-{{ $block->id }}" class="block-item border-primary border-rounded cursor-pointer select-none py-0.5 pl-2 pr-1 my-1 flex justify-between items-center"
    data-block-id="{{ $block->id }}">

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

    <div class="flex items-center">
        <button class="cursor-drag bg-hover-secondary text-tertiary text-xs py-2 px-1 border-rounded block-drag-handle">
            <i class="fa-solid fa-up-down"></i>
        </button>
        {{-- ACTIVE/INACTIVE --}}
        <button type="button"
            class="toggle-block-active text-tertiary text-xs bg-hover-secondary py-2 px-1 border-rounded"
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
                class="delete-block-btn text-tertiary bg-hover-secondary py-2 px-1 border-rounded text-xs">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>

    </div>
    @include('tenant.admin.builder.blocks.block-edit')
</li>
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
        class="text-blue-800 text-left text-sm bg-hover-secondary mt-1 w-full block p-2 border-primary border-rounded"
        onclick="openAddNestedBlock({{ $block->id }})">
        <i class="fa-regular fa-square-plus mr-1"></i> Add Block
        </button>
        @endif
</div>
@include('tenant.admin.builder.blocks.nested-block-add')