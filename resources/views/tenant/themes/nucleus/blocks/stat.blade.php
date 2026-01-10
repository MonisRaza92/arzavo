@php
$s = $block->settings ?? [];

$number = $s['number'] ?? '100+';
$label  = $s['label'] ?? 'Happy Students';
$align  = $s['alignment'] ?? 'left';
$malign = $s['mobile_alignment'] ?? 'left';
@endphp

<div data-block-id="{{ $block->id }}" data-name="{{ $block->name }}"
    class="
        flex flex-col gap-1
        {{ $align === 'center' ? 'md:text-center' : 'md:text-left' }}
        {{ $malign === 'center' ? 'text-center' : 'text-left' }}
    "
>
    <div class="text-3xl font-bold" style="color: var(--arzavo-heading-color);">
        {{ $number }}
    </div>
    <div class="text-sm arzavo-paragraph opacity-70" style="color: var(--arzavo-paragraph-color);">
        {{ $label }}
    </div>
</div>
