@props([
'name',
'label' => '',
'value' => null,
'icon' => 'fa-image',
'aspect' => 'aspect-video',
'bg' => 'bg-secondary',
])

@php
$hasImage = filled($value);

// generate safe unique id based on name + random suffix
$uid = 'img_' . md5($name . uniqid());
@endphp

<x-input.wrapper :label="$label">

<div class="image-field-{{ $uid }} relative group border-primary border-rounded overflow-hidden">

    <button type="button"
        class="text-invert transition absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100"
        onclick="deleteSettingsImage('{{ $uid }}')">
        <i class="fa-solid fa-trash text-sm"></i>
    </button>

    <div data-content-wrapper>

        {{-- NAME stays SAME for backend --}}
        <input type="hidden"
               name="{{ $name }}"
               value="{{ $value }}"
               id="{{ $uid }}">

        <div
            class="border-primary border-rounded {{ $bg }} flex flex-col justify-center items-center {{ $aspect }} cursor-pointer group relative overflow-hidden"
            style="border-width:2px;border-style:dashed"
            onclick="openContentPicker('{{ $uid }}','image')">

            <img
                data-content-preview
                @if($hasImage) src="{{ media($value) }}" @endif
                class="{{ $hasImage ? '' : 'hidden' }} object-contain border-rounded">

            <div
                data-content-placeholder
                class="flex flex-col items-center text-tertiary h-full justify-center {{ $hasImage ? 'hidden' : '' }}">
                <i class="fa-solid {{ $icon }} text-2xl mb-2"></i>
                Upload {{ $label }}
            </div>

            <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition">
                <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">
                    Upload / Change
                </span>
            </div>

        </div>
    </div>
</div>

</x-input.wrapper>