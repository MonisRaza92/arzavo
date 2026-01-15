@props([
'name',
'label' => '',
'value' => false,
'disabled' => false
])

<x-settings.wrapper :label="$label">
    <div class="flex justify-end">
        <div class="relative">

            <input type="hidden" name="{{ $name }}" value="0">

            <input type="checkbox"
                name="{{ $name }}"
                value="1"
                @checked($value)
                @disabled($disabled)
                class="sr-only peer">

            <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition"></div>
            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full peer-checked:translate-x-5 transition"></div>

        </div>
    </div>
</x-settings.wrapper>