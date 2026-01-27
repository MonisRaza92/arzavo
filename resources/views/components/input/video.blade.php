@props([
'name',
'label' => '',
'value' => null,
'icon' => 'fa-video',
'aspect' => 'aspect-video',
'bg' => 'bg-secondary',
])

@php
$hasVideo = !empty($value);
@endphp

<x-input.wrapper :label="$label">

    <div class="video-field-{{ $name }} relative group border-primary border-rounded overflow-hidden">

        <button type="button"
            class="text-invert transition absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100"
            onclick="deleteSettingsVideo('{{ $name }}')">
            <i class="fa-solid fa-trash text-sm"></i>
        </button>

        <div data-content-wrapper>
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}">

            <div
                class="border-primary border-rounded {{ $bg }} flex flex-col justify-center items-center {{ $aspect }} cursor-pointer group relative overflow-hidden"
                style="border-width:2px;border-style:dashed"
                onclick="openContentPicker('{{ $name }}','video')">

                <video data-content-preview controls
                    @if($hasVideo) src="{{ media($value) }}" @endif
                    class="{{ $hasVideo ? '' : 'hidden' }} object-cover h-full border-rounded">
                </video>
                <div
                    data-content-placeholder
                    class="flex flex-col items-center text-tertiary h-full justify-center {{ $hasVideo ? 'hidden' : '' }}">
                    <i class="fa-solid {{ $icon }} text-3xl mb-2"></i>
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
<script>
    function deleteSettingsVideo(key) {

        const wrapper = document.querySelector('.video-field-' + key);
        if (!wrapper) return;

        const preview = wrapper.querySelector('[data-content-preview]');
        const placeholder = wrapper.querySelector('[data-content-placeholder]');
        const input = wrapper.querySelector('input[name="' + key + '"]');

        if (preview) {
            preview.classList.add('hidden');
            preview.removeAttribute('src');
        }

        if (placeholder) {
            placeholder.classList.remove('hidden');
        }

        if (input) {
            input.value = '';
        }
    }
</script>