@props(['name', 'label' => '', 'checked' => false, 'value' => 1])

<x-input.wrapper :label="$label">

    <label class="flex items-center gap-2 cursor-pointer group select-none">

        {{-- Hidden input taaki unchecked hone par bhi value POST ho --}}
        <input type="hidden" name="{{ $name }}" value="0">

        {{-- Actual checkbox --}}
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked($checked)
            {{ $attributes->merge([
                'class' => 'w-4 h-4 border-primary border-rounded accent-accent cursor-pointer live-input',
            ]) }}>

        {{-- Text --}}
        <span class="text-sm text-primary group-hover:text-accent transition">
            {{ $slot->isEmpty() ? $label : $slot }}
        </span>

    </label>

</x-input.wrapper>
