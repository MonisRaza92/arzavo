<div class="border-shadow-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('border-shadow-settings-menu', 'arrow-border-shadow')" type="button" class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-border-all text-sm"></i> Border & Shadow</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-border-shadow"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="border-shadow-settings-menu">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">

            @php
            $borderShadowSettings = [
            'Border Settings' => [
            ['key' => 'border_color', 'label' => 'Border Color', 'type' => 'color', 'value' => '#DDDDDD'],
            ['key' => 'border_width', 'label' => 'Width (px)', 'type' => 'select', 'options' => [0,1,2,3,4,5,8,10,12], 'value' => 1],
            ['key' => 'border_radius', 'label' => 'Radius (px)', 'type' => 'select', 'options' => [0,4,8,10,12,16,20,30], 'value' => 8],
            ],

            'Shadow Settings' => [
            ['key' => 'shadow_color', 'label' => 'Shadow Color', 'type' => 'color', 'value' => '#000000'],
            ['key' => 'shadow_enable', 'label' => 'Enable Shadow', 'type' => 'radio', 'options' => ['yes','no'], 'value' => 'yes'],
            ['key' => 'shadow_blur', 'label' => 'Blur (px)', 'type' => 'select', 'options' => [0,5,10,15,20,25,30], 'value' => 15],
            ['key' => 'shadow_spread', 'label' => 'Spread (px)', 'type' => 'select', 'options' => [0,1,2,4,6,8], 'value' => 2],
            ['key' => 'shadow_opacity', 'label' => 'Opacity (0 - 1)', 'type' => 'text', 'value' => .15],
            ['key' => 'shadow_position', 'label' => 'Position', 'type' => 'select', 'options' => ['around','top','bottom'], 'value' => 'around'],
            ],
            ];
            @endphp

            @foreach ($borderShadowSettings as $category => $items)
            <div class="category-section mb-4">
                <h3 class="text-md font-semibold text-primary {{ $category === 'Border Settings' ? 'pb-4' : 'border-top py-4' }}">{{ $category }}</h3>

                <div class="grid grid-cols-1 gap-3">
                    @foreach ($items as $item)
                    @php $key = $item['key']; $value = $customizes[$key] ?? $item['value']; @endphp

                    <div class="flex items-center justify-between">
                        <label class="text-primary text-xs">{{ $item['label'] }}</label>

                        <div class="flex items-center border-rounded border-primary">

                            {{-- Radio Inputs (Same UI as Shadow & Border section) --}}
                            @if ($item['type'] === 'radio')
                            <div class="flex w-34">
                                @foreach ($item['options'] as $option)
                                @php $checked = ($customizes[$key] ?? $item['value']) === $option ? 'checked' : ''; @endphp

                                <label class="cursor-pointer">
                                    <input type="radio"
                                        id="{{ $key }}_{{ $option }}"
                                        name="{{ $key }}"
                                        value="{{ $option }}"
                                        {{ $checked }}
                                        class="hidden peer">

                                    <span class="py-2 w-17 text-xs text-center border-rounded inline-block
            transition-all duration-200
            peer-checked:bg-black peer-checked:text-white">
                                        {{ ucfirst($option) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Select --}}
                            @elseif($item['type'] === 'select')
                            <select name="{{ $key }}" class="font-sm focus:ring-0 w-34 p-2 text-md">
                                @foreach($item['options'] as $option)
                                <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}
                                </option>
                                @endforeach
                            </select>

                            {{-- Color (same style as colors section) --}}
                            @elseif($item['type'] === 'color')
                            <div class="flex items-center border-rounded p-2">
                                <input type="color" id="{{ $key }}Input" name="{{ $key }}" class="h-8 w-8 color-input rounded-full"
                                    value="{{ $value }}"
                                    oninput="document.getElementById('{{ $key }}Code').value = this.value"
                                    onchange="document.getElementById('{{ $key }}Code').value = this.value">

                                <input type="text" id="{{ $key }}Code" name="{{ $key }}"
                                    class="h-8 w-20 border-rounded ml-2 p-2 text-xs"
                                    value="{{ $value }}"
                                    oninput="document.getElementById('{{ $key }}Input').value = this.value"
                                    onchange="document.getElementById('{{ $key }}Input').value = this.value">
                            </div>

                            {{-- Text --}}
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
</div>