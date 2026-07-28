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
        arz-{{ $headingType }}
        heading-{{ $block['id'] }}
        {{ $mAlignmentClass }}
        {{ $alignmentClass }}
    ">
    @php
        $isPaid = ($data->price_type ?? '') === 'paid' || ($data->is_paid ?? false) == true || (($data->price ?? 0) > 0);
        $salePrice = $data->sale_price ?? $data->discount_price ?? null;
        $originalPrice = $data->price ?? null;
    @endphp

    @if ($isPaid)
        @if($salePrice && $salePrice > 0 && $salePrice != $originalPrice)
            <span class="font-bold">
                ₹ {{ number_format($salePrice, 2) }}
            </span>
            <span class="line-through text-sm opacity-60 ml-1">
                ₹ {{ number_format($originalPrice, 2) }}
            </span>
        @else
            <span class="font-bold">
                ₹ {{ number_format($originalPrice ?? 0, 2) }}
            </span>
        @endif
    @else
        <span class="font-bold text-green-600">FREE</span>
    @endif
</h2>