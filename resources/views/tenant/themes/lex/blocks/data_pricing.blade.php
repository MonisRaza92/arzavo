@php
    $s = $block['settings'] ?? [];

    $heading = $s['text'] ?? '';
    $headingType = $s['heading_type'] ?? '';
    $fontStyle = $s['font_style'] ?? '';
    $textDecoration = $s['text_decoration'] ?? '';
    $alignment = $s['alignment'] ?? '';
    $mAlignment = $s['mobile_alignment'] ?? '';
    $pt = $s['padding_top'] ?? '';
    $pb = $s['padding_bottom'] ?? '';
    $pl = $s['padding_left'] ?? '';
    $pr = $s['padding_right'] ?? '';


    $alignmentClass = match ($alignment) {
        'left' => 'md:text-left',
        'center' => 'md:text-center',
        'right' => 'md:text-right',
        default => 'md:text-left'
    };

    $mAlignmentClass = match ($mAlignment) {
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left'
    };
@endphp

<h2 data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" style="
        padding-top: {{ $pt }}px;
        padding-bottom: {{ $pb }}px;
        padding-left: {{ $pl }}px;
        padding-right: {{ $pr }}px;
        font-style: {{ $fontStyle }};
        text-decoration: {{ $textDecoration }};
    " class="
        arzavo-{{ $headingType }}
        heading-{{ $block['id'] }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    ">
    @if ($data->is_paid ?? false)

        @if($course->discount_price ?? null)
            <span class="font-bold">
            ₹ {{ $course->discount_price }}
            </span>
            <span class="line-through"
                style="color: var(--arzavo-paragraph-color); font-size: var(--arzavo-paragraph-font-size);">
                ₹ {{ $course->price }}
            </span>
        @else
            <span class="font-bold">
            ₹ {{ $course->price ?? '00.00' }}
            </span>
        @endif
    @else
        Free
    @endif
</h2>
