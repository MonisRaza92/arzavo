@props([
    'name',
    'label' => '',
    'value' => '',
    'min' => null,
    'max' => null,
    'placeholder' => '',
    'class' => '',
    'disabled' => false,
    'required' => false,
    'hint' => null
])

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">
    <input
        type="number"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @required($required)
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        {{ $attributes->merge([
            'class' => "w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-full focus:outline-none focus:ring-1 focus:ring-brand font-normal transition $class"
        ]) }}>
</x-input.wrapper>

