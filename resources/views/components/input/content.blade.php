@props([
    'name',
    'label' => '',
    'value' => null,
    'icon' => 'fa-file-pdf',
    'aspect' => 'aspect-video',
    'bg' => 'bg-secondary',
    'type' => 'image',
])

@php
    $hasContent = !empty($value);
    $uid = 'cnt_' . md5($name . uniqid());
    $fileName = $hasContent ? basename($value) : '';
@endphp

<x-input.wrapper :label="$label">

<div class="content-field-{{ $uid }} relative group border-primary border-rounded overflow-hidden">

    <button type="button"
        class="text-white bg-red-600 hover:bg-red-700 p-1.5 border-rounded transition absolute top-2 right-2 z-20 opacity-0 group-hover:opacity-100 shadow"
        onclick="deleteSettingsContent('{{ $uid }}')" title="Remove File">
        <i class="fa-solid fa-trash text-xs"></i>
    </button>

    <div data-content-wrapper>

        {{-- NAME SAME, ID UNIQUE --}}
        <input type="hidden"
               name="{{ $name }}"
               value="{{ $value }}"
               id="{{ $uid }}">

        <div
            class="border-primary border-rounded {{ $bg }} flex flex-col justify-center items-center p-4 min-h-[140px] cursor-pointer group relative overflow-hidden transition-all hover:border-black"
            style="border-width:2px;border-style:dashed"
            onclick="openContentPicker('{{ $uid }}','{{ $type }}')">

            @if($type === 'image')
                <img
                    data-content-preview
                    @if($hasContent) src="{{ media($value) }}" @endif
                    class="{{ $hasContent ? '' : 'hidden' }} max-h-40 object-contain border-rounded">
            @else
                <div data-content-file-preview class="{{ $hasContent ? 'flex' : 'hidden' }} flex-col items-center justify-center text-center p-2 z-10">
                    @if($type === 'pdf')
                        <i class="fa-solid fa-file-pdf text-red-500 text-4xl mb-2 drop-shadow-sm"></i>
                    @elseif($type === 'video')
                        <i class="fa-solid fa-file-video text-blue-500 text-4xl mb-2 drop-shadow-sm"></i>
                    @else
                        <i class="fa-solid {{ $icon }} text-accent text-4xl mb-2 drop-shadow-sm"></i>
                    @endif

                    <span data-content-filename class="text-xs font-bold text-primary max-w-[220px] truncate block mb-1">
                        {{ $fileName }}
                    </span>
                    <span class="text-[10px] uppercase font-bold px-2.5 py-0.5 bg-tertiary text-primary rounded border border-primary">
                        {{ strtoupper($type) }} FILE ATTACHED
                    </span>
                </div>
            @endif

            <div
                data-content-placeholder
                class="flex flex-col items-center text-tertiary h-full justify-center {{ $hasContent ? 'hidden' : '' }}">
                <i class="fa-solid {{ $icon }} text-3xl mb-2 text-secondary"></i>
                <span class="text-xs font-semibold">Upload {{ $label }}</span>
            </div>

            <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition z-20">
                <span class="px-3 py-1.5 bg-black/70 border-rounded text-xs text-white font-medium shadow">
                    Upload / Change {{ strtoupper($type) }}
                </span>
            </div>

        </div>
    </div>

</div>

</x-input.wrapper>