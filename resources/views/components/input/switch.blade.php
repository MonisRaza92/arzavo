@props(['name', 'label' => '', 'checked' => false, 'value' => 1, 'containerClass' => ''])

<x-input.wrapper :label="$label" :containerClass="$containerClass">

    <label
        class="flex items-center justify-between cursor-pointer select-none">

        {{-- Label text --}}
        <span class="text-sm text-primary">
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
            <div
                class="
                w-12 h-6 bg-gray-200 border-rounded border-primary
                peer-checked:bg-accent overflow-hidden shadow-inner
                transition

                after:content-['']
                after:absolute
                after:top-0
                after:left-0
                after:w-6
                after:h-full
                after:bg-white
                after:border
                after:border-gray-300
                after:rounded
                after:transition-all

                peer-checked:after:translate-x-full
                peer-checked:bg-gray-800
            ">
            </div>

        </div>

    </label>

</x-input.wrapper>
