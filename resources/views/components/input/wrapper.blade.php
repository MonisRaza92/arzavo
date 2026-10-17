@props([
    'label' => '',
    'hint' => null,
    'containerClass' => '',
    'required' => false
])

<div {{ $attributes->merge(['class' => "w-full $containerClass"]) }}>
    @if($label)
    <label class="text-xs font-normal text-theme-text-muted mb-1.5 flex items-center justify-between">
        <span>
            {{ $label }}
            @if($required)
                <span class="text-danger ml-0.5">*</span>
            @endif
        </span>
    </label>
    @endif

    {{ $slot }}

    @if($hint)
    <p class="text-[11px] mt-1 text-theme-text-muted font-normal">{{ $hint }}</p>
    @endif
</div>

