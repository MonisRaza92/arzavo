@php
    $s = $block['settings'] ?? [];

    $text = $s['text'] ?? '';
    $type = $s['text_type'] ?? 'paragraph';

    $desktopWidthType = $s['desktop_width_type'] ?? 'auto';
    $desktopWidth = $s['desktop_width'] ?? 100;

    $align = $s['alignment'] ?? 'left';
    $mAlign = $s['mobile_alignment'] ?? 'left';

    $pt = $s['padding_top'] ?? 0;
    $pb = $s['padding_bottom'] ?? 0;
    $pl = $s['padding_left'] ?? 0;
    $pr = $s['padding_right'] ?? 0;

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


<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}"
    class="
text-{{ $block['id'] }}
arzavo-{{ $type }}
{{ $mAlignClass }}
{{ $alignClass }}
"
    style="
{{ $style }}
padding: {{ $pt }}px {{ $pr }}px {{ $pb }}px {{ $pl }}px;
">
    {!! $text !!}
</div>
