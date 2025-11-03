<div class="typography-settings bg-primary border-bottom">
    <button
        onclick="openCustomizesMenu('typography-settings-menu', 'arrow-typography')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-font text-sm"></i> Typography</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-typography"></i>
    </button>

    <div class="typography-settings-menu overflow-hidden max-h-0 transition-all duration-500" id="typography-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">

            @php
            $typographyCategories = [

            'Font Family' => [
            ['key' => 'heading_font_family', 'label' => 'Heading', 'type' => 'select',
            'options' => [
            'Arial, sans-serif' => 'Arial, sans-serif',
            'Verdana, sans-serif' => 'Verdana, sans-serif',
            'Helvetica, sans-serif' => 'Helvetica, sans-serif',
            'Georgia, serif' => 'Georgia, serif',
            'Times New Roman, serif' => 'Times New Roman, serif',
            'Courier New, monospace' => 'Courier New, monospace',
            'Tahoma, sans-serif' => 'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS, sans-serif'
            ],
            'value' => 'Arial, sans-serif'],

            ['key' => 'subheading_font_family', 'label' => 'Subheading', 'type' => 'select',
            'options' => [
            'Arial, sans-serif' => 'Arial, sans-serif',
            'Verdana, sans-serif' => 'Verdana, sans-serif',
            'Helvetica, sans-serif' => 'Helvetica, sans-serif',
            'Georgia, serif' => 'Georgia, serif',
            'Times New Roman, serif' => 'Times New Roman, serif',
            'Courier New, monospace' => 'Courier New, monospace',
            'Tahoma, sans-serif' => 'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS, sans-serif'
            ],
            'value' => 'Arial, sans-serif'],

            ['key' => 'paragraph_font_family', 'label' => 'Paragraph', 'type' => 'select',
            'options' => [
            'Arial, sans-serif' => 'Arial, sans-serif',
            'Verdana, sans-serif' => 'Verdana, sans-serif',
            'Helvetica, sans-serif' => 'Helvetica, sans-serif',
            'Georgia, serif' => 'Georgia, serif',
            'Times New Roman, serif' => 'Times New Roman, serif',
            'Courier New, monospace' => 'Courier New, monospace',
            'Tahoma, sans-serif' => 'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS, sans-serif'
            ],
            'value' => 'Arial, sans-serif'],

            ['key' => 'body_font_family', 'label' => 'Body Text', 'type' => 'select',
            'options' => [
            'Arial, sans-serif' => 'Arial, sans-serif',
            'Verdana, sans-serif' => 'Verdana, sans-serif',
            'Helvetica, sans-serif' => 'Helvetica, sans-serif',
            'Georgia, serif' => 'Georgia, serif',
            'Times New Roman, serif' => 'Times New Roman, serif',
            'Courier New, monospace' => 'Courier New, monospace',
            'Tahoma, sans-serif' => 'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS, sans-serif'
            ],
            'value' => 'Arial, sans-serif'],
            ],

            'Heading' => [
            ['key' => 'heading_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '24' => 'Small',
            '28' => 'Large',
            '32' => 'Extra Large',
            '36' => 'Huge',
            '40' => 'Jumbo',
            '44' => 'Gigantic',
            '48' => 'Colossal',
            '52' => 'Titanic',
            '56' => 'Monstrous',
            '60' => 'Behemoth',
            ],
            'value' => '40'],

            ['key' => 'heading_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'uppercase'],

            ['key' => 'heading_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Subheading' => [
            ['key' => 'subheading_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '18' => 'Small',
            '22' => 'Medium',
            '26' => 'Large',
            '30' => 'Extra Large',
            '34' => 'Huge',
            '38' => 'Jumbo',
            ],
            'value' => '24'],


            ['key' => 'subheading_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'subheading_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '400'],
            ],

            'Paragraph' => [
            ['key' => 'paragraph_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '14' => 'Small',
            '16' => 'Medium',
            '18' => 'Large',
            '20' => 'Extra Large',
            '22' => 'Huge',
            '24' => 'Jumbo',
            ],
            'value' => '16'],


            ['key' => 'paragraph_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'capitalize' => 'capitalize'
            ],
            'value' => 'default'],

            ['key' => 'paragraph_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '400'],
            ],

            'Body' => [
            ['key' => 'body_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '12' => 'Micro',
            '14' => 'Small',
            '16' => 'Medium',
            ],
            'value' => '14'],


            ['key' => 'body_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'capitalize' => 'capitalize'
            ],
            'value' => 'default'],

            ['key' => 'body_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '400'],
            ],
            ];
            @endphp


            @foreach ($typographyCategories as $category => $items)
            <div class="category-section mb-4">
                <h3 class="text-md font-semibold text-primary {{ $category === 'Font Family' ? 'pb-4' : 'border-top py-4' }}">{{ $category }}</h3>

                <div class="grid grid-cols-1 gap-4">
                    @foreach ($items as $item)
                    @php $key = $item['key']; @endphp

                    <div class="flex flex-wrap items-center justify-between">
                        <label for="{{ $key }}Input" class="text-primary text-xs">{{ $item['label'] }}</label>
                        <div class="flex items-center border-rounded {{ $item['type'] === 'select' ? 'pr-2 border-primary' : '' }}">

                            {{-- Radio Inputs --}}
                            @if ($item['type'] === 'radio')
                            <div class="flex w-43 bg-secondary p-1 border-rounded">
                                @foreach ($item['options'] as $option)
                                @php $checked = ($customizes[$key] ?? $item['value']) === $option ? 'checked' : ''; @endphp

                                <label class="cursor-pointer w-full flex">
                                    <input type="radio"
                                        id="{{ $key }}_{{ $option }}"
                                        name="{{ $key }}"
                                        value="{{ $option }}"
                                        {{ $checked }}
                                        class="hidden peer">

                                    <span class="p-2 flex-1 text-xs text-center border-rounded inline-block
            transition-all duration-200
            peer-checked:bg-black peer-checked:text-white">
                                        {{ ucfirst($option) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Select Inputs --}}
                            @elseif ($item['type'] === 'select')
                            <select
                                id="{{ $key }}Input"
                                name="{{ $key }}"
                                class="font-sm focus:ring-0 outline-0 w-40 p-2 text-xs capitalize">
                                @foreach ($item['options'] as $value => $text)
                                <option value="{{ $value }}" {{ ($customizes[$key] ?? $item['value']) == $value ? 'selected' : '' }}>
                                    {{ $text }}
                                </option>
                                @endforeach
                            </select>

                            {{-- Default Inputs --}}
                            @else
                            <input
                                type="{{ $item['type'] }}"
                                id="{{ $key }}Input"
                                name="{{ $key }}"
                                value="{{ $customizes[$key] ?? $item['value'] }}"
                                class="w-30 p-2 capitalize text-xs focus:ring-0">
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>