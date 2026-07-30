@props([
'name',
'label' => '',
'value' => false,
'disabled' => false,
'hint' => null
])

<div class="flex justify-between items-center p-3 rounded border border-primary">

    <div>
        <label class="text-sm font-medium text-gray-700">
            {{ $label }}
        </label>

        @if ($hint)
        <p class="text-xs mt-1 text-gray-500">{{ $hint }}</p>
        @endif
    </div>

    <label class="relative inline-flex items-center cursor-pointer">
        {{-- Hidden false value --}}
        <input type="hidden" name="{{ $name }}" value="0">

        {{-- Actual checkbox --}}
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            @checked($value)
            @disabled($disabled)
            class="sr-only peer">

        {{-- Track --}}
        <div class="w-11 h-6 bg-gray-300 rounded-full
                    peer-checked:bg-black
                    peer-disabled:bg-gray-200
                    transition-colors duration-200">
        </div>

        {{-- Thumb --}}
        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full
                    transition-transform duration-200
                    peer-checked:translate-x-5">
        </div>
    </label>

</div>