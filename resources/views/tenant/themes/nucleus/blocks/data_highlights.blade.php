@php
    $s = $block['settings'] ?? [];

    $showHeading = filter_var($s['show_heading'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $heading = $s['heading'] ?? 'Key Highlights & Features';
    $headingType = $s['heading_type'] ?? 'h6';

    $bulletIcon = $s['bullet_icon'] ?? 'check-circle';
    $iconColor = $s['icon_color'] ?? '#10b981';
    $bgColor = $s['bg_color'] ?? '';
    $textStyle = $s['text_style'] ?? 'arz-body-text';

    $borderWidth = isset($s['border_width']) ? (int)$s['border_width'] : 1;
    $radius = isset($s['border_radius']) ? (int)$s['border_radius'] : 8;
    $cols = (int) ($s['columns'] ?? 2);

    $highlights = isset($data) && !empty($data->highlights) && is_array($data->highlights)
        ? $data->highlights
        : [];

    $gridClass = $cols === 2 ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1';

    $itemStyle = "border-radius: {$radius}px; border-width: {$borderWidth}px;";
    if (filled($bgColor)) {
        $itemStyle .= " background-color: {$bgColor};";
    }

    $bgClass = filled($bgColor) ? '' : 'bg-secondary';
@endphp

<div {!! $block->attributes() !!} class="w-full space-y-3" style="{{ $block->margin }}">

    @if($showHeading && filled($heading))
        <div class="arz-{{ $headingType }} font-bold text-primary flex items-center gap-2">
            <span>{!! $heading !!}</span>
        </div>
    @endif

    @if(!empty($highlights) && count($highlights) > 0)
        <div class="grid {{ $gridClass }} gap-2.5">
            @foreach($highlights as $point)
                @if(filled(trim($point)))
                    <div class="flex items-start gap-2.5 p-2.5 {{ $bgClass }} arz-border transition-all hover:border-accent" style="{{ $itemStyle }}">
                        <i class="fa-solid fa-{{ $bulletIcon }} mt-0.5 text-base shrink-0" style="color: {{ $iconColor }};"></i>
                        <span class="leading-snug font-medium {{ $textStyle }}">{{ $point }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    @elseif(isBuilder())
        <div class="grid {{ $gridClass }} gap-2.5">
            <div class="flex items-start gap-2.5 p-2.5 {{ $bgClass }} arz-border" style="{{ $itemStyle }}">
                <i class="fa-solid fa-{{ $bulletIcon }} mt-0.5 text-base shrink-0" style="color: {{ $iconColor }};"></i>
                <span class="leading-snug font-medium {{ $textStyle }}">500+ Practice Questions & Solved Examples</span>
            </div>
            <div class="flex items-start gap-2.5 p-2.5 {{ $bgClass }} arz-border" style="{{ $itemStyle }}">
                <i class="fa-solid fa-{{ $bulletIcon }} mt-0.5 text-base shrink-0" style="color: {{ $iconColor }};"></i>
                <span class="leading-snug font-medium {{ $textStyle }}">Comprehensive Syllabus Coverage & Chapter Formula Sheets</span>
            </div>
        </div>
    @endif
</div>
