@props([
'name',
'label' => '',
'value' => '',
'placeholder' => '',
'class' => ''
])

<x-settings.wrapper :label="$label">
    <input
        type="url"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => "w-full p-2 rounded-md border border-primary bg-transparent $class"
        ]) }}>
</x-settings.wrapper>