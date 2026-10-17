@props([
    'name',
    'label' => '',
    'value' => false,
    'disabled' => false,
    'hint' => null
])

<div class="flex justify-between items-center p-3 rounded-lg bg-secondary">
    <div>
        <label class="text-xs font-semibold text-theme-text block">
            {{ $label }}
        </label>
        @if ($hint)
        <p class="text-[10px] text-theme-text-muted mt-0.5">{{ $hint }}</p>
        @endif
    </div>

    <label class="relative inline-flex items-center cursor-pointer">
        {{-- Hidden false value --}}
        <input type="hidden" name="{{ $name }}" value="0">

        {{-- Actual checkbox --}}
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $attributes->get('id') ?? $name }}"
            value="1"
            @checked($value)
            @disabled($disabled)
            {{ $attributes->except(['id', 'class']) }}
            class="sr-only peer">

        {{-- Track & Thumb with Brand colors --}}
        <div class="w-9 h-5 bg-primary peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand cursor-pointer"></div>
    </label>
</div>