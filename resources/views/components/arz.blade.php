<{{ $tag }} {!! $section->attributes() !!} {{ $attributes }} {{ $class }} {{ $style }} {{ $id }}>

    {{-- Backgrounds --}}
    {!! $section->backgrounds() !!}
    {{-- Content --}}
    {{ $slot }}

</{{ $tag }}>