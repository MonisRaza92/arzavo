@php
    $s = $block['settings'] ?? [];

    $text = $s['text'] ?? '';

    $type = $s['heading_type'] ?? 'heading-2';

    $desktopWidthType = $s['desktop_width_type'] ?? 'auto';

    $desktopWidth = $s['desktop_width'] ?? 100;

    $align = $s['alignment'] ?? 'left';
    $mAlign = $s['mobile_alignment'] ?? 'left';

    $mt = $s['margin_top'] ?? 0;
    $mb = $s['margin_bottom'] ?? 0;
    $ml = $s['margin_left'] ?? 0;
    $mr = $s['margin_right'] ?? 0;

    $pt = $s['padding_top'] ?? 0;
    $pb = $s['padding_bottom'] ?? 0;
    $pl = $s['padding_left'] ?? 0;
    $pr = $s['padding_right'] ?? 0;

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


<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="
arzavo-{{ $type }}
heading-{{ $block['id'] }}
w-full
{{ $desktopWidthType === 'full' ? 'md:w-full' : 'md:w-auto' }}
{{ $mAlignClass }}
{{ $alignClass }}
" style="
{{ $widthStyle }}
margin:{{ $mt }}px {{ $mr }}px {{ $mb }}px {{ $ml }}px;
padding:{{ $pt }}px {{ $pr }}px {{ $pb }}px {{ $pl }}px;
">

    {!! $text !!}

</div>