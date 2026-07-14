@php


    $type = $block->heading_type ?? 'heading-2';

    $desktopWidthType = $block->desktop_width_type ?? 'auto';

    $desktopWidth = $block->desktop_width ?? 100;

    $align = $block->alignment ?? 'left';
    $mAlign = $block->mobile_alignment ?? 'left';

    $tag = match ($type) {
        'heading-1' => 'h1',
        'heading-2' => 'h2',
        'heading-3' => 'h3',
        'heading-4' => 'h4',
        'heading-5' => 'h5',
        'heading-6' => 'h6',
        default => 'h2',
    };

    /* ---------- WIDTH CLASS ---------- */

    $widthStyle = '';

    if ($desktopWidthType === 'custom') {
        $widthStyle .= "--desktop-width:{$desktopWidth}%;";
    }

    /* ---------- ALIGNMENT ---------- */

    $alignClass = match ($align) {
        'center' => 'md:text-center',
        'right' => 'md:text-right',
        default => 'md:text-left',
    };

    $mAlignClass = match ($mAlign) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };

    /* ---------- FULL WIDTH ---------- */

    if ($desktopWidthType === 'full') {
        $widthStyle .= '--desktop-full:1;';
    }

@endphp


<style>
    @media(min-width:768px) {
        .heading-{{ $block['id'] }} {
            @if ($desktopWidthType === 'custom')
                max-width: var(--desktop-width);
            @endif
        }
    }
</style>


<div {!! $block->attributes() !!} class="
arz-{{ $tag }}
heading-{{ $block['id'] }}
w-full
{{ $desktopWidthType === 'full' ? 'md:w-full' : 'md:w-auto' }}
{{ $mAlignClass }}
{{ $alignClass }}
" style="
{{ $widthStyle }} {{ $block->margin . ' ' . $block->padding }}
">

    {!! $block->text !!}

</div>
