@props([
    'name',
    'label' => '',
    'value' => null,
    'icon' => 'fa-image',
    'aspect' => 'aspect-video',
    'bg' => 'bg-secondary',
    'hint' => null,
    'required' => false
])

@php
    $hasImage = filled($value);
    $uid = $attributes->get('id') ?: ('img_' . md5($name . uniqid()));
@endphp

<x-input.wrapper :label="$label" :hint="$hint" :required="$required">

<div class="image-field-{{ $uid }} relative group rounded-lg overflow-hidden">

    <button type="button"
        class="w-7 h-7 rounded-full bg-danger text-white flex items-center justify-center transition absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 shadow-sm hover:opacity-90"
        onclick="deleteSettingsImage('{{ $uid }}')"
        title="Remove Image">
        <i class="fa-solid fa-trash text-xs"></i>
    </button>

    <div data-content-wrapper>

        {{-- NAME stays SAME for backend --}}
        <input type="hidden"
               name="{{ $name }}"
               value="{{ $value }}"
               id="{{ $uid }}">

        <div
            class="rounded-lg border-2 border-dashed border-theme-border/80 hover:border-brand {{ $bg }} flex flex-col justify-center items-center {{ $aspect }} cursor-pointer group relative overflow-hidden transition"
            onclick="openContentPicker('{{ $uid }}','image')">

            <img
                data-content-preview
                @if($hasImage) src="{{ media($value) }}" @endif
                class="{{ $hasImage ? '' : 'hidden' }} w-full h-full object-cover rounded-lg">

            <div
                data-content-placeholder
                class="flex flex-col items-center text-theme-text-muted h-full justify-center p-4 {{ $hasImage ? 'hidden' : '' }}">
                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg mb-2">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <span class="text-xs font-normal text-theme-text">Upload {{ $label ?: 'Image' }}</span>
                <span class="text-[10px] text-theme-text-muted mt-0.5">Click to browse gallery</span>
            </div>

            <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition">
                <span class="px-3 py-1.5 bg-brand text-primary rounded-full text-xs font-normal shadow-sm">
                    Upload / Change
                </span>
            </div>

        </div>
    </div>
</div>

</x-input.wrapper>