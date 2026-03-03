@props([
'name',
'label' => '',
'value' => '',
'placeholder' => '',
'disabled' => false,
'readonly' => false,
'class' => ''
])

<x-input.wrapper :label="$label">
    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @readonly($readonly)
        {{ $attributes->merge([
            'class' => "w-full p-2 border-rounded text-xs border-primary bg-transparent $class"
        ]) }}>
</x-input.wrapper>
