<div id="edit-block-form-{{ $block->id }}" class="edit-block-form hidden fixed top-0 left-0 bottom-0 w-[299px] pt-29 z-10 overflow-auto scrollbar bg-primary">
    <div class="flex items-center justify-between p-2 border-bottom sticky top-0 bg-primary z-10">
        <h2 class="text-sm font-semibold text-primary p-2 bg-hover-primary border-rounded flex gap-2 items-center curser-pointer" id="blockFormClose" onclick="document.getElementById('edit-block-form-{{ $block->id }}').classList.add('hidden'); clearPreviewHighlights();">
            <i class="fa-solid fa-arrow-left text-tertiary"></i> {{ $block->name }}
        </h2>
        <div class="flex items-center">
            {{-- ACTIVE/INACTIVE --}}
            <button type="button"
                class="toggle-block-active text-tertiary text-sm bg-hover-secondary p-1 border-rounded"
                data-block-id="{{ $block->id }}">
                @if($block->is_active)
                <i class="fa-solid fa-eye"></i>
                @else
                <i class="fa-solid fa-eye-slash"></i>
                @endif
            </button>

            {{-- DELETE --}}
            <form class="delete-block-form" data-block-id="{{ $block->id }}"
                action="{{ route('admin.builder.sections.blocks.destroy', $block->id) }}"
                method="POST">
                @csrf
                @method('DELETE')

                <button type="button"
                    class="delete-block-btn text-tertiary bg-hover-secondary p-1 border-rounded text-sm">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>

        </div>
    </div>
    @php
    $schema = collect($availableBlocks)->firstWhere('type', $block->type);
    $fields = $schema['fields'] ?? [];
    @endphp

    @if(count($fields) > 0)
    <form class="editBlockForm" data-block-id="{{ $block->id }}" enctype="multipart/form-data">
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


        <div class="field-item flex justify-between items-center p-4 gap-4 {{ $field['key'] === 'group' ? 'border-bottom border-top' : '' }} transition-all duration-300"
            data-field-key="{{ $field['key'] }}"
            @if(isset($field['conditional']))
            data-conditions='@json($field["conditional"])'
            @endif>

            <label class="block text-[12px] {{ $field['key'] === 'group' ? 'font-semibold' : '' }} text-primary text-left w-1/3">
                {{ $field['label'] ?? ucfirst($field['key']) }}
                @if($field['required'] ?? false)
                <span class="text-red-500">*</span>
                @endif
            </label>

            <div class="w-2/3">
                @switch($field['type'])

                @case('group')
                @break

                @case('color_scheme_selector')
                <div class="color-scheme-selector border-primary border-rounded pr-1">
                    <select name="color_scheme_id"
                        class="w-full p-2 text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">
                        @foreach($colorSchemes as $scheme)
                        <option value="{{ $scheme->id }}"
                            {{ $block->color_scheme_id == $scheme->id ? 'selected' : '' }}>
                            {{ $scheme->name ?? "Scheme $scheme->id" }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @break
                @case('menu')
                <div class="border-primary border-rounded pr-1">
                    <select name="settings[{{ $field['key'] }}]"
                        class="w-full p-2 text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all"
                        {{ ($field['required'] ?? false) ? 'required' : '' }}>
                        @foreach($menus as $menu)
                        <option value="{{ $menu->id }}"
                            {{ ($block->settings[$field['key']] ?? $field['default'] ?? null) == $menu->id ? 'selected' : '' }}>
                            {{ $menu->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if(empty($menus) || $menus->count() === 0)
                <p class="text-xs text-red-500 mt-1">
                    No menus found. Create a menu first.
                </p>
                @endif
                @break


                @case('text')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                @case('link')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                @case('textarea')
                <textarea name="settings[{{ $field['key'] }}]"
                    rows="{{ $field['rows'] ?? 3 }}"
                    placeholder="{{ $field['placeholder'] ?? 'Write something...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs resize-none transition-all">{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}</textarea>
                @break

                @case('select')
                <select name="settings[{{ $field['key'] }}]"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full capitalize p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs transition-all">
                    @foreach($field['options'] ?? [] as $value)
                    <option class="capitalize"
                        value="{{ $value }}"
                        {{ ($block->settings[$field['key']] ?? $field['default'] ?? '') === $value ? 'selected' : '' }}>
                        {{ ucfirst(str_replace(['_', '-'], ' ', $value)) }}
                    </option>
                    @endforeach
                </select>
                @break

                @case('icon')
                <select name="settings[{{ $field['key'] }}]"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full capitalize p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs transition-all">

                    @foreach(config('icons') as $icon)
                    <option value="{{ $icon }}"
                        {{ ($block->settings[$field['key']] ?? $field['default'] ?? '') === $icon ? 'selected' : '' }}>
                        {{ $icon }}
                    </option>
                    @endforeach

                </select>
                @break


                @case('checkbox')
                <label class="flex items-center gap-3 cursor-pointer select-none group">

                    {{-- Hidden input ensures 0 is sent when unchecked --}}
                    <input type="hidden" name="settings[{{ $field['key'] }}]" value="0">

                    {{-- BEAUTIFUL TOGGLE CHECKBOX --}}
                    <div class="relative inline-flex items-center">
                        <input type="checkbox"
                            name="settings[{{ $field['key'] }}]"
                            value="1"
                            {{ !empty($block->settings[$field['key']]) ? 'checked' : '' }}
                            class="sr-only peer live-input">

                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-accent transition-all duration-300 shadow-inner"></div>

                        <div class="absolute left-0.5 top-1/2 -translate-y-1/2 w-5 h-5 bg-white rounded-full shadow-sm
                    transition-all duration-300 peer-checked:translate-x-5"></div>
                    </div>

                    {{-- LABEL --}}
                    <span class="text-sm font-medium text-primary group-hover:text-accent transition-colors">
                        {{ $field['text'] ?? 'Enable' }}
                    </span>

                </label>
                @break


                @case('switch')
                <label class="relative inline-flex items-center cursor-pointer group">
                    <input type="hidden"
                        name="settings[{{ $field['key'] }}]"
                        value="0">
                    <input type="checkbox"
                        name="settings[{{ $field['key'] }}]"
                        value="1"
                        class="sr-only peer live-input"
                        {{ !empty($block->settings[$field['key']]) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-300 rounded-full shrink-0 peer peer-checked:bg-black transition-all duration-300"></div>
                    <div class="absolute left-0.75 top-1/2 transform -translate-y-1/2 bg-white w-5 h-5 shrink-0 rounded-full shadow-sm peer-checked:translate-x-4.75 transition-all duration-300"></div>
                    <span class="ml-3 text-[11px] font-semibold text-primary group-hover:text-accent transition-colors">{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                @case('color')
                <div class="flex items-start gap-2">
                    <div class="relative group">
                        <input type="color"
                            id="colorPicker-block-{{ $block->id }}-{{ $field['key'] }}"
                            value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                            class="w-15 h-9.5 border-primary color-input border-rounded cursor-pointer live-input transition-all hover:scale-105"
                            oninput="document.getElementById('colorInput-block-{{ $block->id }}-{{ $field['key'] }}').value = this.value">
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-accent border-rounded pointer-events-none transition-all"></div>
                    </div>
                    <input type="text"
                        id="colorInput-block-{{ $block->id }}-{{ $field['key'] }}"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                        maxlength="7"
                        pattern="^#[0-9A-Fa-f]{6}$"
                        class="w-25 p-2 border-primary border-rounded text-sm live-input uppercase font-mono focus:ring-2 focus:ring-accent focus:outline-none transition-all"
                        oninput="if(this.value.match(/^#[0-9A-Fa-f]{6}$/)) document.getElementById('colorPicker-block-{{ $block->id }}-{{ $field['key'] }}').value = this.value">
                </div>
                @break

                @case('range')
                <div class="flex items-center gap-3">
                    <input type="range"
                        min="{{ $field['min'] ?? 0 }}"
                        max="{{ $field['max'] ?? 100 }}"
                        step="{{ $field['step'] ?? 1 }}"
                        value="{{ $block->settings[$field['key']] ?? $field['default'] ?? 50 }}"
                        name="settings[{{ $field['key'] }}]"
                        class="w-full range-black live-input range-slider transition-all"
                        oninput="this.nextElementSibling.textContent = this.value">
                    <span class="text-sm font-semibold w-12 text-right range-value bg-gray-100 px-2 py-1 border-rounded">
                        {{ $block->settings[$field['key']] ?? $field['default'] ?? 50 }}
                    </span>
                </div>
                @break

                @case('number')
                <input type="number"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? $field['default'] ?? 0 }}"
                    min="{{ $field['min'] ?? 0 }}"
                    max="{{ $field['max'] ?? 100 }}"
                    step="{{ $field['step'] ?? 1 }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded live-input text-sm focus:ring-2 focus:ring-accent focus:outline-none transition-all">
                @break

                @case('radio')
                <div class="flex border-rounded border-primary overflow-hidden">
                    @foreach($field['options'] ?? [] as $value)
                    <label class="flex-1 cursor-pointer relative group">
                        <input type="radio"
                            name="settings[{{ $field['key'] }}]"
                            value="{{ $value }}"
                            {{ ($block->settings[$field['key']] ?? $field['default']) == $value ? 'checked' : '' }}
                            class="sr-only peer live-input">

                        <span class="block p-2 text-xs capitalize text-center
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
                            {{ ($block->settings[$field['key']] ?? $field['default']) === $value ? 'checked' : '' }}
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
                $hasImage = $block->settings[$field['key']] ?? null;
                @endphp
                <div class="media-field-{{ $field['key'] }}_{{ $block->id }} relative group overflow-hidden">
                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-invert transition-all z-10 opacity-0 group-hover:opacity-100 absolute top-1 right-1"
                        onclick="deleteBlockMedia('{{ $field['key'] }}_{{ $block->id }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>

                    <!-- Upload Area -->
                    <div data-content-wrapper>
                        <input type="hidden" name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}_{{ $block->id }}"
                            @if($hasImage)
                            value="{{ $hasImage }}"
                            @endif>

                        <div class="border-primary border-rounded cursor-pointer group relative overflow-hidden"
                            style="border-style: dashed; border-width: 2px;"
                            onclick="openContentPicker('{{ $field['key'] }}_{{ $block->id }}', 'image')">

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
                $hasVideo = $block->settings[$field['key']] ?? null;
                @endphp
                <div class="media-field-{{ $field['key'] }}_{{ $block->id }} relative group overflow-hidden">
                    <!-- Delete Button - Always Visible -->
                    <button type="button"
                        class="delete-image-btn text-invert transition-all z-10 opacity-0 group-hover:opacity-100 absolute top-1 right-1"
                        onclick="deleteBlockMedia('{{ $field['key'] }}_{{ $block->id }}')">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>

                    <!-- Upload Area -->
                    <div data-content-wrapper>
                        <input type="hidden" name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}_{{ $block->id }}"
                            @if($hasVideo)
                            value="{{ $hasVideo }}"
                            @endif>

                        <div class="border-primary border-rounded cursor-pointer group relative overflow-hidden"
                            style="border-style: dashed; border-width: 2px;"
                            onclick="openContentPicker('{{ $field['key'] }}_{{ $block->id }}', 'video')">

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

                @case('alignment')
                <div class="flex items-center gap-2">
                    @foreach(['left'=>'fa-align-left','center'=>'fa-align-center','right'=>'fa-align-right'] as $align => $icon)
                    <button type="button"
                        class="flex-1 p-2.5 border-primary border-rounded hover:bg-accent hover:text-white transition-all duration-200 {{ ($block->settings[$field['key']] ?? 'left') === $align ? 'bg-accent text-white shadow-md' : 'hover:scale-105' }}"
                        onclick="this.closest('.field-item').querySelector('input[name=\'settings[{{ $field['key'] }}]\']').value='{{ $align }}'; 
                                        this.closest('.editBlockForm').dispatchEvent(new Event('input'));
                                        this.closest('.flex').querySelectorAll('button').forEach(b => b.classList.remove('bg-accent', 'text-white', 'shadow-md'));
                                        this.classList.add('bg-accent', 'text-white', 'shadow-md');">
                        <i class="fa-solid {{ $icon }}"></i>
                    </button>
                    @endforeach
                    <input type="hidden"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $block->settings[$field['key']] ?? 'left' }}">
                </div>
                @break

                @default
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? '' }}"
                    class="w-full p-2 border-primary border-rounded live-input text-sm focus:ring-2 focus:ring-accent focus:outline-none transition-all">
                @endswitch

                @if(isset($field['help']))
                <p class="text-xs text-gray-500 mt-1.5 italic">
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
    document.addEventListener("turbo:load", function() {
        const form = document.querySelector(`#edit-block-form-{{ $block->id }} .editBlockForm`);
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

        function updateConditionalFields() {
            const conditionalFields = form.querySelectorAll("[data-conditions]");

            conditionalFields.forEach((field) => {
                let conditions = JSON.parse(field.dataset.conditions);

                // backward compatibility
                if (!Array.isArray(conditions)) {
                    conditions = [conditions];
                }

                let shouldShow = true;

                conditions.forEach(cond => {
                    const control = form.querySelector(
                        `[name="settings[${cond.field}]"], [name="${cond.field}"]`
                    );

                    const value = getValue(control);

                    if (String(value) !== String(cond.value)) {
                        shouldShow = false;
                    }
                });

                if (shouldShow) {
                    field.style.display = "flex";
                    field.style.opacity = "1";
                    field.querySelectorAll("input,select,textarea")
                        .forEach(el => el.disabled = false);
                } else {
                    field.style.display = "none";
                    field.style.opacity = "0";
                    field.querySelectorAll("input,select,textarea")
                        .forEach(el => el.disabled = true);
                }
            });
        }

        updateConditionalFields();

        form.addEventListener("input", updateConditionalFields);
        form.addEventListener("change", updateConditionalFields);
    });


    // Auto-submit form with debouncing
    if (typeof submitTimeout === 'undefined') {
        window.submitTimeout = null;
    }

    document.addEventListener('input', handleBlockFormChange);
    document.addEventListener('change', handleBlockFormChange);

    // Form change handler
    function handleBlockFormChange(e) {
        const form = e.target.closest('.editBlockForm');
        if (!form) return;

        // Clear old timeout (debounce)
        clearTimeout(window.submitTimeout);

        // Wait 800ms after last input
        window.submitTimeout = setTimeout(() => {
            submitBlockForm(form); // ✅ Use global function
        }, 200);
    }

    // ✅ GLOBALLY EXPOSE submitBlockForm
    function submitBlockForm(form) {

        const formBlockId = form.dataset.blockId;
        const formData = new FormData(form);

        // Add empty string for empty image fields to ensure they get cleared
        const imageInputs = form.querySelectorAll('input[type="hidden"][id$="Input"]');
        imageInputs.forEach(input => {
            if (input.value === '') {
                formData.set(input.name, '');
            }
        });

        fetch(`/admin/builder/sections/blocks/${formBlockId}`, {
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
    };


    function deleteBlockMedia(key) {

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
        const id = window.currentOpenBlockId;
        const form = document.querySelector('#edit-block-form-' + id + ' .editBlockForm');
        if (!form) return;

        submitBlockForm(form);
    }
</script>