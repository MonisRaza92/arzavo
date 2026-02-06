@php
    $s = $block['settings'] ?? [];

    $logoSize = $s['logo_size'] ?? '35';
    $pt = $s['padding_top'] ?? '0';
    $pb = $s['padding_bottom'] ?? '0';
    $pl = $s['padding_left'] ?? '0';
    $pr = $s['padding_right'] ?? '0';
    $clickableLogo = $s['clickable_logo'] ?? '0';

    $logo = $customizes['logo'] ?? null;
    $invertLogo = $customizes['invert_logo'] ?? $logo;
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="arzavo-logo-wrapper w-fit shrink-0"
    style="padding-top: {{ $pt }}px; padding-right: {{ $pr }}px; padding-bottom: {{ $pb }}px; padding-left: {{ $pl }}px;">
    @if ($clickableLogo === '1')
        <a href="{{ route('tenant.home') }}" class="block relative">
    @else
            <div class="block relative">
        @endif

            @if($logo)
                <img src="{{ media($logo) }}" alt="Logo" class="arzavo-logo-normal w-auto transition-opacity duration-300"
                    style="height: {{ $logoSize }}px;">
            @endif

            @if($invertLogo)
                <img src="{{ media($invertLogo) }}" alt="Invert Logo"
                    class="arzavo-logo-invert w-auto absolute top-0 left-0 transition-opacity duration-300 opacity-0"
                    style="height: {{ $logoSize }}px;">
            @endif

            @if(!$logo && !$invertLogo)
                <h2 class="text-xl font-semibold" style="color: var(--arzavo-heading-color);">
                    {{ app('currentTenant')->name }}
                </h2>
            @endif
            @if ($clickableLogo === '1')
                </a>
            @else
        </div>
    @endif
</div>