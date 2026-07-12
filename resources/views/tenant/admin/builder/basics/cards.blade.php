<div class="cards-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('cards-settings-menu', 'arrow-cards')" type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-desktop mr-1"></i>Cards</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-cards"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="cards-settings-menu">
        @php
            $cardtypes = ['category_cards', 'classes_cards', 'subjects_cards', 'courses_cards', 'badges'];
            $cardSettings = [
                ['key' => "background", 'label' => 'Background', 'type' => 'color', 'default' => '#fff', 'except' => ['badges']],
                ['key' => "color", 'label' => 'Badge Color', 'type' => 'color', 'default' => '#fff', 'only' => ['badges']],
                ['key' => "text_size", 'label' => 'Badge Text Size', 'type' => 'range', 'default' => 16, 'min' => 0, 'max' => 40, 'only' => ['badges']],
                ['key' => "text_color", 'label' => 'Badge Text Color', 'type' => 'color', 'default' => '#000', 'only' => ['badges']],
                ['key' => "title_size", 'label' => 'Title Size', 'type' => 'range', 'default' => '28', 'min' => 10, 'max' => 100, 'except' => ['badges']],
                ['key' => "title_color", 'label' => 'Title Color', 'type' => 'color', 'default' => '#000', 'except' => ['badges']],
                ['key' => "desc_size", 'label' => 'Description Size', 'type' => 'range', 'default' => '14', 'min' => 10, 'max' => 40, 'except' => ['badges']],
                ['key' => "desc_color", 'label' => 'Description Color', 'type' => 'color', 'default' => '#000', 'except' => ['badges']],
                ['key' => "discounted_price_size", 'label' => 'Discounted Price Size', 'type' => 'range', 'default' => '14', 'min' => 10, 'max' => 40, 'only' => ['courses_cards']],
                ['key' => "discounted_price_color", 'label' => 'Discounted Price Color', 'type' => 'color', 'default' => '#fff', 'only' => ['courses_cards']],
                ['key' => "price_size", 'label' => 'Price Size', 'type' => 'range', 'default' => '14', 'min' => 10, 'max' => 40, 'only' => ['courses_cards']],
                ['key' => "price_color", 'label' => 'Price Color', 'type' => 'color', 'default' => '#fff', 'only' => ['courses_cards']],
                ['key' => "border_width", 'label' => 'Border Width', 'type' => 'range', 'default' => 1, 'min' => 0, 'max' => 10],
                ['key' => "border_color", 'label' => 'Border Color', 'type' => 'color', 'default' => '#e5e7eb'],
                ['key' => "border_radius", 'label' => 'Border Radius', 'type' => 'range', 'default' => 8, 'min' => 0, 'max' => 50],
                ['key' => "padding", 'label' => 'Padding', 'type' => 'range', 'default' => 16, 'min' => 0, 'max' => 100],
            ]
        @endphp
        @foreach($cardtypes as $cardtype)
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                {{ ucwords(str_replace('_', ' ', $cardtype)) }}
            </h3>
            <div class="grid grid-cols-1 gap-4 p-4">
                @foreach($cardSettings as $setting)
                    @if(isset($setting['only']) && !in_array($cardtype, $setting['only']))
                        @continue
                    @endif
                    @if(isset($setting['except']) && in_array($cardtype, $setting['except']))
                        @continue
                    @endif
                    @php
                        $key = $cardtype . '_' . $setting['key'];
                        $value = $customizes[$key] ?? $setting['default'];
                    @endphp
                    <div class="flex items-center justify-between">
                        <label class="text-primary text-xs">{{ $setting['label'] }}</label>

                        <div class="flex items-center border-rounded border-primary">
                            @if($setting['type'] == 'color')
                                <x-input.color name="{{ $key }}" value="{{ $value }}" />
                            @endif
                            @if($setting['type'] == 'range')
                                <div class="flex items-center w-43 gap-2 px-2 py-3">
                                    <input type="range" name="{{ $key }}" min="{{ $setting['min'] }}" max="{{ $setting['max'] }}"
                                        value="{{ $value }}" class="w-full accent-black"
                                        oninput="this.nextElementSibling.innerText = this.value">

                                    <span class="text-xs text-primary min-w-10 text-right">
                                        {{ $value }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
</div>