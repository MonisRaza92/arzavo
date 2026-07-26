@php
$columns = (int) ($section->columns ?? 4);
$tablet = (int) ($section->tablet_columns ?? 2);
$mobile = (int) ($section->mobile_columns ?? 2);

$desktopColumns = match($columns) {
    2 => 'lg:grid-cols-2',
    3 => 'lg:grid-cols-3',
    4 => 'lg:grid-cols-4',
    default => 'lg:grid-cols-4',
};  
$tabletColumns = match($tablet) {
    1 => 'md:grid-cols-1',
    2 => 'md:grid-cols-2',
    3 => 'md:grid-cols-3',
    default => 'md:grid-cols-2',
};  
$mobileColumns = match($mobile) {
    1 => 'grid-cols-1',
    2 => 'grid-cols-2',
    default => 'grid-cols-2',
};
@endphp
<div {!! $section->attributes() !!} class="{{ $section->visibility }}" style="{{ $section->margin . $section->padding }}">
    {!! $section->backgrounds() !!}

    <div class="section-content container arz-content">

        {{-- Optional Header --}}
        @if($section->blocks()->has('header', 'heading', 'text'))
            <div class="stats-header" style="margin-bottom: {{ $section->gap ?? 32 }}px;">
                {!! $section->blocks()->only('header', 'heading', 'text') !!}
            </div>
        @endif

        <div class="grid {{ $mobileColumns }} {{ $desktopColumns }} {{ $tabletColumns }}" style="gap: {{ $section->gap ?? 16 }}px;">
            {!! $section->blocks()->only('stat_item') !!}
        </div>

    </div>
</div>