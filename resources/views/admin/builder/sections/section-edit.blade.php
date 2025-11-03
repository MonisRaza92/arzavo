<div id="edit-form-{{ $section->id }}" class="hidden bg-primary border-top p-4">
    @php
    $schema = collect($availableSections)->firstWhere('type', $section->type);
    $fields = $schema['fields'] ?? [];
    @endphp

    @if(count($fields) > 0)
    <form class="editSectionForm space-y-4" data-section-id="{{ $section->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($fields as $field)
        <div class="field-item flex justify-between items-center gap-4 border-bottom pb-3">
            <label class="block text-xs font-semibold text-primary text-left w-1/3">
                {{ $field['label'] ?? ucfirst($field['key']) }}
            </label>

            <div class="w-2/3">
                @switch($field['type'])

                {{-- 🟢 Text Input --}}
                @case('text')
                <input type="text" name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}"
                    placeholder="{{ $field['placeholder'] ?? 'Enter text...' }}"
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm">
                @break

                {{-- 🟣 Textarea --}}
                @case('textarea')
                <textarea name="settings[{{ $field['key'] }}]" rows="3"
                    placeholder="{{ $field['placeholder'] ?? 'Write something...' }}"
                    class="w-full p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm">{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}</textarea>
                @break

                {{-- 🟠 Select Dropdown --}}
                @case('select')
                <select name="settings[{{ $field['key'] }}]"
                    class="w-full capitalize p-2 border-primary border-rounded focus:ring-2 focus:ring-accent focus:outline-none live-input text-sm">
                    @foreach($field['options'] ?? [] as $value)
                    <option class="capitalize" value="{{ $value }}" {{ ($section->settings[$field['key']] ?? '') === $value ? 'selected' : '' }}>
                        {{ $value }}
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
                    <select name="settings[{{ $field['key'] }}][]" multiple
                        class="w-full border-primary border-rounded p-2 focus:ring-2 focus:ring-accent focus:outline-none text-sm bg-white">
                        @foreach($pages as $page)
                        <option value="{{ $page->slug }}" {{ in_array($page->slug, $selectedLinks) ? 'selected' : '' }}>
                            {{ $page->name }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Hold <kbd>Ctrl</kbd> (or <kbd>Cmd</kbd>) to select multiple pages.
                    </p>
                </div>
                @else
                <textarea name="settings[{{ $field['key'] }}]"
                    class="border-primary border-rounded w-full p-2 text-sm bg-gray-50 focus:ring-2 focus:ring-accent focus:outline-none">{{ json_encode($section->settings[$field['key']] ?? $field['value'] ?? '', JSON_PRETTY_PRINT) }}</textarea>
                @endif
                @break

                {{-- 🟡 Checkbox --}}
                @case('checkbox')
                <label class="inline-flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" name="settings[{{ $field['key'] }}]" value="1"
                        {{ !empty($section->settings[$field['key']]) ? 'checked' : '' }}
                        class="border-rounded border-primary accent-accent w-4 h-4 live-input">
                    <span>{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                {{-- 🔵 Toggle Switch --}}
                @case('switch')
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="settings[{{ $field['key'] }}]"
                        class="sr-only peer live-input"
                        {{ !empty($section->settings[$field['key']]) ? 'checked' : '' }}>
                    <div class="w-10 h-5 bg-gray-300 border-rounded peer peer-checked:bg-accent transition-all"></div>
                    <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 border-rounded peer-checked:translate-x-5 transition-all"></div>
                    <span class="ml-3 text-xs text-primary">{{ $field['text'] ?? 'Enable' }}</span>
                </label>
                @break

                {{-- 🟢 Color Picker --}}
                @case('color')
                <div class="flex items-center gap-3">
                    {{-- 🎨 Color Picker --}}
                    <input type="color"
                        id="colorPicker-{{ $field['key'] }}"
                        value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}"
                        class="w-14 h-10 border-primary color-input border-primary border-rounded cursor-pointer live-input"
                        oninput="this.nextElementSibling.value = this.value">

                    {{-- 🔢 Hex Input --}}
                    <input type="text"
                        id="colorInput-{{ $field['key'] }}"
                        name="settings[{{ $field['key'] }}]"
                        value="{{ $section->settings[$field['key']] ?? $field['default'] ?? '' }}"
                        class="w-24 p-2 border-primary border-rounded text-sm live-input uppercase"
                        oninput="this.previousElementSibling.value = this.value">
                </div>
                @break


                {{-- 🟠 Range Slider --}}
                @case('range')
                <div class="flex items-center gap-3">
                    <input type="range" min="{{ $field['min'] ?? 0 }}" max="{{ $field['max'] ?? 100 }}"
                        value="{{ $section->settings[$field['key']] ?? $field['default'] ?? 50 }}"
                        name="settings[{{ $field['key'] }}]"
                        class="w-full range-black live-input range-slider"
                        oninput="this.nextElementSibling.textContent = this.value">
                    <span class="text-sm w-10 text-right range-value">
                        {{ $section->settings[$field['key']] ?? $field['default'] ?? 50 }}
                    </span>
                </div>
                @break

                {{-- 🔴 Number --}}
                @case('number')
                <input type="number" name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? $field['default'] ?? 0 }}"
                    min="{{ $field['min'] ?? 0 }}" max="{{ $field['max'] ?? 100 }}"
                    class="w-full p-2 border-primary border-rounded live-input text-sm">
                @break

                {{-- 🟣 Radio --}}
                @case('radio')
                <div class="flex bg-secondary border-rounded p-1">
                    @foreach($field['options'] ?? [] as $value)
                    <label class="flex-1 text-center cursor-pointer">
                        <input type="radio" name="settings[{{ $field['key'] }}]" value="{{ $value }}"
                            {{ ($section->settings[$field['key']] ?? '') == $value ? 'checked' : '' }}
                            class="peer hidden live-input">
                        <span class="block p-2 text-xs border-rounded capitalize peer-checked:bg-black peer-checked:text-white transition-all">
                            {{ $value }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @break


                {{-- 🖼️ Image Upload --}}
                @case('image')
                <div class="image-field relative group border-primary border-rounded overflow-hidden w-42" style="border-style: dashed; border-width: 2px;">
                    <label for="{{ $field['key'] }}Input" class="cursor-pointer aspect-video block">
                        <!-- Upload / Preview Area -->
                        <div class="relative bg-secondary cursor-pointer" onclick="openImageMenu('{{ $field['key'] }}Input')">
                            @php $hasImage = !empty($section->settings[$field['key']]) @endphp
                            @if ($hasImage)
                            <img id="{{ $field['key'] }}Preview"
                                src="{{ asset($section->settings[$field['key']]) }}"
                                alt="{{ $field['label'] ?? $field['key'] }}"
                                class="w-full object-contain p-2">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                <span class="text-white text-sm bg-black/50 px-3 py-1 border-rounded">Change</span>
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center w-42 aspect-video text-primary/80">
                                <i class="fa-solid fa-image text-3xl mb-2"></i>
                                <span class="text-sm">Upload {{ $field['label'] ?? $field['key'] }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Hidden Input -->
                        <input type="text" name="settings[{{ $field['key'] }}]"
                            id="{{ $field['key'] }}Input"
                            value="{{ $section->settings[$field['key']] ?? '' }}"
                            class="hidden">

                        <!-- Delete Button -->
                        @if(!empty($section->settings[$field['key']]))
                        <div class="absolute top-2.5 right-2">
                            <button type="button" class="text-tertiary"
                                onclick="document.getElementById('{{ $field['key'] }}Input').value=''; 
                     document.getElementById('{{ $field['key'] }}Preview').classList.add('hidden'); submitSectionForm(form)">
                                <i class="fa-solid fa-trash text-md"></i>
                            </button>
                        </div>
                        @endif
                    </label>
                </div>
                @break

                {{-- 🔘 Alignment --}}
                @case('alignment')
                <div class="flex items-center gap-2">
                    @foreach(['left'=>'fa-align-left','center'=>'fa-align-center','right'=>'fa-align-right'] as $align => $icon)
                    <button type="button"
                        class="p-2 border-primary border-rounded hover:bg-accent hover:text-white transition {{ ($section->settings[$field['key']] ?? 'left') === $align ? 'bg-accent text-white' : '' }}"
                        onclick="this.closest('.field-item').querySelector('input[name=\'settings[{{ $field['key'] }}]\']').value='{{ $align }}'; this.closest('.editSectionForm').dispatchEvent(new Event('input'));">
                        <i class="fa-solid {{ $icon }}"></i>
                    </button>
                    @endforeach
                    <input type="hidden" name="settings[{{ $field['key'] }}]" value="{{ $section->settings[$field['key']] ?? 'left' }}">
                </div>
                @break

                @default
                <input type="text" name="settings[{{ $field['key'] }}]"
                    value="{{ $section->settings[$field['key']] ?? '' }}"
                    class="w-full p-2 border-primary border-rounded live-input text-sm">
                @endswitch
            </div>
        </div>
        @endforeach
    </form>
    @endif
</div>
<script>
    let submitTimeout = null;

    // Listen for any input, change, or file change inside editSectionForm
    document.addEventListener('input', handleFormChange);
    document.addEventListener('change', handleFormChange);
    document.addEventListener('select', handleFormChange);

    function handleFormChange(e) {
        const form = e.target.closest('.editSectionForm');
        if (!form) return;

        // Clear old timeout (debounce)
        clearTimeout(submitTimeout);

        // Wait 800ms after last input
        submitTimeout = setTimeout(() => {
            submitSectionForm(form);
        }, 800);
    }

    function submitSectionForm(form) {
        const sectionId = form.dataset.sectionId;
        const formData = new FormData(form);

        fetch(`/admin/builder/sections/${sectionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(res => res.json()) // ✅ JSON response expect karo (controller me badla gaya)
            .then(data => {
                if (data.status === 'success') {
                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) {
                        iframe.contentWindow.location.reload(); // 🔁 reload only iframe
                    }
                } else {
                    console.error('Update failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('Live update failed:', err));
    }
</script>