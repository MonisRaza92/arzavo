<div id="edit-form-{{ $section->id }}" class="hidden fixed top-0 left-0 bottom-0 w-[299px] pt-29 overflow-auto scrollbar bg-primary z-9">
    <div class="flex items-center justify-between p-2 border-bottom sticky top-0 bg-primary z-10">
        <h2 class="text-sm font-semibold text-primary p-2 bg-hover-primary border-rounded flex gap-2 items-center">
            <i class="fa-solid fa-arrow-left text-tertiary"></i> {{ $section->name }}
        </h2>
        <div class="flex items-center">
            <button type="button" class="toggle-active-btn text-tertiary text-sm bg-hover-secondary p-1 border-rounded" data-section-id="{{ $section->id }}">
                @if($section->is_active)
                <i class="fa-solid fa-eye"></i>
                @else
                <i class="fa-solid fa-eye-slash"></i>
                @endif
            </button>
            <form class="delete-section-form" data-section-id="{{ $section->id }}" action="{{ route('admin.builder.sections.destroy', $section->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="delete-btn text-tertiary text-sm bg-hover-secondary p-1 border-rounded">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @php
    $schema = collect($availableSections)->firstWhere('type', $section->type);
    $fields = $schema['fields'] ?? [];
    @endphp

    @if(count($fields) > 0)
    <form class="editSectionForm" data-section-id="{{ $section->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($fields as $field)
        @php
        // Check conditional display
        $shouldShow = true;
        if (isset($field['conditional'])) {
        $conditionalField = $field['conditional']['field'];
        $conditionalValue = $field['conditional']['value'];
        $currentValue = $section->settings[$conditionalField] ?? null;
        $shouldShow = ($currentValue === $conditionalValue);
        }
        @endphp

        @if($shouldShow)
        <div class="field-item flex justify-between items-center gap-4 border-bottom p-4 transition-all duration-300"
            data-field-key="{{ $field['key'] }}"
            @if(isset($field['conditional']))
            data-conditional-field="{{ $field['conditional']['field'] }}"
            data-conditional-value="{{ $field['conditional']['value'] }}"
            @endif>

            <label class="block text-xs font-semibold text-primary text-left w-1/3">
                {{ $field['label'] ?? ucfirst($field['key']) }}
                @if($field['required'] ?? false)
                <span class="text-red-500">*</span>
                @endif
            </label>

            <div class="w-2/3">
                @switch($field['type'])

                {{-- 🎨 Color Scheme Selector --}}
                @case('color_scheme_selector')
                <div class="color-scheme-selector">
                    <select name="color_scheme_id"
                        class="w-full p-2.5 border-primary border-rounded text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">
                        @foreach($colorSchemes as $scheme)
                        <option value="{{ $scheme->id }}"
                            {{ $section->color_scheme_id == $scheme->id ? 'selected' : '' }}>
                            {{ $scheme->name ?? "Scheme $scheme->id" }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @break

                {{-- 📝 Text Input --}}
                @case('text')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                {{-- 📝 Text Input --}}
                @case('link')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                {{-- 📄 Textarea --}}
                @case('textarea')
                <textarea name="settings[{{ $field['key'] }}]"
                    rows="{{ $field['rows'] ?? 3 }}"
                    placeholder="{{ $field['placeholder'] ?? 'Write something...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs resize-none transition-all">{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}</textarea>
                @break

                {{-- 📋 Select Dropdown --}}
                @case('select')
                <select name="settings[{{ $field['key'] }}]"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full capitalize p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs transition-all">
                    @foreach($field['options'] ?? [] as $value)
                    <option class="capitalize"
                        value="{{ $value }}"
                        {{ ($section->settings[$field['key']] ?? $field['default'] ?? '') === $value ? 'selected' : '' }}>
                        {{ ucfirst(str_replace(['_', '-'], ' ', $value)) }}
                    </option>
                    @endforeach
                </select>
                @break

                {{-- 📜 Array Fields --}}
                @case('array')
                @if($field['key'] === 'navlinks' || $field['key'] === 'navlinks_mobile')
                @php
                $selectedLinks = collect($section->settings['navlinks'] ?? [])->pluck('slug')->toArray();
                @endphp
                <div>
                    <select name="settings[{{ $field['key'] }}][]"
                        multiple
                        class="w-full border-primary border-rounded p-2.5 focus:ring-2 focus:ring-accent focus:outline-none text-sm bg-white transition-all">
                        @foreach($pages as $page)
                        <option value="{{ $page->slug }}"
                            {{ in_array($page->slug, $selectedLinks) ? 'selected' : '' }}>
                            {{ $page->name }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Hold <kbd class="px-1.5 py-0.5 bg-gray-200 border-rounded text-xs">Ctrl</kbd> (or <kbd class="px-1.5 py-0.5 bg-gray-200 border-rounded text-xs">Cmd</kbd>) to select multiple.
                    </p>
                </div>
                @else
                <textarea name="settings[{{ $field['key'] }}]"
                    rows="4"
                    class="border-primary border-rounded w-full p-2.5 text-xs bg-gray-50 focus:ring-2 focus:ring-accent focus:outline-none font-mono transition-all">{{ json_encode($section->settings[$field['key']] ?? $field['value'] ?? '', JSON_PRETTY_PRINT) }}</textarea>
                @endif
                @break

                {{-- ☑️ Checkbox --}}
                @case('checkbox')
                <label class="inline-flex items-center gap-2 cursor-pointer text-sm group">
                    <input type="checkbox"
                        name="settings[{{ $field['key'] }}]"
                        value="1"
                        {{ !empty($section->settings[$field['key']]) ? 'checked' : '' }}
                        class="border-rounded border-primary accent-accent w-4 h-4 live-input cursor-pointer transition-all">
                    <span class="group-hover:text-accent transition-colors">{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                {{-- 🔘 Toggle Switch --}}
                @case('switch')
                <label class="relative inline-flex items-center cursor-pointer group">
                    <input type="hidden"
                        name="settings[{{ $field['key'] }}]"
                        value="0">
                    <input type="checkbox"
                        name="settings[{{ $field['key'] }}]"
                        value="1"
                        class="sr-only peer live-input"
                        {{ !empty($section->settings[$field['key']]) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 border-rounded peer shrink-0 peer-checked:bg-black transition-all duration-300"></div>
                    <div class="absolute left-0.5 top-1/2 transform -translate-y-1/2 bg-white w-5 h-5 shrink-0 border-rounded shadow-sm peer-checked:translate-x-5 transition-all duration-300"></div>
                    <span class="ml-3 text-xs font-semibold text-primary group-hover:text-accent transition-colors">{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                {{-- 🎨 Color Picker --}}
                @case('color')
                <div class="flex items-center gap-3">
                    {{-- Color Picker --}}
                    <div class="relative group">
                        <input type="color"
                            id="colorPicker-{{ $field['key'] }}"
                            value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                            class="w-14 h-10 border-primary border-rounded cursor-pointer live-input transition-all hover:scale-105"
                            oninput="document.getElementById('colorInput-{{ $field['key'] }}').value = this.value">
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-accent border-rounded pointer-events-none transition-all"></div>
                    </div>

                    {{-- Hex Input --}}
                    <input type="text"
                        id="colorInput-{{ $field['key'] }}"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                        maxlength="7"
                        pattern="^#[0-9A-Fa-f]{6}$"
                        class="w-28 p-2.5 border-primary border-rounded text-sm live-input uppercase font-mono focus:ring-2 focus:ring-accent focus:outline-none transition-all"
                        oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('colorPicker-{{ $field['key'] }}').value = this.value">
                </div>
                @break

                {{-- 🎚️ Range Slider --}}
                @case('range')
                <div class="flex items-center gap-3">
                    <input type="range"
                        min="{{ $field['min'] ?? 0 }}"
                        max="{{ $field['max'] ?? 100 }}"
                        step="{{ $field['step'] ?? 1 }}"
                        value="{{ $section->settings[$field['key']] ?? $field['default'] ?? 50 }}"
                        name="settings[{{ $field['key'] }}]"
                        class="w-full range-black live-input range-slider transition-all"
                        oninput="this.nextElementSibling.textContent = this.value">
                    <span class="text-sm font-semibold w-12 text-right range-value bg-gray-100 px-2 py-1 border-rounded">
                        {{ $section->settings[$field['key']] ?? $field['default'] ?? 50 }}
                    </span>
                </div>
                @break

                {{-- 🔢 Number --}}
                @case('number')
                <input type="number"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? $field['default'] ?? 0 }}"
                    min="{{ $field['min'] ?? 0 }}"
                    max="{{ $field['max'] ?? 100 }}"
                    step="{{ $field['step'] ?? 1 }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded live-input text-sm focus:ring-2 focus:ring-accent focus:outline-none transition-all">
                @break

                {{-- 📻 Radio Buttons --}}
                @case('radio')
                <div class="flex border-rounded border-primary overflow-hidden">
                    @foreach($field['options'] ?? [] as $value)
                    <label class="flex-1 text-center cursor-pointer group relative">
                        <input type="radio"
                            name="settings[{{ $field['key'] }}]"
                            value="{{ $value }}"
                            {{ ($section->settings[$field['key']] ?? $field['default']) == $value ? 'checked' : '' }}
                            class="peer hidden live-input">
                        <span class="block p-2.5 text-xs border-rounded capitalize peer-checked:bg-black peer-checked:text-white hover:bg-gray-100 peer-checked:hover:bg-gray-900 transition-all duration-200">
                            {{ ucfirst(str_replace(['_', '-'], ' ', $value)) }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @break

                {{-- 🖼️ Image Upload --}}
                @case('image')
                <div class="image-field-wrapper-{{ $field['key'] }} relative group border-primary border-rounded overflow-hidden bg-white hover:border-accent transition-all duration-300"
                    style="border-style: dashed; border-width: 2px;">
                    @php $hasImage = !empty($section->settings[$field['key']]) @endphp

                    <label for="{{ $field['key'] }}Input" class="cursor-pointer block relative">
                        <!-- Upload / Preview Area -->
                        <div id="{{ $field['key'] }}Container" class="relative bg-secondary cursor-pointer"
                            onclick="openImageMenu('{{ $field['key'] }}Input')">

                            @if ($hasImage)
                            <!-- Image Preview -->
                            <img id="{{ $field['key'] }}Preview"
                                src="{{ asset($section->settings[$field['key']]) }}"
                                alt="{{ $field['label'] ?? $field['key'] }}"
                                class="w-full object-contain p-2 transition-all duration-300">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 pointer-events-none">
                                <div class="text-center">
                                    <i class="fa-solid fa-camera text-white text-2xl mb-2"></i>
                                    <span class="text-white text-sm bg-black/70 px-4 py-2 border-rounded block">Change Image</span>
                                </div>
                            </div>
                            @else
                            <!-- Upload Placeholder -->
                            <div id="{{ $field['key'] }}Placeholder" class="flex flex-col items-center justify-center w-42 aspect-video text-primary/80 group-hover:text-accent transition-colors duration-300">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                                <span class="text-xs font-semibold">Upload {{ $field['label'] ?? $field['key'] }}</span>
                                <span class="text-xs text-gray-400 mt-1">Click to browse</span>
                            </div>
                            @endif
                        </div>

                        <!-- Hidden Input -->
                        <input type="text"
                            name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}Input"
                            value="{{ $section->settings[$field['key']] ?? '' }}"
                            class="hidden">
                    </label>

                    <!-- Delete Button (Outside Label) -->
                    @if($hasImage)
                    <button type="button"
                        id="{{ $field['key'] }}DeleteBtn"
                        class="absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100"
                        onclick="deleteImage('{{ $field['key'] }}')">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    @endif
                </div>
                @break

                {{-- ↔️ Alignment Buttons --}}
                @case('alignment')
                <div class="flex items-center gap-2">
                    @foreach(['left'=>'fa-align-left','center'=>'fa-align-center','right'=>'fa-align-right'] as $align => $icon)
                    <button type="button"
                        class="flex-1 p-2.5 border-primary border-rounded hover:bg-accent hover:text-white transition-all duration-200 {{ ($section->settings[$field['key']] ?? 'left') === $align ? 'bg-accent text-white shadow-md' : 'hover:scale-105' }}"
                        onclick="this.closest('.field-item').querySelector('input[name=\'settings[{{ $field['key'] }}]\']').value='{{ $align }}'; 
                                        this.closest('.editSectionForm').dispatchEvent(new Event('input'));
                                        this.closest('.flex').querySelectorAll('button').forEach(b => b.classList.remove('bg-accent', 'text-white', 'shadow-md'));
                                        this.classList.add('bg-accent', 'text-white', 'shadow-md');">
                        <i class="fa-solid {{ $icon }}"></i>
                    </button>
                    @endforeach
                    <input type="hidden"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $section->settings[$field['key']] ?? 'left' }}">
                </div>
                @break

                {{-- ❓ Default/Fallback --}}
                @default
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? '' }}"
                    class="w-full p-2.5 border-primary border-rounded live-input text-sm focus:ring-2 focus:ring-accent focus:outline-none transition-all">
                @endswitch

                {{-- Help Text --}}
                @if(isset($field['help']))
                <p class="text-xs text-gray-500 mt-1.5 italic">
                    <i class="fa-solid fa-circle-info mr-1"></i>{{ $field['help'] }}
                </p>
                @endif
            </div>
        </div>
        @endif
        @endforeach
    </form>
    @endif
</div>

{{-- Conditional Fields JavaScript --}}
<script>
    // Handle image selection from media library
    window.addEventListener('message', function(e) {
        if (e.data && e.data.type === 'imageSelected') {
            const fieldKey = e.data.fieldKey;
            const imagePath = e.data.imagePath;

            const input = document.getElementById(fieldKey + 'Input');
            const container = document.getElementById(fieldKey + 'Container');
            const wrapper = document.querySelector('.image-field-wrapper-' + fieldKey);

            if (input && container) {
                input.value = imagePath;

                // Update container with new image
                container.innerHTML = `
                    <img id="${fieldKey}Preview"
                         src="${imagePath}"
                         alt="Image Preview"
                         class="w-full object-contain p-2 transition-all duration-300">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 pointer-events-none">
                        <div class="text-center">
                            <i class="fa-solid fa-camera text-white text-2xl mb-2"></i>
                            <span class="text-white text-sm bg-black/70 px-4 py-2 border-rounded block">Change Image</span>
                        </div>
                    </div>
                `;

                // Add delete button if it doesn't exist
                if (wrapper && !document.getElementById(fieldKey + 'DeleteBtn')) {
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.id = fieldKey + 'DeleteBtn';
                    deleteBtn.className = 'absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100';
                    deleteBtn.onclick = function() {
                        deleteImage(fieldKey);
                    };
                    deleteBtn.innerHTML = '<i class="fa-solid fa-trash text-xs"></i>';
                    wrapper.appendChild(deleteBtn);
                }

                // Submit form
                const form = input.closest('.editSectionForm');
                if (form) {
                    handleFormChange({
                        target: input
                    });
                }
            }
        }
    });

    // Delete Image Function
    function deleteImage(fieldKey) {
        event.stopPropagation();

        const input = document.getElementById(fieldKey + 'Input');
        const preview = document.getElementById(fieldKey + 'Preview');
        const deleteBtn = document.getElementById(fieldKey + 'DeleteBtn');
        const container = document.getElementById(fieldKey + 'Container');

        // Clear input value
        if (input) {
            input.value = '';
        }

        // Remove preview image
        if (preview) {
            preview.remove();
        }

        // Remove delete button
        if (deleteBtn) {
            deleteBtn.remove();
        }

        // Add placeholder back
        if (container) {
            container.innerHTML = `
                <div id="${fieldKey}Placeholder" class="flex flex-col items-center justify-center w-42 aspect-video text-primary/80 group-hover:text-accent transition-colors duration-300">
                    <i class="fa-solid fa-cloud-arrow-up text-4xl mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="text-xs font-semibold">Upload Image</span>
                    <span class="text-xs text-gray-400 mt-1">Click to browse</span>
                </div>
            `;
        }

        // Submit form to update backend
        const form = input.closest('.editSectionForm');
        if (form) {
            submitSectionForm(form);
        }
    }

    // Handle conditional field visibility
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.editSectionForm');
        if (!form) return;

        // Function to toggle conditional fields
        function updateConditionalFields() {
            const conditionalFields = form.querySelectorAll('[data-conditional-field]');

            conditionalFields.forEach(field => {
                const conditionalFieldName = field.dataset.conditionalField;
                const conditionalValue = field.dataset.conditionalValue;

                // Find the controlling field
                const controlField = form.querySelector(`[name="settings[${conditionalFieldName}]"]`);

                if (controlField) {
                    let currentValue = null;

                    // Get value based on field type
                    if (controlField.type === 'radio') {
                        const checkedRadio = form.querySelector(`[name="settings[${conditionalFieldName}]"]:checked`);
                        currentValue = checkedRadio ? checkedRadio.value : null;
                    } else if (controlField.type === 'checkbox') {
                        currentValue = controlField.checked ? '1' : '0';
                    } else {
                        currentValue = controlField.value;
                    }

                    // Show/hide field based on condition
                    if (currentValue === conditionalValue) {
                        field.style.display = 'flex';
                        field.classList.remove('opacity-0');
                        field.classList.add('opacity-100');
                    } else {
                        field.style.display = 'none';
                        field.classList.remove('opacity-100');
                        field.classList.add('opacity-0');
                    }
                }
            });
        }

        // Initial check
        updateConditionalFields();

        // Listen for changes
        form.addEventListener('change', updateConditionalFields);
        form.addEventListener('input', updateConditionalFields);
    });

    // Auto-submit form with debouncing
    if (typeof submitTimeout === 'undefined') {
        window.submitTimeout = null;
    }

    document.addEventListener('input', handleFormChange);
    document.addEventListener('change', handleFormChange);

    function handleFormChange(e) {
        const form = e.target.closest('.editSectionForm');
        if (!form) return;

        // Clear old timeout (debounce)
        clearTimeout(window.submitTimeout);

        // Wait 800ms after last input
        window.submitTimeout = setTimeout(() => {
            submitSectionForm(form);
        }, 800);
    }

    function submitSectionForm(form) {
        const sectionId = form.dataset.sectionId;
        const formData = new FormData(form);

        // Add empty string for empty image fields to ensure they get cleared
        const imageInputs = form.querySelectorAll('input[type="text"][id$="Input"]');
        imageInputs.forEach(input => {
            if (input.value === '') {
                formData.set(input.name, '');
            }
        });

        fetch(`/admin/builder/sections/${sectionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update delete buttons for image fields
                    updateImageDeleteButtons(form);

                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) {
                        iframe.contentWindow.location.reload();
                    }
                } else {
                    console.error('Update failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('Live update failed:', err));
    }

    // Function to update delete buttons after image upload
    function updateImageDeleteButtons(form) {
        const imageInputs = form.querySelectorAll('input[type="text"][id$="Input"]');

        imageInputs.forEach(input => {
            const fieldKey = input.id.replace('Input', '');
            const hasImage = input.value && input.value.trim() !== '';
            const wrapper = input.closest('.image-field-wrapper-' + fieldKey);
            const existingDeleteBtn = document.getElementById(fieldKey + 'DeleteBtn');

            if (hasImage && !existingDeleteBtn && wrapper) {
                // Create delete button if image exists but button doesn't
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.id = fieldKey + 'DeleteBtn';
                deleteBtn.className = 'absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100';
                deleteBtn.onclick = function() {
                    deleteImage(fieldKey);
                };
                deleteBtn.innerHTML = '<i class="fa-solid fa-trash text-xs"></i>';
                wrapper.appendChild(deleteBtn);
            } else if (!hasImage && existingDeleteBtn) {
                // Remove delete button if no image
                existingDeleteBtn.remove();
            }
        });
    }
</script>

{{-- Custom Styles --}}
<style>
    /* Smooth transitions for all interactive elements */
    .field-item input:not([type="radio"]):not([type="checkbox"]),
    .field-item select,
    .field-item textarea {
        transition: all 0.2s ease;
    }

    .field-item input:focus:not([type="radio"]):not([type="checkbox"]),
    .field-item select:focus,
    .field-item textarea:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Range slider enhancement */
    .range-slider::-webkit-slider-thumb {
        transition: all 0.2s ease;
    }

    .range-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }

    /* Radio button smooth animation */
    input[type="radio"]+span {
        position: relative;
        overflow: hidden;
    }

    input[type="radio"]:checked+span::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.2);
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0;
        }
    }

    /* Image upload hover effects */
    .image-field:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Conditional fields animation */
    [data-conditional-field] {
        transition: opacity 0.3s ease, max-height 0.3s ease;
    }
</style>