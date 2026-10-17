@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'class' => '',
    'disabled' => false,
    'required' => false,
    'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">
    <input
        type="email"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @required($required)
        {{ $attributes->merge([
            'class' => "w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none focus:ring-1 focus:ring-brand font-normal transition $class"
        ]) }}>
</x-input.wrapper>

