<div class="ui-element-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('ui-element-settings-menu', 'arrow-ui')" type="button" class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-shapes text-sm"></i> UI Elements</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-ui"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="ui-element-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $uiSettings = [
            'Inputs' => [
            ['key' => 'input_border_radius', 'label' => 'Input Radius', 'type' => 'select', 'options' => ['0px', '4px', '8px', '12px', '20px'], 'value' => '8px'],
            ['key' => 'input_focus_color', 'label' => 'Focus Border Color', 'type' => 'color', 'value' => '#2563eb'],
            ],
            'Cards' => [
            ['key' => 'card_border_radius', 'label' => 'Card Radius', 'type' => 'select', 'options' => ['0px', '8px', '12px', '16px', '24px'], 'value' => '12px'],
            ['key' => 'card_shadow', 'label' => 'Card Shadow', 'type' => 'radio', 'options' => ['none', 'small', 'medium', 'large'], 'value' => 'small'],
            ],
            'Images' => [
            ['key' => 'image_radius', 'label' => 'Image Border Radius', 'type' => 'select', 'options' => ['0px', '8px', '16px', '24px', '50%'], 'value' => '8px'],
            ],
            ];
            @endphp

            @foreach($uiSettings as $category => $items)
            <div class="category-section mb-4">
                <h3 class="text-md font-semibold text-primary border-top py-4">{{ $category }}</h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach($items as $item)
                    <div class="flex items-center justify-between">
                        <label class="text-primary text-xs">{{ $item['label'] }}</label>
                        <div class="flex items-center border-rounded border-primary pr-2">
                            @if($item['type'] === 'radio')
                            @foreach($item['options'] as $option)
                            <label class="radio-style mr-2 text-xs cursor-pointer">
                                <input type="radio" name="{{ $item['key'] }}" value="{{ $option }}" {{ ($customizes[$item['key']] ?? $item['value']) === $option ? 'checked' : '' }} class="hidden peer">
                                <span class="px-2 py-1 border rounded peer-checked:bg-primary peer-checked:text-white">{{ ucfirst($option) }}</span>
                            </label>
                            @endforeach
                            @elseif($item['type'] === 'select')
                            <select name="{{ $item['key'] }}" class="font-sm focus:ring-0 w-40 p-2">
                                @foreach($item['options'] as $option)
                                <option value="{{ $option }}" {{ ($customizes[$item['key']] ?? $item['value']) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            @else
                            <input type="{{ $item['type'] }}" name="{{ $item['key'] }}" value="{{ $customizes[$item['key']] ?? $item['value'] }}" class="w-28 p-2 border-none">
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