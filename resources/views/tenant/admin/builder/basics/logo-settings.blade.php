<div class="logo-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('logo-settings-menu', 'arrow-logo')" type="button" class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Logo & Favicon</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-logo"></i>
    </button>
    <div id="logo-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $items = [
            ['key' => 'logo', 'label' => 'Logo', 'icon' => 'fa-image', 'bg' => 'bg-primary'],
            ['key' => 'invert_logo', 'label' => 'Invert Logo', 'icon' => 'fa-adjust', 'bg' => 'bg-primary'],
            ['key' => 'favicon', 'label' => 'Favicon', 'icon' => 'fa-star', 'bg' => 'bg-primary'],
            ]
            @endphp
            @foreach ($items as $item)
            @php
            $key = $item['key'];
            $hasImage = !empty($customizes[$key] ?? null);
            @endphp
            <div class="image-field-{{ $key }} relative group border-primary border-rounded p-2 overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-primary text-sm flex items-center gap-2">
                        <i class="fa-solid {{ $item['icon'] }} text-xs opacity-70"></i>
                        {{ $item['label'] }}
                    </h3>

                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-teriary hover:text-zinc-800  border-rounded transition-all z-10"
                        onclick="deleteLogoImage('{{ $key }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <!-- Upload Area -->
                <div data-content-wrapper>
                    <input type="hidden" name="{{ $key }}" id="{{ $key }}" @if($customizes[$key] !==null) value="{{ $customizes[$key] }}" @endif>

                    <div class="border-primary border-rounded px-2 bg-secondary flex flex-col justify-center items-center aspect-video cursor-pointer group relative overflow-hidden"
                       style="border-width: 2px; border-style: dashed;"
                        onclick="openContentPicker('{{ $key }}', 'image')">

                        <img data-content-preview @if($customizes[$key] !==null) src="{{ media($customizes[$key]) }}" @endif
                            class="{{ $customizes[$key] === null ? 'hidden' : '' }} object-contain border-rounded">

                        <div data-content-placeholder
                            class="flex flex-col items-center text-tertiary h-full justify-center {{ $customizes[$key] === null ? '' : 'hidden' }}">
                            <i class="fa-solid {{$item['icon']}} text-3xl mb-2"></i>
                            Upload {{ $item['label'] }}
                        </div>
                        <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100">
                            <span class="px-4 py-2 bg-black/50 border-rounded text-xs text-invert">Upload/Change image</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    function deleteLogoImage(key) {

        const wrapper = document.querySelector('.image-field-' + key);
        if (!wrapper) return;

        // Preview image
        const preview = wrapper.querySelector('[data-content-preview]');
        // Placeholder
        const placeholder = wrapper.querySelector('[data-content-placeholder]');
        // Hidden input
        const input = wrapper.querySelector('input[name="' + key + '"]');

        // 1️⃣ Hide preview
        if (preview) {
            preview.classList.add('hidden');
            preview.removeAttribute('src');
        }

        // 2️⃣ Show placeholder
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }

        // 3️⃣ Set input value to null
        if (input) {
            input.value = '';
        }

        submitCustomizesForm()
    }
</script>
