@props([
'name',
'label' => '',
'value' => '',
'placeholder' => '',
'class' => '',
'disabled' => false,
'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint">
    <input
        type="email"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => "w-full p-2.5 rounded-md border border-primary bg-transparent $class"
        ]) }}>
</x-input.wrapper>
