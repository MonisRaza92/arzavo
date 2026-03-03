@props([
'label' => '',
'hint' => null,
'containerClass' => ''
])

<div {{ $attributes->merge(['class' => "w-full $containerClass"]) }}>
    @if($label)
    <label class="block text-sm font-medium mb-2 text-secondary">
        {{ $label }}
    </label>
    @endif

    {{ $slot }}

    @if($hint)
    <p class="text-xs mt-2 text-gray-500">{{ $hint }}</p>
    @endif
</div>
