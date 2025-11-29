<div class="typography-settings bg-primary border-bottom">
    <button
        onclick="openCustomizesMenu('typography-settings-menu', 'arrow-typography')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Typography</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-typography"></i>
    </button>

    <div class="typography-settings-menu overflow-hidden max-h-0 transition-all duration-500" id="typography-settings-menu">
        <div class="grid grid-cols-1 border-top">

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

            ['key' => 'secondary_font_family', 'label' => 'Body Text', 'type' => 'select',
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

            'Heading 1' => [
            ['key' => 'heading_1_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '44'],

            ['key' => 'heading_1_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_1_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_1_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],


            'Heading 2' => [
            ['key' => 'heading_2_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '40'],

            ['key' => 'heading_2_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_2_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_2_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Heading 3' => [
            ['key' => 'heading_3_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '36'],

            ['key' => 'heading_3_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_3_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_3_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Heading 4' => [
            ['key' => 'heading_4_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '32'],

            ['key' => 'heading_4_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_4_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_4_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Heading 5' => [
            ['key' => 'heading_5_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '28'],

            ['key' => 'heading_5_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_5_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_5_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Heading 6' => [
            ['key' => 'heading_6_font_size', 'label' => 'Font Size', 'type' => 'select',
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
            '64' => 'Mammoth',
            '68' => 'Gargantuan',
            '72' => 'Colossal',
            '76' => 'Titanic',
            '80' => 'Monstrous',
            ],
            'value' => '24'],

            ['key' => 'heading_6_line_height', 'label' => 'Line Height', 'type' => 'select',
            'options' => [
            '1.1' => 'Compact',
            '1.3' => 'Standard',
            '1.8' => 'Spacious',
            ],
            'value' => '1.1'],

            ['key' => 'heading_6_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'uppercase' => 'uppercase'
            ],
            'value' => 'default'],

            ['key' => 'heading_6_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '600'],
            ],

            'Paragraph' => [
            ['key' => 'paragraph_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '12' => 'Micro',
            '14' => 'Small',
            '16' => 'Medium',
            '18' => 'Large',
            '20' => 'Extra Large',
            '22' => 'Huge',
            '24' => 'Jumbo',
            '26' => 'Gigantic',
            '28' => 'Colossal',
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
            ['key' => 'secondary_text_font_size', 'label' => 'Font Size', 'type' => 'select',
            'options' => [
            '12' => 'Micro',
            '14' => 'Small',
            '16' => 'Medium',
            ],
            'value' => '12'],


            ['key' => 'secondary_text_text_transform', 'label' => 'Transform', 'type' => 'radio',
            'options' => [
            'default' => 'default',
            'capitalize' => 'capitalize'
            ],
            'value' => 'default'],

            ['key' => 'secondary_text_weight', 'label' => 'Weight', 'type' => 'select',
            'options' => ['200'=>'Thin','400'=>'Normal','600'=>'semibold', '800'=>'Bold'],
            'value' => '400'],
            ],
            ];
            @endphp


            @foreach ($typographyCategories as $category => $items)
            <div class="category-section">
                <h3 class="text-sm px-4 py-3 border-bottom font-semibold text-primary {{ $category === 'Font Family' ? '' : 'border-top' }}">{{ $category }}</h3>

                <div class="grid grid-cols-1 gap-4 p-4">
                    @foreach ($items as $item)
                    @php $key = $item['key']; @endphp

                    <div class="flex flex-wrap items-center justify-between">
                        <label for="{{ $key }}Input" class="text-primary text-xs">{{ $item['label'] }}</label>
                        <div class="flex items-center border-rounded {{ $item['type'] === 'select' ? 'pr-2 border-primary' : '' }}">

                            {{-- Radio Inputs --}}
                            @if ($item['type'] === 'radio')
                            <div class="flex w-43 border-primary border-rounded">
                                @foreach ($item['options'] as $option)
                                @php $checked = ($customizes[$key] ?? $item['value']) === $option ? 'checked' : ''; @endphp

                                <label class="cursor-pointer w-full flex">
                                    <input type="radio"
                                        id="{{ $key }}_{{ $option }}"
                                        name="{{ $key }}"
                                        value="{{ $option }}"
                                        {{ $checked }}
                                        class="hidden peer">

                                    <span class="p-2 flex-1 text-sm text-center border-rounded inline-block
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
                                class="focus:ring-0 outline-0 w-40 p-2 text-sm capitalize">
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