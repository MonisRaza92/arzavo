@php

    $type = $block->text_type ?? 'paragraph';

    $desktopWidthType = $block->desktop_width_type ?? 'auto';
    $desktopWidth = $block->desktop_width ?? 100;

    $align = $block->alignment ?? 'left';
    $mAlign = $block->mobile_alignment ?? 'left';


    /* ---------- WIDTH STYLE ---------- */

    $style = '';

    if ($desktopWidthType === 'full') {
        $style .= 'width:100%;';
    } elseif ($desktopWidthType === 'custom') {
        $style .= "--desktop-width:{$desktopWidth}%;";
    }

    /* ---------- ALIGNMENT ---------- */

    $alignClass = match ($align) {
        'center' => 'md:mx-auto md:text-center',
        'right' => 'md:ml-auto md:text-right',
        default => 'md:text-left',
    };

    $mAlignClass = match ($mAlign) {
        'center' => 'mx-auto text-center',
        'right' => 'ml-auto text-right',
        default => 'text-left',
    };

@endphp


<style>
    @media(min-width:768px) {
        .text-{{ $block['id'] }} {

            @if ($desktopWidthType === 'custom')
                max-width: var(--desktop-width);
            @endif

            @if ($desktopWidthType === 'auto')
                width: auto;
            @endif

            @if ($desktopWidthType === 'full')
                width: 100%;
            @endif

        }
    }
</style>


<div {!! $block->attributes() !!} class="
text-{{ $block['id'] }}
arz-{{ $type }}
{{ $mAlignClass }}
{{ $alignClass }}
"
    style="
{{ $style }}
{{ $block->margin . ' ' . $block->padding }}
">
    {!! $block->text !!}
</div>
