@props([
'name',
'label' => '',
'value' => '',
'placeholder' => '',
'disabled' => false,
'readonly' => false,
'class' => '',
'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint">
    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @readonly($readonly)
        {{ $attributes->merge([
            'class' => "w-full p-2.5 rounded text-sm border-primary bg-transparent $class"
        ]) }}>
</x-input.wrapper>
