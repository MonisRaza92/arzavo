@props([
'name',
'label' => '',
'value' => '',
'rows' => 5,
'class' => '',
'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint">
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => "w-full p-2.5 rounded border border-primary bg-transparent $class"
        ]) }}>{{ $value }}</textarea>
</x-input.wrapper>
