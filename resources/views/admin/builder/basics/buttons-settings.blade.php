<div class="button-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('button-settings-menu', 'arrow-button')" type="button" class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-square text-sm"></i> Buttons</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-button"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="button-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $buttonSettings = [
                'Style' => [
                    ['key' => 'button_shape', 'label' => 'Shape', 'type' => 'select', 'options' => ['rounded', 'square', 'pill'], 'value' => 'rounded'],
                    ['key' => 'button_size', 'label' => 'Size', 'type' => 'select', 'options' => ['small', 'medium', 'large'], 'value' => 'medium'],
                    ['key' => 'button_text_transform', 'label' => 'Text Transform', 'type' => 'radio', 'options' => ['none', 'uppercase', 'capitalize'], 'value' => 'uppercase'],
                ],
                'Colors' => [
                    ['key' => 'button_bg_color', 'label' => 'Background Color', 'type' => 'color', 'value' => '#2563eb'],
                    ['key' => 'button_hover_color', 'label' => 'Hover Color', 'type' => 'color', 'value' => '#1e40af'],
                    ['key' => 'button_text_color', 'label' => 'Text Color', 'type' => 'color', 'value' => '#ffffff'],
                ],
                'Effects' => [
                    ['key' => 'button_shadow', 'label' => 'Button Shadow', 'type' => 'radio', 'options' => ['none', 'small', 'medium', 'large'], 'value' => 'small'],
                    ['key' => 'button_transition', 'label' => 'Hover Transition', 'type' => 'radio', 'options' => ['none', 'smooth', 'fast'], 'value' => 'smooth'],
                ],
            ];
            @endphp

            @foreach ($buttonSettings as $category => $items)
            <div class="category-section mb-4">
                <h3 class="text-md font-semibold text-primary border-top py-4">{{ $category }}</h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach ($items as $item)
                    <div class="flex items-center justify-between">
                        <label for="{{ $item['key'] }}Input" class="text-primary text-xs">{{ $item['label'] }}</label>
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
                                    <option value="{{ $option }}" {{ ($customizes[$item['key']] ?? $item['value']) === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
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
