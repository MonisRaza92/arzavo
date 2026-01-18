@props([
'name',
'label' => '',
'value' => '#000000'
])

<x-input.wrapper :label="$label">
    <input type="color"
        name="{{ $name }}"
        value="{{ $value }}"
        class="w-full h-10 rounded-md border">
</x-input.wrapper>
