@php
    $fullDesc = isset($data) ? ($data->description ?? $data->content ?? null) : null;
    if ($fullDesc) {
        $fullDesc = htmlspecialchars_decode(htmlspecialchars_decode($fullDesc, ENT_QUOTES), ENT_QUOTES);
    }
@endphp


<div {!! $block->attributes() !!} class="w-full space-y-3" style="{{ $block->margin }}">
    <div class="arz-body-text leading-relaxed">
        @if(filled($fullDesc))
            <div class="arz-prose-content prose max-w-none text-secondary">
                {!! $fullDesc !!}
            </div>
        @endif
    </div>
</div>
