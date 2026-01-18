@props([
'name',
'label' => '',
'value' => '',
'placeholder' => '',
'class' => '',
'disabled' => false
])

<x-input.wrapper :label="$label">
    <input
        type="email"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>
</x-input.wrapper>
