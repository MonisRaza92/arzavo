@props([
'name',
'label' => '',
'value' => '',
'rows' => 5,
'class' => ''
])

<x-input.wrapper :label="$label">
    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>{{ $value }}</textarea>
</x-input.wrapper>
