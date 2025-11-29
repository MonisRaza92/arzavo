<div class="animation-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('animation-settings-menu', 'arrow-anim')" type="button" class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-wand-magic-sparkles text-sm"></i> Animations</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-anim"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="animation-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $animationSettings = [
            'Scroll Animations' => [
            ['key' => 'scroll_animation', 'label' => 'Enable Animation', 'type' => 'radio', 'options' => ['on', 'off'], 'value' => 'on'],
            ['key' => 'animation_type', 'label' => 'Type', 'type' => 'select', 'options' => ['fade', 'slide', 'zoom', 'flip'], 'value' => 'fade'],
            ],
            'Speed & Style' => [
            ['key' => 'animation_speed', 'label' => 'Speed', 'type' => 'select', 'options' => ['slow', 'normal', 'fast'], 'value' => 'normal'],
            ['key' => 'hover_animation', 'label' => 'Hover Effect', 'type' => 'select', 'options' => ['none', 'grow', 'lift', 'rotate'], 'value' => 'lift'],
            ],
            ];
            @endphp

            @foreach($animationSettings as $category => $items)
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
                            @else
                            <select name="{{ $item['key'] }}" class="font-sm focus:ring-0 w-40 p-2">
                                @foreach($item['options'] as $option)
                                <option value="{{ $option }}" {{ ($customizes[$item['key']] ?? $item['value']) === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
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