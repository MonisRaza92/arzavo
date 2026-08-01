@php
    $position = match ($block->position) {
        'left' => 'justify-start!',
        'right' => 'justify-end!',
        'space-between' => 'justify-between!',
        default => 'justify-center!',
    };
@endphp
<p {!! $block->attributes() !!} class="flex! items-center w-full arz-link {{ $position }}" style="{{ $block->margin }} gap:{{ $block->gap ?? 8 }}px;">
    <span class="leading-none">{!! $block->text !!}</span> <i class="fa-solid fa-arrow-right"></i>
</p>