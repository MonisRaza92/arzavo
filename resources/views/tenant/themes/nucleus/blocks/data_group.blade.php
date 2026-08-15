@php
    $s = $block['settings'] ?? [];

    /* ================= 1. SCHEME & STYLING ================= */
    $schemeMode = $block->scheme_mode ?? ($s['scheme_mode'] ?? 'inherit');
    $scheme = $block->color_scheme ?? ($s['color_scheme'] ?? 'scheme_1');
    $schemeClass = ($schemeMode === 'separate') ? "arz-scheme-{$scheme} bg-primary text-primary" : '';

    /* ================= 2. LAYOUT & FLEX ================= */
    $direction = $block->direction ?? ($s['direction'] ?? 'vertical');
    $alignment = $block->alignment ?? ($s['alignment'] ?? 'start');
    $position = $block->position ?? ($s['position'] ?? 'start');

    $dirClass = ($direction === 'horizontal') ? 'flex-row' : 'flex-col';

    $alignClass = match($alignment) {
        'center' => 'items-center text-center',
        'end' => 'items-end text-right',
        default => 'items-start text-left',
    };

    $justifyClass = match($position) {
        'center' => 'justify-center',
        'end' => 'justify-end',
        'between' => 'justify-between',
        default => 'justify-start',
    };

    /* ================= 3. SIZING, SPACING & BORDER ================= */
    $blockWidth = (int) ($block->block_width ?? ($s['block_width'] ?? 100));
    $gap = isset($block->gap) ? (int) $block->gap : (isset($s['gap']) ? (int) $s['gap'] : 16);
    $border = (int) ($block->border ?? ($s['border'] ?? 0));
    $radius = (int) ($block->radius ?? ($s['radius'] ?? 0));
    $rawPadding = $block->padding ?? ($s['padding'] ?? null);

    // Padding style builder
    $paddingStyle = '';
    if (is_numeric($rawPadding)) {
        $paddingStyle = "padding: {$rawPadding}px;";
    } elseif (is_string($rawPadding) && !empty($rawPadding)) {
        $paddingStyle = $rawPadding;
    }

    $rawMargin = $block->margin ?? ($s['margin'] ?? null);
    $marginStyle = (is_string($rawMargin) && !empty($rawMargin)) ? $rawMargin : '';

    // Width style
    $widthStyle = ($blockWidth > 0 && $blockWidth < 100) ? "width: {$blockWidth}%; max-width: {$blockWidth}%;" : 'width: 100%;';
    
    // Border style
    $borderStyle = ($border > 0) ? "border-width: {$border}px; border-style: solid;" : '';
    $radiusStyle = ($radius > 0) ? "border-radius: {$radius}px;" : '';
@endphp

<div {!! $block->attributes() !!} 
     class="flex {{ $schemeClass }} {{ $dirClass }} {{ $alignClass }} {{ $justifyClass }} @if($border > 0) border-primary @endif"
     style="{{ $widthStyle }} gap: {{ $gap }}px; {{ $paddingStyle }} {{ $marginStyle }} {{ $borderStyle }} {{ $radiusStyle }}">
    {!! $block->blocks()->render(['data' => $data]) !!}
</div>