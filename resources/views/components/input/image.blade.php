@props([
'name',
'label' => '',
'value' => null,
'icon' => 'fa-image',
'aspect' => 'aspect-video',
'bg' => 'bg-secondary'
])

@php
$hasImage = !empty($value);
@endphp

<x-input.wrapper :label="$label">

    <div class="image-field-{{ $name }} relative group border-primary border-rounded p-2 overflow-hidden">

        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-primary text-sm flex items-center gap-2">
                <i class="fa-solid {{ $icon }} text-xs opacity-70"></i>
                {{ $label }}
            </h3>

            <button type="button"
                class="text-tertiary hover:text-zinc-800 border-rounded transition"
                onclick="deleteSettingsImage('{{ $name }}')">
                <i class="fa-solid fa-trash text-sm"></i>
            </button>
        </div>

        <div data-content-wrapper>
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}">

            <div
                class="border-primary border-rounded px-2 {{ $bg }} flex flex-col justify-center items-center {{ $aspect }} cursor-pointer group relative overflow-hidden"
                style="border-width:2px;border-style:dashed"
                onclick="openContentPicker('{{ $name }}','image')">

                <img
                    data-content-preview
                    @if($hasImage) src="{{ media($value) }}" @endif
                    class="{{ $hasImage ? '' : 'hidden' }} object-contain border-rounded">

                <div
                    data-content-placeholder
                    class="flex flex-col items-center text-tertiary h-full justify-center {{ $hasImage ? 'hidden' : '' }}">
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
    function deleteSettingsImage(key) {

        const wrapper = document.querySelector('.image-field-' + key);
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

        if (typeof submitCustomizesForm === 'function') {
            submitCustomizesForm();
        }
    }
</script>
