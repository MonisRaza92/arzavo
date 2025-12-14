<div id="edit-block-form-{{ $block->id }}" class="hidden fixed top-0 left-0 bottom-0 w-[299px] pt-29 z-10 overflow-auto scrollbar bg-primary">
    <div class="flex items-center justify-between p-2 border-bottom sticky top-0 bg-primary z-10">
        <h2 class="text-sm font-semibold text-primary p-2 bg-hover-primary border-rounded flex gap-2 items-center curser-pointer" onclick="closeBlockEditForm({{ $block->id }})">
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
        if (isset($field['conditional'])) {
        $conditionalField = $field['conditional']['field'];
        $conditionalValue = $field['conditional']['value'];
        $currentValue = $block->settings[$conditionalField] ?? null;
        }
        @endphp

        <div class="field-item flex justify-between items-center gap-4 border-bottom p-4 transition-all duration-300"
            data-field-key="{{ $field['key'] }}"
            @if(isset($field['conditional']))
            data-cond-field="{{ $field['conditional']['field'] }}"
            data-cond-value="{{ $field['conditional']['value'] }}"
            @endif>

            <label class="block text-xs font-semibold text-primary text-left w-1/3">
                {{ $field['label'] ?? ucfirst($field['key']) }}
                @if($field['required'] ?? false)
                <span class="text-red-500">*</span>
                @endif
            </label>

            <div class="w-2/3">
                @switch($field['type'])

                @case('color_scheme_selector')
                <div class="color-scheme-selector">
                    <select name="color_scheme_id"
                        class="w-full p-2.5 border-primary border-rounded text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">
                        @foreach($colorSchemes as $scheme)
                        <option value="{{ $scheme->id }}"
                            {{ $block->color_scheme_id == $scheme->id ? 'selected' : '' }}>
                            {{ $scheme->name ?? "Scheme $scheme->id" }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @break

                @case('text')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                @case('link')
                <input type="text"
                    name="settings[{{ $field['key'] }}]"
                    value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? $field['default'] ?? 'Enter text...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm transition-all">
                @break

                @case('textarea')
                <textarea name="settings[{{ $field['key'] }}]"
                    rows="{{ $field['rows'] ?? 3 }}"
                    placeholder="{{ $field['placeholder'] ?? 'Write something...' }}"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs resize-none transition-all">{{ $block->settings[$field['key']] ?? $field['default'] ?? '' }}</textarea>
                @break

                @case('select')
                <select name="settings[{{ $field['key'] }}]"
                    {{ ($field['required'] ?? false) ? 'required' : '' }}
                    class="w-full capitalize p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs transition-all">
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
                    class="w-full capitalize p-2.5 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-xs transition-all">

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
                    <div class="w-11 h-6 bg-gray-300 border-rounded shrink-0 peer peer-checked:bg-black transition-all duration-300"></div>
                    <div class="absolute left-0.5 top-1/2 transform -translate-y-1/2 bg-white w-5 h-5 shrink-0 border-rounded shadow-sm peer-checked:translate-x-5 transition-all duration-300"></div>
                    <span class="ml-3 text-xs font-semibold text-primary group-hover:text-accent transition-colors">{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                @case('color')
                <div class="flex items-start gap-2">
                    <div class="relative group">
                        <input type="color"
                            id="colorPicker-block-{{ $block->id }}-{{ $field['key'] }}"
                            value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                            class="w-10 h-10.5 border-primary color-input border-rounded cursor-pointer live-input transition-all hover:scale-105"
                            oninput="document.getElementById('colorInput-block-{{ $block->id }}-{{ $field['key'] }}').value = this.value">
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-accent border-rounded pointer-events-none transition-all"></div>
                    </div>
                    <input type="text"
                        id="colorInput-block-{{ $block->id }}-{{ $field['key'] }}"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $block->settings[$field['key']] ?? $field['default'] ?? '#000000' }}"
                        maxlength="7"
                        pattern="^#[0-9A-Fa-f]{6}$"
                        class="w-30 p-2.5 border-primary border-rounded text-sm live-input uppercase font-mono focus:ring-2 focus:ring-accent focus:outline-none transition-all"
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
                    <label class="flex-1 text-center cursor-pointer group relative">
                        <input type="radio"
                            name="settings[{{ $field['key'] }}]"
                            value="{{ $value }}"
                            {{ ($block->settings[$field['key']] ?? $field['default']) == $value ? 'checked' : '' }}
                            class="peer hidden live-input">
                        <span class="block p-2.5 text-xs border-rounded capitalize peer-checked:bg-black peer-checked:text-white hover:bg-gray-100 peer-checked:hover:bg-gray-900 transition-all duration-200">
                            {{ ucfirst(str_replace(['_', '-'], ' ', $value)) }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @break

                @case('image')
                <div class="image-field-wrapper-block-{{ $block->id }}-{{ $field['key'] }} relative group border-primary border-rounded overflow-hidden bg-white hover:border-accent transition-all duration-300"
                    style="border-style: dashed; border-width: 2px;">
                    @php $hasImage = !empty($block->settings[$field['key']]) @endphp

                    <label for="block-{{ $block->id }}-{{ $field['key'] }}Input" class="cursor-pointer block relative">
                        <div id="block-{{ $block->id }}-{{ $field['key'] }}Container" class="relative bg-secondary cursor-pointer"
                            onclick="openImageMenu('block-{{ $block->id }}-{{ $field['key'] }}Input')">

                            @if ($hasImage)
                            <img id="block-{{ $block->id }}-{{ $field['key'] }}Preview"
                                src="{{ asset($block->settings[$field['key']]) }}"
                                alt="{{ $field['label'] ?? $field['key'] }}"
                                class="w-full object-contain p-2 transition-all duration-300">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 pointer-events-none">
                                <div class="text-center">
                                    <i class="fa-solid fa-camera text-white text-2xl mb-2"></i>
                                    <span class="text-white text-sm bg-black/70 px-4 py-2 border-rounded block">Change Image</span>
                                </div>
                            </div>
                            @else
                            <div id="block-{{ $block->id }}-{{ $field['key'] }}Placeholder" class="flex flex-col items-center justify-center w-42 aspect-video text-primary/80 group-hover:text-accent transition-colors duration-300">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                                <span class="text-xs font-semibold">Upload {{ $field['label'] ?? $field['key'] }}</span>
                                <span class="text-xs text-gray-400 mt-1">Click to browse</span>
                            </div>
                            @endif
                        </div>

                        <input type="text"
                            name="settings[{{ $field['key'] }}]"
                            id="block-{{ $block->id }}-{{ $field['key'] }}Input"
                            value="{{ $block->settings[$field['key']] ?? '' }}"
                            class="hidden">
                    </label>

                    @if($hasImage)
                    <button type="button"
                        id="block-{{ $block->id }}-{{ $field['key'] }}DeleteBtn"
                        class="absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100"
                        onclick="deleteBlockImage('block-{{ $block->id }}-{{ $field['key'] }}')">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    @endif
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
                    class="w-full p-2.5 border-primary border-rounded live-input text-sm focus:ring-2 focus:ring-accent focus:outline-none transition-all">
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
    function closeBlockEditForm(BlockId) {
        const form = document.getElementById(`edit-block-form-${BlockId}`);
        if (form) {
            form.classList.add("hidden");
        }
    }

    (function() {
        const blockId = {{$block->id}};
        let blockSubmitTimeout = null;

        // ✅ GLOBALLY EXPOSE submitBlockForm
        window.submitBlockForm = function(form) {
            const formBlockId = form.dataset.blockId;
            const formData = new FormData(form);

            // Handle empty image fields
            const imageInputs = form.querySelectorAll('input[type="text"][id$="Input"]');
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
                        console.log('Block updated successfully');
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
        };

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

                    if (wrapper && !document.getElementById(fieldKey + 'DeleteBtn')) {
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.id = fieldKey + 'DeleteBtn';
                        deleteBtn.className = 'absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100';
                        deleteBtn.onclick = function() {
                            deleteBlockImage(fieldKey);
                        };
                        deleteBtn.innerHTML = '<i class="fa-solid fa-trash text-xs"></i>';
                        wrapper.appendChild(deleteBtn);
                    }

                    const form = input.closest('.editBlockForm');
                    if (form) {
                        handleBlockFormChange({
                            target: input
                        });
                    }
                }
            }
        });

        // Delete Image Function
        window.deleteBlockImage = function(fieldKey) {
            event.stopPropagation();

            const input = document.getElementById(fieldKey + 'Input');
            const preview = document.getElementById(fieldKey + 'Preview');
            const deleteBtn = document.getElementById(fieldKey + 'DeleteBtn');
            const container = document.getElementById(fieldKey + 'Container');

            if (input) input.value = '';
            if (preview) preview.remove();
            if (deleteBtn) deleteBtn.remove();

            if (container) {
                container.innerHTML = `
                <div id="${fieldKey}Placeholder" class="flex flex-col items-center justify-center w-42 aspect-video text-primary/80 group-hover:text-accent transition-colors duration-300">
                    <i class="fa-solid fa-cloud-arrow-up text-4xl mb-2 group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="text-xs font-semibold">Upload Image</span>
                    <span class="text-xs text-gray-400 mt-1">Click to browse</span>
                </div>
            `;
            }

            const form = input?.closest('.editBlockForm');
            if (form) {
                window.submitBlockForm(form); // ✅ Use global function
            }
        };

        function initBlockConditional(blockId) {
            const form = document.querySelector(`.editBlockForm[data-block-id="${blockId}"]`);
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
                const conditionalFields = form.querySelectorAll("[data-cond-field]");

                conditionalFields.forEach((field) => {
                    const dependOn = field.dataset.condField;
                    const expect = field.dataset.condValue;

                    const control = form.querySelector(
                        `[name="settings[${dependOn}]"], [name="${dependOn}"]`
                    );

                    const value = getValue(control);

                    if (value === expect) {
                        field.style.display = "flex";
                        field.style.opacity = "1";
                    } else {
                        field.style.display = "none";
                        field.style.opacity = "0";
                    }
                });
            }

            updateConditionalFields();

            form.addEventListener("input", updateConditionalFields);
            form.addEventListener("change", updateConditionalFields);
        }

        // Form change handler
        function handleBlockFormChange(e) {
            const form = e.target.closest('.editBlockForm');
            if (!form || form.dataset.blockId != blockId) return;

            clearTimeout(blockSubmitTimeout);
            blockSubmitTimeout = setTimeout(() => {
                window.submitBlockForm(form); // ✅ Use global function
            }, 150);
        }

        // Update delete buttons
        function updateImageDeleteButtons(form) {
            const imageInputs = form.querySelectorAll('input[type="text"][id$="Input"]');

            imageInputs.forEach(input => {
                const fieldKey = input.id.replace('Input', '');
                const hasImage = input.value && input.value.trim() !== '';
                const wrapper = input.closest('[class*="image-field-wrapper-"]');
                const existingDeleteBtn = document.getElementById(fieldKey + 'DeleteBtn');

                if (hasImage && !existingDeleteBtn && wrapper) {
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.id = fieldKey + 'DeleteBtn';
                    deleteBtn.className = 'absolute top-2 right-2 z-20 bg-red-500 text-white w-8 h-8 flex items-center justify-center border-rounded shadow-lg hover:bg-red-600 hover:scale-110 transition-all duration-200 opacity-0 group-hover:opacity-100';
                    deleteBtn.onclick = function() {
                        deleteBlockImage(fieldKey);
                    };
                    deleteBtn.innerHTML = '<i class="fa-solid fa-trash text-xs"></i>';
                    wrapper.appendChild(deleteBtn);
                } else if (!hasImage && existingDeleteBtn) {
                    existingDeleteBtn.remove();
                }
            });
        }

        // Initialize on DOM load
        document.addEventListener("turbo:load", function() {
            initBlockConditional({{$block->id}});
            const form = document.querySelector(`.editBlockForm[data-block-id="{{ $block->id }}"]`);
            if (!form) return;

            form.addEventListener("input", handleBlockFormChange);
            form.addEventListener("change", handleBlockFormChange);
        });
    })();
</script>