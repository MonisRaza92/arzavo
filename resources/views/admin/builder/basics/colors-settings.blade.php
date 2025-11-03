<div class="colors-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('colors-settings-menu', 'arrow-colors')" type="button" class=" p-4 flex justify-between items-center w-full text-md font-semibold"><span><i class="fa-solid text-sm fa-palette"></i> Theme Colors</span> <i class="fas fa-angle-right transition-all duration-300" id="arrow-colors"></i></button>
    <div class="colors-settings-menu overflow-hidden max-h-0" id="colors-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $categories = [
            'Theme Colors' => [
            ['key' => 'background_color', 'label' => 'Background', 'type' => 'color', 'value' => 'f5f5f5'],
            ['key' => 'primary_color', 'label' => 'Primary Color', 'type' => 'color', 'value' => 'ffffff'],
            ['key' => 'secondary_color', 'label' => 'Secondary Color', 'type' => 'color', 'value' => 'f5f5f5'],
            ['key' => 'accent_color', 'label' => 'Accent Color', 'type' => 'color', 'value' => '111111'],
            ['key' => 'tertiary_color', 'label' => 'Tertiary Color', 'type' => 'color', 'value' => 'cccccc'],
            ],
            'Text Colors' => [
            ['key' => 'heading_color', 'label' => 'Heading', 'type' => 'color', 'value' => '000000'],
            ['key' => 'subheading_color', 'label' => 'Subheading', 'type' => 'color', 'value' => '333333'],
            ['key' => 'paragraph_color', 'label' => 'Paragraph', 'type' => 'color', 'value' => '666666'],
            ['key' => 'secondary_text_color', 'label' => 'Secondary Text', 'type' => 'color', 'value' => '999999'],
            ],
            'Border and Shadow' => [
            ['key' => 'border_color', 'label' => 'Border Color', 'type' => 'color', 'value' => 'dddddd'],
            ['key' => 'shadow_color', 'label' => 'Shadow Color', 'type' => 'color', 'value' => '000000'],
            ],
            'Primary Button' => [
            ['key' => 'primary_btn_background', 'label' => 'Background', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'primary_btn_text_color', 'label' => 'Text Color', 'type' => 'color', 'value' => '#ffffff'],
            ['key' => 'primary_btn_hover_background', 'label' => 'Hover Background', 'type' => 'color', 'value' => '#ffffff'],
            ['key' => 'primary_btn_hover_text_color', 'label' => 'Hover Text Color', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'primary_btn_border_color', 'label' => 'Border Color', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'primary_btn_hover_border', 'label' => 'Hover Border', 'type' => 'color', 'value' => '#000000'],
            ],
            'Secondary Button' => [
            ['key' => 'secondary_btn_background', 'label' => 'Background', 'type' => 'color', 'value' => ''],
            ['key' => 'secondary_btn_text_color', 'label' => 'Text Color', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'secondary_btn_hover_background', 'label' => 'Hover Background', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'secondary_btn_hover_text_color', 'label' => 'Hover Text', 'type' => 'color', 'value' => '#ffffff'],
            ['key' => 'secondary_btn_border_color', 'label' => 'Border Color', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'secondary_btn_hover_border', 'label' => 'Hover Border', 'type' => 'color', 'value' => '#000000'],
            ],
            'Input Colors' => [
            ['key' => 'input_background_color', 'label' => 'Input Background Color', 'type' => 'color', 'value' => ''],
            ['key' => 'input_text_color', 'label' => 'Input Text Color', 'type' => 'color', 'value' => '000000'],
            ['key' => 'input_border_color', 'label' => 'Input Border Color', 'type' => 'color', 'value' => 'cccccc'],
            ['key' => 'input_focus_border_color', 'label' => 'Input Focus Border Color', 'type' => 'color', 'value' => '007bff'],
            ],
            ];
            @endphp

            @foreach ($categories as $category => $items)
            <div class="category-section mb-4">
                <h3 class="text-md font-semibold text-primary {{ $category === 'Theme Colors' ? 'pb-4' : 'border-top py-4' }}">{{ $category }}</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($items as $item)
                    @php $key = $item['key']; @endphp
                    <div class="flex items-center justify-between">
                        <label for="{{ $key }}Input" class="text-primary text-xs">{{ $item['label'] }}</label>
                        <div class="flex items-center border-rounded border-primary p-2">
                            <input type="{{ $item['type'] }}" id="{{ $key }}Input" name="{{ $key }}" class="h-8 w-8 border-primary color-input rounded-full" value="{{ $customizes[$key] ?? $item['value'] }}" oninput="document.getElementById('{{ $key }}Code').value = this.value" onchange="document.getElementById('{{ $key }}Code').value = this.value">
                            <input type="text" id="{{ $key }}Code" name="{{ $key }}" class="h-8 w-20 border-rounded ml-2 p-2" value="{{ $customizes[$key] ?? $item['value'] }}" oninput="document.getElementById('{{ $key }}Input').value = this.value" onchange="document.getElementById('{{ $key }}Input').value = this.value">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>