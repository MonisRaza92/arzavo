@props(['name', 'label' => '', 'checked' => false, 'value' => 1, 'containerClass' => ''])

<x-input.wrapper :label="$label" :containerClass="$containerClass">

    <label class="flex items-center justify-between cursor-pointer select-none p-3 rounded-lg bg-secondary">

        {{-- Label text --}}
        <span class="text-xs font-semibold text-theme-text">
            {{ $slot->isEmpty() ? $label : $slot }}
        </span>

        <div class="relative inline-flex items-center">

            {{-- Hidden input (important for unchecked state) --}}
            <input type="hidden" name="{{ $name }}" value="0">

            {{-- Real checkbox --}}
            <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked($checked)
                {{ $attributes->merge([
                    'class' => 'sr-only peer live-input',
                ]) }}>

            {{-- Switch UI --}}
            <div class="w-9 h-5 bg-primary peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand cursor-pointer"></div>

        </div>

    </label>

</x-input.wrapper>
