@php
    $image = $block['settings'] ?? [];

    $aspectRatio = $image['aspect_ratio'] ?? 'portrait';
    $borderWidth = (int) ($image['border_width'] ?? 1);
    $borderRadius = (int) ($image['border_radius'] ?? 12);
    $showPriceOverlay = filter_var($image['show_price_overlay'] ?? false, FILTER_VALIDATE_BOOLEAN);

    $mt = (int) ($image['margin_top'] ?? 0);
    $mb = (int) ($image['margin_bottom'] ?? 0);
    $ml = (int) ($image['margin_left'] ?? 0);
    $mr = (int) ($image['margin_right'] ?? 0);

    $aspectRatioClass = match ($aspectRatio) {
        'square' => 'aspect-square',
        'portrait' => 'aspect-[3/4]',
        'wide' => 'aspect-video',
        'auto' => '',
        default => 'aspect-[3/4]'
    };

    $hasPricing = isset($data->price_type) || isset($data->price) || isset($data->sale_price);
    $isPaid = ($data->price_type ?? '') === 'paid' || (($data->price ?? 0) > 0);
    $salePrice = $data->sale_price ?? null;
    $originalPrice = $data->price ?? null;
@endphp

<div {!! $block->attributes() !!}
    class="relative w-full max-w-sm overflow-hidden arz-border group" style="
        margin-top: {{ $mt }}px;
        margin-bottom: {{ $mb }}px;
        margin-left: {{ $ml }}px;
        margin-right: {{ $mr }}px;
        border-width: {{ $borderWidth }}px;
        border-radius: {{ $borderRadius }}px;
    ">
    <img src="{{ image($data->cover_image ?? $data->thumbnail ?? $data->image ?? null) }}" alt="{{ substr($data->title ?? $data->name ?? 'Cover Image', 0, 40) }}"
        class="w-full {{ $aspectRatioClass }} object-cover">

    @if($showPriceOverlay && $hasPricing)
        <div class="absolute top-4 right-4 shadow-lg">
            @if($isPaid)
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow">
                    ₹ {{ number_format($salePrice ?: $originalPrice, 2) }}
                </span>
            @else
                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500 text-white shadow">
                    FREE
                </span>
            @endif
        </div>
    @endif
</div>