@props([
    'name',
    'label' => '',
    'value' => '',
    'rows' => 2,
    'class' => '',
    'hint' => null,
    'rich' => false,
    'placeholder' => '',
    'required' => false,
    'toolbar' => 'full'
])

@if($rich)
    <x-input.rich-text
        :name="$name"
        :label="$label"
        :value="$value"
        :rows="$rows"
        :placeholder="$placeholder"
        :hint="$hint"
        :toolbar="$toolbar" />
@else
    @php
        $cleanValue = is_string($value) ? htmlspecialchars_decode(htmlspecialchars_decode($value, ENT_QUOTES), ENT_QUOTES) : $value;
    @endphp
    <x-input.wrapper :label="$label" :hint="$hint" :required="$required ?? false">
        <textarea
            name="{{ $name }}"
            id="{{ $attributes->get('id') ?? $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->except(['id', 'class'])->merge([
                'class' => "w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-2.5 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand font-normal resize-none transition $class"
            ]) }}>{{ $cleanValue }}</textarea>
    </x-input.wrapper>
@endif
