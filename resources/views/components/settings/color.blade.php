@props([
'name',
'label' => '',
'value' => '#000000'
])

<x-settings.wrapper :label="$label">
    <input type="color"
        name="{{ $name }}"
        value="{{ $value }}"
        class="w-full h-10 rounded-md border">
</x-settings.wrapper>