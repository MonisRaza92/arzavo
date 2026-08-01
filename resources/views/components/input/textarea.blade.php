@props([
    'name',
    'label' => '',
    'value' => '',
    'rows' => 2,
    'class' => '',
    'hint' => null,
    'rich' => false,
    'placeholder' => '',
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
    <x-input.wrapper :label="$label" :hint="$hint">
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            x-data="{
                resize() {
                    $el.style.height = 'auto';
                    $el.style.height = Math.max($el.scrollHeight, 60) + 'px';
                }
            }"
            x-init="$nextTick(() => resize())"
            @input="resize()"
            {{ $attributes->merge([
                'class' => "w-full p-2.5 rounded border border-primary bg-primary text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent overflow-hidden resize-none transition-all $class"
            ]) }}>{{ $cleanValue }}</textarea>
    </x-input.wrapper>
@endif
