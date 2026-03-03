@php
    $s = $section['settings'] ?? [];

    $columns = (int) ($s['columns'] ?? 4);
    $mobileColumns = (int) ($s['mobile_columns'] ?? 1);
    $gap = (int) ($s['gap'] ?? 32);

    $pt = (int) ($s['padding_top'] ?? 60);
    $pb = (int) ($s['padding_bottom'] ?? 40);
    $mt = (int) ($s['margin_top'] ?? 0);

    $contentWidth = $s['content_width'] ?? 'container';

    $backgroundType = $s['background_type'] ?? 'none';
    $bgColor = $s['background_color'] ?? '#111111';
    $bgImage = $s['background_image'] ?? null;

    $showCopyright = ($s['show_copyright'] ?? '1') === '1';
    $copyrightText = $s['copyright_text'] ?? '';

    $hideDesktop = ($s['hide_desktop'] ?? '0') === '1';
    $hideMobile = ($s['hide_mobile'] ?? '0') === '1';

    $visibility = match (true) {
        $hideDesktop && !$hideMobile => 'block md:hidden',
        !$hideDesktop && $hideMobile => 'hidden md:block',
        default => ''
    };

    $containerClass = $contentWidth === 'full' ? 'w-full' : 'container';

    $gridCols = "grid-cols-$mobileColumns md:grid-cols-$columns";

    $styles = "
            padding-top: {$pt}px;
            padding-bottom: {$pb}px;
            margin-top: {$mt}px;
        ";


    if ($backgroundType === 'image' && $bgImage) {
        $styles .= "
                background-image: url('{$bgImage}');
                background-size: cover;
                background-position: center;
            ";
    }
@endphp

<footer data-section-id="{{ $section['id'] }}" class="relative arz-section arzavo-background {{ $visibility }}" style="{{ $styles }}">

    <div class="{{ $containerClass }}">

        <div class="grid {{ $gridCols }}" style="gap: {{ $gap }}px;">
            {!! renderBlocks($section['blocks']) !!}
        </div>

        @if($showCopyright)
            <div class="mt-10 pt-6 border-t text-sm opacity-70">
                {{ $copyrightText }}
            </div>
        @endif

    </div>

</footer>