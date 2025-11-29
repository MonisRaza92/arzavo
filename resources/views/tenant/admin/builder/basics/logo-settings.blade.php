<div class="logo-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('logo-settings-menu', 'arrow-logo')" type="button" class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Logo & Favicon</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-logo"></i>
    </button>
    <div id="logo-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @foreach ([
            ['key' => 'logo', 'label' => 'Logo', 'icon' => 'fa-image', 'bg' => 'bg-primary'],
            ['key' => 'invert_logo', 'label' => 'Invert Logo', 'icon' => 'fa-adjust', 'bg' => 'bg-primary'],
            ['key' => 'favicon', 'label' => 'Favicon', 'icon' => 'fa-star', 'bg' => 'bg-primary'],
            ] as $item)
            @php
            $key = $item['key'];
            $hasImage = !empty($customizes[$key] ?? null);
            @endphp

            <div class="image-field-{{ $key }} relative group border-primary border-rounded p-2 overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-primary text-sm flex items-center gap-2">
                        <i class="fa-solid {{ $item['icon'] }} text-sm opacity-70"></i>
                        {{ $item['label'] }}
                    </h3>

                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-teriary hover:text-zinc-800  border-rounded transition-all z-10 {{ $hasImage ? '' : 'opacity-50' }}"
                        data-key="{{ $key }}"
                        onclick="event.stopPropagation(); deleteCustomizeImage('{{ $key }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </div>

                <!-- Upload Area -->
                <label for="{{ $key }}Input" class="cursor-pointer block">
                    <div id="{{ $key }}PreviewContainer"
                        class="relative bg-secondary border-primary border-rounded cursor-pointer overflow-hidden"
                        onclick="openImageMenu('{{ $key }}Input')">
                        @if ($hasImage)
                        <img id="{{ $key }}Preview"
                            src="{{ asset($customizes[$key] ?? '') }}"
                            alt="{{ $item['label'] }}"
                            class="w-full object-contain p-4 aspect-video">
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition pointer-events-none">
                            <span class="text-white text-sm bg-black/50 px-3 py-1 border-rounded">Change</span>
                        </div>
                        @else
                        <div id="{{ $key }}Placeholder" class="flex flex-col items-center justify-center h-28 text-primary">
                            <i class="fa-solid {{ $item['icon'] }} text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Upload {{ $item['label'] }}</span>
                        </div>
                        @endif
                    </div>

                    <input type="text"
                        name="{{ $key }}"
                        id="{{ $key }}Input"
                        value="{{ $customizes[$key] ?? '' }}"
                        class="hidden">
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    (function() {
        'use strict';

        // Handle image selection from media library
        window.addEventListener('message', function(e) {
            if (e.data && e.data.type === 'imageSelected') {
                const fieldKey = e.data.fieldKey;
                const imagePath = e.data.imagePath;

                const input = document.getElementById(fieldKey + 'Input');
                const container = document.getElementById(fieldKey + 'PreviewContainer');
                const wrapper = document.querySelector('.image-field-' + fieldKey);

                if (input && container) {
                    // Update input value
                    input.value = imagePath;

                    // Update preview container
                    container.innerHTML = `
                    <img id="${fieldKey}Preview"
                         src="${imagePath}"
                         alt="Preview"
                         class="w-full object-contain p-4 aspect-video">
                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition pointer-events-none">
                        <span class="text-white text-sm bg-black/50 px-3 py-1 border-rounded">Change</span>
                    </div>
                `;

                    // Add delete button if it doesn't exist
                    if (wrapper) {
                        let deleteBtn = wrapper.querySelector('.delete-image-btn');
                        if (!deleteBtn) {
                            const header = wrapper.querySelector('.flex.items-center.justify-between');
                            if (header) {
                                deleteBtn = document.createElement('button');
                                deleteBtn.type = 'button';
                                deleteBtn.className = 'delete-image-btn text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2 py-1 border-rounded transition-all z-10';
                                deleteBtn.dataset.key = fieldKey;
                                deleteBtn.onclick = function(e) {
                                    e.stopPropagation();
                                    deleteCustomizeImage(fieldKey);
                                };
                                deleteBtn.innerHTML = '<i class="fa-solid fa-trash text-xs"></i>';
                                header.appendChild(deleteBtn);
                            }
                        }
                    }

                    // Submit form
                    if (typeof submitCustomizesForm === 'function') {
                        submitCustomizesForm();
                    }
                }
            }
        });

        // Delete image function
        window.deleteCustomizeImage = function(key) {
            const input = document.getElementById(key + 'Input');
            const container = document.getElementById(key + 'PreviewContainer');
            const wrapper = document.querySelector('.image-field-' + key);
            const deleteBtn = wrapper ? wrapper.querySelector('.delete-image-btn') : null;

            if (!input || !container) {
                console.error('Image elements not found for key:', key);
                return;
            }

            // Clear input value
            input.value = '';

            // Get icon from original data
            const iconMap = {
                'logo': 'fa-image',
                'invert_logo': 'fa-adjust',
                'favicon': 'fa-star'
            };
            const icon = iconMap[key] || 'fa-image';

            // Get label
            const labelMap = {
                'logo': 'Logo',
                'invert_logo': 'Invert Logo',
                'favicon': 'Favicon'
            };
            const label = labelMap[key] || 'Image';

            // Replace with placeholder
            container.innerHTML = `
            <div id="${key}Placeholder" class="flex flex-col items-center justify-center h-28 text-primary">
                <i class="fa-solid ${icon} text-3xl mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Upload ${label}</span>
            </div>
        `;

            // Submit form to save changes
            if (typeof submitCustomizesForm === 'function') {
                submitCustomizesForm();
            } else {
                console.error('submitCustomizesForm function not found');
            }
        };

    })();
</script>

<style>
    .image-field-logo:hover .delete-image-btn,
    .image-field-invert_logo:hover .delete-image-btn,
    .image-field-favicon:hover .delete-image-btn {
        opacity: 1;
    }

    .delete-image-btn {
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }

    .delete-image-btn:hover {
        opacity: 1;
    }
</style>