@props([
'label' => '',
'hint' => null,
'containerClass' => ''
])

<div {{ $attributes->merge(['class' => "mb-4 $containerClass"]) }}>
    @if($label)
    <label class="block text-sm font-medium mb-2" style="color:var(--text-color)">
        {{ $label }}
    </label>
    @endif

    {{ $slot }}

    @if($hint)
    <p class="text-xs mt-1 text-gray-500">{{ $hint }}</p>
    @endif
</div>