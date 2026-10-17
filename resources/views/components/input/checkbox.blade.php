@props(['name', 'label' => '', 'checked' => false, 'value' => 1])

<x-input.wrapper>

    <label class="flex items-center gap-2.5 cursor-pointer group select-none">

        {{-- Hidden input taaki unchecked hone par bhi value POST ho --}}
        <input type="hidden" name="{{ $name }}" value="0">

        {{-- Actual checkbox --}}
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked($checked)
            {{ $attributes->merge([
                'class' => 'w-4 h-4 rounded accent-brand cursor-pointer live-input',
            ]) }}>

        {{-- Text --}}
        <span class="text-xs text-theme-text font-normal group-hover:text-brand transition">
            {{ $slot->isEmpty() ? $label : $slot }}
        </span>

    </label>

</x-input.wrapper>
