@php
$dColumns = match((int) $section->desktop_columns) {
    1 => 'lg:grid-cols-1',
    2 => 'lg:grid-cols-2',
    3 => 'lg:grid-cols-3',
    4 => 'lg:grid-cols-4',
    5 => 'lg:grid-cols-5',
    default => 'lg:grid-cols-4',
};    

$tColumns = match((int) $section->tablet_columns) {
    1 => 'md:grid-cols-1',
    2 => 'md:grid-cols-2',
    3 => 'md:grid-cols-3',
    4 => 'md:grid-cols-4',
    5 => 'md:grid-cols-5',
    default => 'md:grid-cols-2',
};    

$mColumns = match((int) $section->mobile_columns) {
    1 => 'grid-cols-1',
    2 => 'grid-cols-2',
    default => 'grid-cols-1',
};    

$gap = $section->gap ?? 48;
@endphp
<footer {!! $section->attributes() !!} class="{{ $section->visibility }} relative overflow-hidden" style="{{ $section->padding . $section->margin }}">
    {!! $section->backgrounds() !!}
    <div class="arz-content {{ $section->container }} relative z-10">
        
        <div class="grid {{ $mColumns }} {{ $tColumns }} {{ $dColumns }}" style="gap: {{ $gap }}px;">
            {!! $section->blocks() !!}
        </div>
    </div>
</footer>
