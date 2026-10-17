@props([
    'name',
    'type' => 'text',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'class' => '',
    'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $attributes->get('id') ?? $name }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @readonly($readonly)
        @required($required)
        {{ $attributes->except(['id', 'class'])->merge([
            'class' => "w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none focus:ring-1 focus:ring-brand font-normal transition $class"
        ]) }}>
</x-input.wrapper>

