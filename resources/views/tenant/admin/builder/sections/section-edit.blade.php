<div id="edit-form-{{ $section->id }}" class="section-edit-form hidden edit-form fixed top-0 left-0 bottom-0 w-[299px] overflow-auto scrollbar bg-primary z-30">
    <div class="flex items-center justify-between p-2 border-bottom sticky top-15.5 bg-primary z-10">
        <h2 class="text-sm font-semibold text-primary px-2 py-2.25 bg-hover-primary group border-rounded flex gap-2 items-center" onclick="document.getElementById('edit-form-{{ $section->id }}').classList.add('hidden'); clearPreviewHighlights();" id="sectionFormClose">
            <i class="fa-solid fa-arrow-left text-tertiary group-hover:-translate-x-1 duration-200"></i> {{ $section->name }}
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
    <form class="editSectionForm px-4 pt-14" data-section-id="{{ $section->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($fields as $field)
        @php
        $conditions = $field['conditional'] ?? [];
        if (!empty($conditions) && isset($conditions[0])) {
        foreach ($conditions as $cond) {
        $fieldName = $cond['field'] ?? null;
        $expected = $cond['value'] ?? null;
        $current = $fieldName ? ($block->settings[$fieldName] ?? null) : null;
        // use if needed
        }
        }
        @endphp

        <div class="field-item p-3 transition-all duration-300 mb-2 bg-hover-secondary {{ $field['key'] === 'group' ? 'border-rounded border-primary mt-6' : 'border-rounded border-primary' }}"
            data-field-key="{{ $field['key'] }}"
            @if(isset($field['conditional']))
            data-conditions='@json($field["conditional"])'
            @endif>

            <label class="block text-left {{ $field['key'] === 'group' ? 'font-semibold text-primary text-base py-1 flex justify-between items-center' : 'text-xs font-semibold text-tertiary mb-2 uppercase' }}">
                {{ $field['label'] ?? ucfirst($field['key']) }}
                @if($field['key'] === 'group')
                <i class="fa-solid {{ $field['icon'] ?? 'fa-layer-group' }}"></i>
                @endif
            </label>

            <div class="w-full">
                @switch($field['type'])

                @case('group')
                @break

                {{-- 🎨 Color Scheme Selector --}}
                @case('color_scheme_selector')
                <div class="color-scheme-selector border-primary border-rounded pr-1">
                    <select name="color_scheme_id"
                        class="w-full p-2 text-sm focus:outline-none live-input transition-all">
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
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm resize-none transition-all">{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}</textarea>
                @break

                {{-- 📋 Select Dropdown --}}
                @case('select')
                <select name="settings[{{ $field['key'] }}]"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">

                    @foreach($field['options'] ?? [] as $option)
                    @php
                    // Check if the option is an array (Has label/value) or a simple string
                    if (is_array($option)) {
                    $value = $option['value'];
                    $label = $option['label'] ?? $value; // Fallback to value if label is missing
                    } else {
                    $value = $option;
                    // Format simple string for display (remove _, - and capitalize)
                    $label = ucfirst(str_replace(['_', '-'], ' ', $option));
                    }

                    // Check if this option is currently selected
                    $currentSetting = $section->settings[$field['key']] ?? $field['default'] ?? '';
                    // Convert both to string for safe comparison
                    $isSelected = (string)$currentSetting === (string)$value;
                    @endphp

                    <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $label }}
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
                    <p class="text-sm text-gray-500 mt-1">
                        Hold <kbd class="px-1.5 py-0.5 bg-gray-200 border-rounded text-sm">Ctrl</kbd> (or <kbd class="px-1.5 py-0.5 bg-gray-200 border-rounded text-sm">Cmd</kbd>) to select multiple.
                    </p>
                </div>
                @else
                <textarea name="settings[{{ $field['key'] }}]"
                    rows="4"
                    class="border-primary border-rounded w-full p-2.5 text-sm bg-gray-50 focus:ring-2 focus:ring-accent focus:outline-none font-mono transition-all">{{ json_encode($section->settings[$field['key']] ?? $field['value'] ?? '', JSON_PRETTY_PRINT) }}</textarea>
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
                <label class="flex items-center justify-between p-2 bg-primary border-primary border-rounded cursor-pointer transition-all">
                    <span class="text-xs text-gray-700 font-medium">{{ $field['text'] ?? 'Enable' }}</span>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="settings[{{ $field['key'] }}]" value="0">
                        <input type="checkbox" name="settings[{{ $field['key'] }}]" value="1" class="sr-only peer live-input" {{ !empty($section->settings[$field['key']]) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                    </div>
                </label>
                @break

                {{-- 🎨 Color Picker --}}
                @case('color')
                <div class="flex items-start gap-2">
                    {{-- Color Picker --}}
                    <div class="relative group">
                        <input type="color"
                            id="colorPicker-{{ $field['key'] }}"
                            value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                            class="w-15 h-9.5 border-primary border-rounded cursor-pointer color-input live-input transition-all hover:scale-105"
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
                        class="w-full p-2 border-primary border-rounded text-sm live-input uppercase font-mono focus:ring-2 focus:ring-accent focus:outline-none transition-all"
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
                    <label class="flex-1 cursor-pointer relative group">
                        <input type="radio"
                            name="settings[{{ $field['key'] }}]"
                            value="{{ $value }}"
                            {{ ($section->settings[$field['key']] ?? $field['default']) == $value ? 'checked' : '' }}
                            class="sr-only peer live-input">

                        <span class="block p-2 text-sm capitalize text-center
                         transition-all duration-200
                         peer-checked:bg-black
                         peer-checked:text-white
                         hover:bg-gray-100
                         peer-checked:hover:bg-gray-900">
                            {{ ucfirst(str_replace(['_', '-'], ' ', $value)) }}
                        </span>
                    </label>
                    @endforeach
                </div>

                @break

                @case('font_weight')
                <div class="flex border-rounded border-primary overflow-hidden">

                    @foreach(['normal' => 'font-normal', 'medium' => 'font-semibold', 'bold' => 'font-bold'] as $value => $class)
                    <label class="flex-1 cursor-pointer relative group">
                        <input type="radio"
                            name="settings[{{ $field['key'] }}]"
                            value="{{ $value }}"
                            {{ ($section->settings[$field['key']] ?? $field['default']) === $value ? 'checked' : '' }}
                            class="sr-only peer live-input">
                        <span class="block p-1.5 text-center text-sm transition-all duration-200 {{ $class }} peer-checked:bg-black peer-checked:text-white hover:bg-gray-100 peer-checked:hover:bg-gray-900">
                            Aa
                        </span>
                    </label>
                    @endforeach

                </div>
                @break


                {{-- 🖼️ Image Upload --}}
                @case('image')
                @php
                $hasImage = $section->settings[$field['key']] ?? null;
                @endphp
                <div class="media-field-{{ $field['key'] }}_{{ $section->id }} relative group overflow-hidden">
                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-invert transition-all z-10 opacity-0 group-hover:opacity-100 absolute top-1 right-1"
                        onclick="deleteSectionMedia('{{ $field['key'] }}_{{ $section->id }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>

                    <!-- Upload Area -->
                    <div data-content-wrapper>
                        <input type="hidden" name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}_{{ $section->id }}"
                            @if($hasImage)
                            value="{{ $hasImage }}"
                            @endif>

                        <div class="border-primary border-rounded cursor-pointer group relative overflow-hidden"
                            style="border-style: dashed; border-width: 2px;"
                            onclick="openContentPicker('{{ $field['key'] }}_{{ $section->id }}', 'image')">

                            <img data-content-preview @if($hasImage !==null ) src="{{ media($hasImage) }}" @endif
                                class="{{ $hasImage === null ? 'hidden' : '' }} w-full h-auto object-contain border-rounded">

                            <div data-content-placeholder
                                class="flex flex-col text-[12px] items-center text-tertiary w-full aspect-video justify-center {{ $hasImage === null ? '' : 'hidden' }}">
                                <i class="fa-solid fa-image text-3xl mb-2"></i>
                                Upload {{ $field['label'] }}
                            </div>
                            <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/80 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <span class="text-[12px] text-invert">Upload/Change image</span>
                            </div>
                        </div>
                    </div>
                </div>
                @break


                @case('video')
                @php
                $hasVideo = $section->settings[$field['key']] ?? null;
                @endphp
                <div class="media-field-{{ $field['key'] }}_{{ $section->id }} relative group overflow-hidden">
                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-invert transition-all z-10 opacity-0 group-hover:opacity-100 absolute top-1 right-1"
                        onclick="deleteSectionMedia('{{ $field['key'] }}_{{ $section->id }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>

                    <!-- Upload Area -->
                    <div data-content-wrapper>
                        <input type="hidden" name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}_{{ $section->id }}"
                            @if($hasVideo)
                            value="{{ $hasVideo }}"
                            @endif>

                        <div class="border-primary border-rounded cursor-pointer group relative overflow-hidden"
                            style="border-style: dashed; border-width: 2px;"
                            onclick="openContentPicker('{{ $field['key'] }}_{{ $section->id }}', 'video')">

                            <video data-content-preview @if($hasVideo !==null ) src="{{ media($hasVideo) }}" @endif
                                class="{{ $hasVideo === null ? 'hidden' : '' }} w-full h-auto object-contain border-rounded"
                                muted autoplay loop preload=" metadata">
                            </video>

                            <div data-content-placeholder
                                class=" flex flex-col text-[12px] items-center text-tertiary w-full aspect-video justify-center {{ $hasVideo === null ? '' : 'hidden' }}">
                                <i class="fa-solid fa-video text-3xl mb-2"></i>
                                Upload {{ $field['label'] }}
                            </div>
                            <div class="flex w-full h-full absolute left-0 top-0 items-center justify-center bg-black/80 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <span class="text-[12px] text-invert">Upload/Change Video</span>
                            </div>
                        </div>
                    </div>
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
                <p class="text-sm text-gray-500 mt-1.5 italic">
                    <i class="fa-solid fa-circle-info mr-1"></i>{{ $field['help'] }}
                </p>
                @endif
            </div>
        </div>
        @endforeach
    </form>
    @endif
</div>

<script>
    // Handle conditional field visibility
    document.addEventListener("turbo:load", function() {
        const form = document.querySelector(`#edit-form-{{ $section->id }} .editSectionForm`);
        if (!form) return;

        function getValue(input) {
            if (!input) return null;

            if (input.type === "radio") {
                const checked = form.querySelector(`input[name="${input.name}"]:checked`);
                return checked ? checked.value : null;
            }
            if (input.type === "checkbox") {
                return input.checked ? input.value : "0";
            }

            // for hidden input (ignore if checkbox exists)
            if (input.type === "hidden") {
                const checkbox = form.querySelector(`input[type="checkbox"][name="${input.name}"]`);
                if (checkbox) {
                    return checkbox.checked ? checkbox.value : "0";
                }
                return input.value;
            }
            return input.value;
        }

        function evaluateCondition(controlValue, operator, expected) {
            switch (operator) {
                case '==':
                case '=':
                    return String(controlValue) === String(expected);

                case '!=':
                    return String(controlValue) !== String(expected);

                case 'in':
                    return Array.isArray(expected) && expected.includes(controlValue);

                case 'not_in':
                    return Array.isArray(expected) && !expected.includes(controlValue);

                default:
                    return String(controlValue) === String(expected);
            }
        }

        function updateConditionalFields() {
            const conditionalFields = form.querySelectorAll('[data-conditions]');

            conditionalFields.forEach(field => {
                let conditions = JSON.parse(field.dataset.conditions);
                let shouldShow = true;

                // 🔁 OR condition support
                if (conditions.operator === 'or' && Array.isArray(conditions.rules)) {
                    shouldShow = false;

                    conditions.rules.forEach(rule => {
                        const control = form.querySelector(
                            `[name="settings[${rule.field}]"], [name="${rule.field}"]`
                        );

                        const value = getValue(control);
                        if (evaluateCondition(value, rule.operator ?? '==', rule.value)) {
                            shouldShow = true;
                        }
                    });

                } else {
                    // 🔁 AND condition (default)
                    if (!Array.isArray(conditions)) {
                        conditions = [conditions];
                    }

                    conditions.forEach(cond => {
                        const control = form.querySelector(
                            `[name="settings[${cond.field}]"], [name="${cond.field}"]`
                        );

                        const value = getValue(control);
                        const operator = cond.operator ?? '==';

                        if (!evaluateCondition(value, operator, cond.value)) {
                            shouldShow = false;
                        }
                    });
                }

                // 🎯 Apply result
                if (shouldShow) {
                    field.style.display = 'block';
                    field.style.opacity = '1';
                    field.querySelectorAll('input,select,textarea')
                        .forEach(el => el.disabled = false);
                } else {
                    field.style.display = 'none';
                    field.style.opacity = '0';
                    field.querySelectorAll('input,select,textarea')
                        .forEach(el => el.disabled = true);
                }
            });
        }
        // First load
        updateConditionalFields();

        // Live update listeners
        form.addEventListener("input", updateConditionalFields);
        form.addEventListener("change", updateConditionalFields);
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
        }, 200);
    }

    function submitSectionForm(form) {
        const sectionId = form.dataset.sectionId;
        const formData = new FormData(form);

        // Add empty string for empty image fields to ensure they get cleared
        const imageInputs = form.querySelectorAll('input[type="hidden"][id$="Input"]');
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

    function deleteSectionMedia(key) {

        const wrapper = document.querySelector('.media-field-' + key);
        if (!wrapper) return;

        // Preview image
        const preview = wrapper.querySelector('[data-content-preview]');
        // Placeholder
        const placeholder = wrapper.querySelector('[data-content-placeholder]');
        // Hidden input
        const input = wrapper.querySelector('input');

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
        const id = window.currentOpenSectionId;
        const form = document.querySelector('#edit-form-' + id + ' .editSectionForm');
        if (!form) return;

        submitSectionForm(form);
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