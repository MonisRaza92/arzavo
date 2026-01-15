@props([
'name',
'label' => '',
'value' => '',
'min' => null,
'max' => null,
'class' => ''
])

<x-settings.wrapper :label="$label">
    <input
        type="number"
        name="{{ $name }}"
        value="{{ $value }}"
        @if($min !==null) min="{{ $min }}" @endif
        @if($max !==null) max="{{ $max }}" @endif
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>
</x-settings.wrapper>