<div id="edit-block-form-{{ $block['id'] }}"
    class="edit-block-form hidden fixed top-0 left-14 w-75 h-full z-30 overflow-auto scrollbar bg-primary">
    <div class="flex items-center justify-between p-2 border-bottom sticky top-15.5 bg-primary z-10">
        <h2 class="block-editor-header text-sm font-semibold text-primary py-2.25 group bg-hover-primary border-rounded flex gap-2 items-center curser-pointer"
            id="blockFormClose"
            onclick="document.getElementById('edit-block-form-{{ $block['id'] }}').classList.add('hidden'); clearPreviewHighlights();">
            <i class="fa-solid fa-arrow-left text-tertiary group-hover:-translate-x-1 duration-200"></i>
            {{ $block['name'] }}
        </h2>
        <div class="relative"> <!-- trigger --> <button class="text-lg p-1"
                onclick="toggleModel('block_opt_{{ $block['id'] }}')"> <i class="fa-solid fa-ellipsis-vertical"></i>
            </button> <!-- dropdown -->
            <div id="block_opt_{{ $block['id'] }}"
                class="hidden absolute right-0 top-[90%] flex flex-col p-4 text-sm text-secondary items-start gap-4 mt-2 w-52 bg-primary border-primary border-rounded shadow-lg z-50">
                <button type="button" class="block-menu-item" onclick="openBlockEditor('{{ $block['id'] }}')"> <i
                        class="fa-duotone fa-solid fa-code mr-2"></i> Edit Code </button>
                <button type="button" class="block-menu-item" onclick="duplicateBlock('{{ $block['id'] }}')"> <i
                        class="fa-jelly fa-regular fa-clone mr-2"></i> Duplicate </button>
                <form class="delete-block-form border-top pt-3 w-full" data-block-id="{{ $block['id'] }}"
                    action="{{ route('admin.builder.sections.blocks.destroy', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => $section['id'], 'blockId' => $block['id']]) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="section-menu-item text-red-600 delete-btn"> <i
                            class="fa-duotone fa-solid fa-trash-can mr-2"></i> Delete block </button>
                </form>
            </div>
        </div>
    </div>
    @php
        $schema = collect($availableBlocks)->firstWhere('schema', $block['schema'] ?? null) ?? collect($availableBlocks)->firstWhere('type', $block['type'] ?? null) ?? [];
        $fields = $schema['fields'] ?? [];
    @endphp


    @if (count($fields) > 0)

        <form class="editBlockForm px-2 pt-15" data-block-id="{{ $block['id'] }}" enctype="multipart/form-data"
            action="{{ route('admin.builder.sections.blocks.update', ['theme' => $theme->id, 'page' => $page->id, 'sectionId' => $section['id'], 'blockId' => $block['id']]) }}"
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

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? 'Color Scheme' }}
                                </label>

                                <div class="color-scheme-selector w-full border-primary border-rounded pr-1">

                                    <select name="color_scheme"
                                        class="w-full p-2 text-xs focus:ring-2 focus:ring-accent focus:outline-none live-input transition-all">

                                        @foreach ($colorSchemes as $scheme)
                                            <option value="{{ $scheme->key }}"
                                                {{ ($block['color_scheme'] ?? 'scheme_1') === $scheme->key ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace(['_', '-'], ' ', $scheme->key)) }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        @break

                        @case('menu')
                        @case('menu_selector')
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
                                                {{ ($block['settings'][$field['key']] ?? ($field['default'] ?? null)) == $menu->id ? 'selected' : '' }}>
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
                            <x-input.rich-text :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" />
                        @break

                        {{-- SELECT --}}
                        @case('select')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.select :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" :options="$field['options'] ?? []" />

                            </div>
                        @break

                        @case('icon')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.icon-select :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? 'none')" />

                            </div>
                        @break

                        {{-- URL --}}
                        @case('url')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.url :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ''" />
                            </div>
                        @break

                        @case('link')
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.url :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ''" />
                            </div>
                        @break

                        {{-- TEXTAREA --}}
                        @case('textarea')
                            <label class="text-xs font-semibold text-secondary uppercase block mb-1">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.textarea :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" />
                        @break

                        {{-- CHECKBOX --}}
                        @case('checkbox')
                            <label class="text-xs font-semibold text-secondary uppercase block mb-1">
                                {{ $field['label'] ?? '' }}
                            </label>

                            <x-input.checkbox :name="'settings[' . $field['key'] . ']'" :checked="!empty($block['settings'][$field['key']] ?? ($field['default'] ?? false))" />
                        @break

                        {{-- SWITCH --}}
                        @case('switch')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-9/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.switch containerClass="w-3/12!" :name="'settings[' . $field['key'] . ']'" :checked="!empty($block['settings'][$field['key']] ?? ($field['default'] ?? false))" />

                            </div>
                        @break

                        {{-- NUMBER --}}
                        @case('number')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.number class="text-xs!" :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? 0)" :min="$field['min'] ?? 0"
                                    :max="$field['max'] ?? 100" />

                            </div>
                        @break

                        {{-- COLOR --}}
                        @case('color')
                            <div class="flex items-center justify-between gap-4">
                                <label class="text-xs w-7/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.color :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '#000000')" :gradient="true" />
                            </div>
                        @break

                        {{-- RANGE --}}
                        @case('range')
                            <div class="flex items-start justify-between gap-6 pt-1">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.range :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? 50)" :min="$field['min'] ?? 0" :max="$field['max'] ?? 100" />

                            </div>
                        @break

                        {{-- RADIO --}}
                        @case('radio')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.radio :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" :options="$field['options'] ?? []" />

                            </div>
                        @break

                        {{-- IMAGE --}}
                        @case('image')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.image :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" />

                            </div>
                        @break

                        {{-- VIDEO --}}
                        @case('video')
                            <div class="flex items-center justify-between gap-6">

                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.video :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ($field['default'] ?? '')" />

                            </div>
                        @break

                        {{-- DEFAULT --}}

                        @default
                            <div class="flex items-center justify-between gap-6">
                                <label class="text-xs w-8/12 text-secondary block">
                                    {{ $field['label'] ?? '' }}
                                </label>

                                <x-input.text :name="'settings[' . $field['key'] . ']'" :value="$block['settings'][$field['key']] ?? ''" />
                            </div>
                    @endswitch


                    @if (isset($field['help']) && $field['type'] !== 'group')
                        <p class="text-[11px] text-secondary mt-1">{{ $field['help'] }}</p>
                    @endif

                </div>
            @endforeach

        </form>

    @endif
</div>
