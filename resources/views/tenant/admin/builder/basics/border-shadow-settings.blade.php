<div class="border-shadow-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('border-shadow-settings-menu', 'arrow-border-shadow')" type="button" class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Border & Shadow</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-border-shadow"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="border-shadow-settings-menu">

        @php
        $borderShadowSettings = [

        'Border Settings' => [
        [
        'key' => 'border_width',
        'label' => 'Width (px)',
        'type' => 'select',
        'options' => [
        ['label' => 'No Border', 'value' => '0px'],
        ['label' => 'Thin Border', 'value' => '1px'],
        ['label' => 'Light Border', 'value' => '2px'],
        ['label' => 'Medium Border', 'value' => '3px'],
        ['label' => 'Bold Border', 'value' => '4px'],
        ['label' => 'Extra Bold Border', 'value' => '5px'],
        ],
        'value' => '1px'
        ],

        [
        'key' => 'border_radius',
        'label' => 'Radius (px)',
        'type' => 'select',
        'options' => [
        ['label' => 'Sharp (No Radius)', 'value' => '0px'],
        ['label' => 'Slight Rounded', 'value' => '4px'],
        ['label' => 'Rounded', 'value' => '8px'],
        ['label' => 'Soft Rounded', 'value' => '12px'],
        ['label' => 'Medium Rounded', 'value' => '16px'],
        ['label' => 'Smooth Rounded', 'value' => '20px'],
        ['label' => 'Extra Rounded', 'value' => '24px'],
        ['label' => 'Pill Rounded', 'value' => '30px'],
        ],
        'value' => '8px'
        ],
        ],

        'Shadow Settings' => [
        [
        'key' => 'shadow_blur',
        'label' => 'Blur',
        'type' => 'select',
        'options' => [
        ['label' => 'No Blur', 'value' => '0px'],
        ['label' => 'Soft Blur', 'value' => '5px'],
        ['label' => 'Medium Blur', 'value' => '10px'],
        ['label' => 'Strong Blur', 'value' => '15px'],
        ['label' => 'Extra Blur', 'value' => '20px'],
        ['label' => 'Ultra Blur', 'value' => '25px'],
        ['label' => 'Maximum Blur', 'value' => '30px'],
        ],
        'value' => '10px'
        ],

        [
        'key' => 'shadow_spread',
        'label' => 'Spread',
        'type' => 'select',
        'options' => [
        ['label' => 'No Spread', 'value' => '0px'],
        ['label' => 'Soft Spread', 'value' => '1px'],
        ['label' => 'Medium Spread', 'value' => '2px'],
        ['label' => 'Strong Spread', 'value' => '4px'],
        ['label' => 'Wide Spread', 'value' => '6px'],
        ['label' => 'Ultra Spread', 'value' => '8px'],
        ['label' => 'Maximum Spread', 'value' => '10px'],
        ],
        'value' => '2px'
        ],

        ],
        ];

        @endphp

        @foreach ($borderShadowSettings as $category => $items)
        <div class="category-section">
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">{{ $category }}</h3>

            <div class="grid grid-cols-1 gap-4 p-4">
                @foreach ($items as $item)
                @php $key = $item['key']; $value = $customizes[$key] ?? $item['value']; @endphp

                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">{{ $item['label'] }}</label>

                    <div class="flex items-center border-rounded border-primary">

                        {{-- Radio Inputs (Same UI as Shadow & Border section) --}}
                        @if ($item['type'] === 'radio')
                        <div class="flex w-43">
                            @foreach ($item['options'] as $option)
                            @php $checked = ($customizes[$key] ?? $item['value']) === $option ? 'checked' : ''; @endphp

                            <label class="cursor-pointer flex w-full">
                                <input type="radio"
                                    name="{{ $key }}"
                                    value="{{ $option['value'] }}"
                                    {{ $value == $option['value'] ? 'checked' : '' }}
                                    class="hidden peer">

                                <span class="py-2 flex-1 text-sm text-center border-rounded inline-block
        peer-checked:bg-black peer-checked:text-white transition-all duration-200">
                                    {{ $option['label'] }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        {{-- Select --}}
                        @elseif($item['type'] === 'select')
                        <select name="{{ $key }}" class="focus:ring-0 w-43 p-2 text-sm">
                            @foreach($item['options'] as $option)
                            <option value="{{ $option['value'] }}" {{ $value == $option['value'] ? 'selected' : '' }}>
                                {{ $option['label'] }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" name="{{ $key }}" value="{{ $value }}" class="w-34 p-2 border-none text-md" />
                        @endif

                    </div>
                </div>

                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</div>