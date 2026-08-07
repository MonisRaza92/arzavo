@php
    $s = $block['settings'] ?? [];

    $heading = $s['heading'] ?? 'Full Overview & Syllabus Details';
    $showHeading = filter_var($s['show_heading'] ?? true, FILTER_VALIDATE_BOOLEAN);

    $fullDesc = isset($data) ? ($data->description ?? null) : null;
    if ($fullDesc) {
        $fullDesc = htmlspecialchars_decode(htmlspecialchars_decode($fullDesc, ENT_QUOTES), ENT_QUOTES);
    }
@endphp

<div {!! $block->attributes() !!} class="w-full space-y-3" style="{{ $block->margin }}">

    @if($showHeading && filled($heading))
        <h4 class="arz-heading font-bold text-base md:text-lg flex items-center gap-2">
            <i class="fa-solid fa-align-left text-accent"></i>
            <span>{{ $heading }}</span>
        </h4>
    @endif

    <div class="arz-body-text leading-relaxed">
        @if(filled($fullDesc))
            <div class="arz-prose-content prose max-w-none text-secondary">
                {!! $fullDesc !!}
            </div>
        @endif
    </div>
</div>
