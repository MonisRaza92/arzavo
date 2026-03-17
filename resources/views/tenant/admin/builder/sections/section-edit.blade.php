<div id="edit-form-{{ $section['id'] }}"
    class="section-edit-form hidden edit-form absolute top-0 left-14 bottom-0 w-75 overflow-auto h-full scrollbar bg-primary z-30">

    <div class="flex items-center justify-between p-2 border-bottom sticky top-15.5 bg-primary z-10">
        <h2 class="text-sm font-semibold text-primary py-2.25 bg-hover-primary group border-rounded flex gap-2 items-center"
            onclick="document.getElementById('edit-form-{{ $section['id'] }}').classList.add('hidden'); clearPreviewHighlights();"
            id="sectionFormClose"> <i
                class="fa-solid fa-arrow-left text-tertiary group-hover:-translate-x-1 duration-200"></i>
            {{ $section['name'] }} </h2>
        <div class="relative"> <!-- trigger --> <button class="text-lg p-1"
                onclick="toggleModel('section_opt_{{ $section['id'] }}')"> <i class="fa-solid fa-ellipsis-vertical"></i>
            </button> <!-- dropdown -->
            <div id="section_opt_{{ $section['id'] }}"
                class="hidden absolute right-0 top-[90%] flex flex-col p-4 text-sm text-secondary items-start gap-4 mt-2 w-52 bg-primary border-primary border-rounded shadow-lg z-50">
                <button type="button" class="section-menu-item" onclick="openSectionEditor('{{ $section['id'] }}')"> <i
                        class="fa-duotone fa-solid fa-code mr-2"></i> Edit Code </button>
                <button type="button" class="section-menu-item" onclick="duplicateSection('{{ $section['id'] }}')"> <i
                        class="fa-jelly fa-regular fa-clone mr-2"></i> Duplicate </button>
                <form class="delete-section-form border-top pt-3 w-full" data-section-id="{{ $section['id'] }}"
                    action="{{ route('admin.builder.sections.destroy', ['theme' => $theme->theme_slug, 'page' => $page, 'sectionId' => $section['id']]) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="section-menu-item text-red-600 delete-btn"> <i
                            class="fa-duotone fa-solid fa-trash-can mr-2"></i> Delete section </button>
                </form>
            </div>
        </div>
    </div>

    @php
        $schema = collect($availableSections)->firstWhere('schema', $section['schema'] ?? null) ?? collect($availableSections)->firstWhere('type', $section['type'] ?? null) ?? [];
        $fields = resolveFieldPresets($schema['fields'] ?? []);
    @endphp

    @if (count($fields) > 0)

        <form class="editSectionForm px-2 pt-15" data-section-id="{{ $section['id'] }}" enctype="multipart/form-data"
            action="{{ route('admin.builder.sections.update', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => $section['id']]) }}"
            method="POST">
            @csrf
            @method('PUT')

            @foreach ($fields as $field)
                <div class="field-item mb-6" data-field-key="{{ $field['key'] ?? null }}"
                    @if (isset($field['conditional'])) data-conditions='@json($field['conditional'])' @endif>

                    @switch($field['type'])
                        {{-- GROUP --}}
                        @case('group')
                            <div class="font-semibold text-primary text-sm border-top pt-4">
                                {{ $field['label'] ?? ucfirst($field['key']) }}
                            </div>
                            @if (isset($field['help']))
                                <p class="text-[11px] text-secondary mt-1">{{ $field['help'] }}</p>
                            @endif
                        @break

                        {{-- COLOR SCHEME --}}
                        @case('scheme')
                        @case('color_scheme_selector')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block mb-1">
                                    {{ $field['label'] ?? 'Color Scheme' }}
                                </label>

                                <div class="color-scheme-selector w-full border-primary border-rounded pr-1">
                                    <select name="color_scheme"
                                        class="w-full p-2 text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">
                                        @foreach ($colorSchemes as $scheme)
                                            <option value="{{ $scheme->key }}"
                                                {{ ($section['color_scheme'] ?? 'scheme_1') === $scheme->key ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace(['_', '-'], ' ', $scheme->key)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @break

                        @case('invert_color_scheme')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block mb-1">
                                    {{ $field['label'] ?? 'Invert Scheme' }}
                                </label>

                                <div class="color-scheme-selector w-full border-primary border-rounded pr-1">
                                    <select name="settings[{{ $field['key'] }}]"
                                        class="w-full p-2 text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">
                                        @foreach ($colorSchemes as $scheme)
                                            <option value="{{ $scheme->key }}"
                                                {{ ($section['settings'][$field['key']] ?? ($field['default'] ?? null)) === $scheme->key ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace(['_', '-'], ' ', $scheme->key)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @break

                        @case('menu')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? 'Select Menu' }}
                                </label>
                                <div class="border-primary w-full border-rounded pr-1"> <select
                                        name="settings[{{ $field['key'] }}]"
                                        class="w-full p-2 text-xs focus:ring-0 focus:outline-none live-input transition-all"
                                        {{ $field['required'] ?? false ? 'required' : '' }}>
                                        @foreach ($menus as $menu)
                                            <option value="{{ $menu->id }}"
                                                {{ ($section['settings'][$field['key']] ?? ($field['default'] ?? null)) == $menu->id ? 'selected' : '' }}>
                                                {{ $menu->name }} </option>
                                        @endforeach
                                    </select> </div>
                                @if (empty($menus) || $menus->count() === 0)
                                    <p class="text-sm text-red-500 mt-1"> No menus found. Create a menu first. </p>
                                @endif
                            </div>
                        @break

                        {{-- RICH TEXT --}}
                        @case('richtext')
                            <x-input.rich-text :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? '')" />
                        @break
                        
                        {{-- HIDDEN --}}
                        @case('hidden')
                            <input type="hidden" :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? '')" />
                        @break

                        {{-- SELECT --}}
                        @case('select')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.select :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? '')" :options="$field['options'] ?? []" />
                            </div>
                        @break

                        @case('icon')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.icon-select :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? 'none')" />

                            </div>
                        @break

                        {{-- URL --}}
                        @case('url')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.url :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                            </div>
                        @break

                        @case('link')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.url :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                            </div>
                        @break

                        @case('textarea')
                            <label class="text-xs font-semibold text-secondary uppercase block mb-1">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.textarea :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                        @break

                        {{-- CHECKBOX --}}
                        @case('checkbox')
                            <label class="text-xs font-semibold text-secondary uppercase block mb-1">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.checkbox :name="'settings[' . $field['key'] . ']'" :checked="!empty($section['settings'][$field['key']])" />
                        @break

                        {{-- SWITCH --}}
                        @case('switch')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-9/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.switch containerClass="w-3/12!" :name="'settings[' . $field['key'] . ']'" :checked="!empty($section['settings'][$field['key']])" />
                            </div>
                        @break

                        {{-- NUMBER --}}
                        @case('number')
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.number class="text-xs!" :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? 0)" :min="$field['min'] ?? 0"
                                :max="$field['max'] ?? 100" />
                        </div>
                    @break

                    {{-- COLOR --}}
                    @case('color')
                        <div class="flex items-center justify-between gap-4">
                            <label class="text-xs w-7/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.color :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? '#000000')" :gradient="true" />
                        </div>
                    @break

                    {{-- RANGE --}}
                    @case('range')
                        <div class="flex items-start justify-between gap-6 pt-1">
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.range :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? 50)" :min="$field['min'] ?? 0" :max="$field['max'] ?? 100" />
                        </div>
                    @break

                    {{-- RADIO --}}
                    @case('radio')
                        <div class="flex items-center justify-between gap-6">
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.radio :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ($field['default'] ?? '')" :options="$field['options'] ?? []" />
                        </div>
                    @break

                    {{-- IMAGE --}}
                    @case('image')
                        <div class="flex items-center justify-between gap-6">
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.image :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                        </div>
                    @break

                    {{-- VIDEO --}}
                    @case('video')
                        <div class="flex items-center justify-between gap-6">
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.video :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                        </div>
                    @break

                    {{-- DEFAULT --}}

                    @default
                        <div class="flex items-center justify-between gap-6">
                            <label class="text-xs w-8/12 text-secondary block">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.text :name="'settings[' . $field['key'] . ']'" :value="$section['settings'][$field['key']] ?? ''" />
                        </div>
                @endswitch

                @if (isset($field['help']) && $field['type'] !== 'group')
                    <p class="text-[11px] text-secondary mt-3">{{ $field['help'] }}</p>
                @endif

</div>
@endforeach

</form>

@endif
</div>
