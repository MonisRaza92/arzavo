<div class="button-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('buttons-settings-menu', 'arrow-buttons')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-arrow-pointer mr-1"></i> Buttons & Inputs</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-buttons"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="buttons-settings-menu">

        @php
        // FINAL CLEAN TYPE ARRAY
        $buttonTypes = ['primary_button','secondary_button'];

        // OPTIONS WITH LABEL + VALUE SUPPORT
        $buttonCustomizeOptions = [

        [
        'key'=>'shape','label'=>'Roundness','type'=>'range',
        'min'=>0,'max'=>50,'step'=>2,'unit'=>'px',
        'value'=>0
        ],


        [
        'key'=>'padding_vertical','label'=>'Padding Vertical','type'=>'range',
        'min'=>8,'max'=>80,'step'=>2,'unit'=>'px',
        'value'=>10
        ],
        [
        'key'=>'padding_horizontal','label'=>'Padding Horizontal','type'=>'range',
        'min'=>8,'max'=>80,'step'=>2,'unit'=>'px',
        'value'=>16
        ],


        [
        'key'=>'font_size','label'=>'Font Size','type'=>'range',
        'min'=>12,'max'=>32,'step'=>1,'unit'=>'px',
        'value'=>16
        ],


        [
        'key'=>'font_weight','label'=>'Font Weight','type'=>'select',
        'options'=>[
        ['label'=>'Thin','value'=>'100'],
        ['label'=>'Extra Light','value'=>'200'],
        ['label'=>'Light','value'=>'300'],
        ['label'=>'Normal','value'=>'400'],
        ['label'=>'Medium','value'=>'500'],
        ['label'=>'Semi Bold','value'=>'600'],
        ['label'=>'Bold','value'=>'700'],
        ['label'=>'Extra Bold','value'=>'800'],
        ['label'=>'Black','value'=>'900'],
        ],
        'value'=>'400'
        ],

        [
        'key'=>'text_transform','label'=>'Text Transform','type'=>'radio',
        'options'=>[
        ['label'=>'Default','value'=>'default'],
        ['label'=>'Uppercase','value'=>'uppercase'],
        ],
        'value'=>'uppercase'
        ],

        [
        'key'=>'border_width','label'=>'Border Width','type'=>'range',
        'min'=>0,'max'=>4,'step'=>1,'unit'=>'px',
        'value'=>1
        ],

        [
        'key'=>'font_family','label'=>'Font Family','type'=>'select',
        'options'=>[
        ['label'=>'Outfit','value'=>'Outfit'],
        ['label'=>'Arial','value'=>'Arial, sans-serif'],
        ['label'=>'Helvetica','value'=>'Helvetica, sans-serif'],
        ['label'=>'Times New Roman','value'=>'"Times New Roman", serif'],
        ['label'=>'Courier New','value'=>'"Courier New", monospace'],
        ['label'=>'Georgia','value'=>'Georgia, serif'],
        ['label'=>'Verdana','value'=>'Verdana, sans-serif'],
        ],
        'value'=>'Outfit'
        ],
        ];
        @endphp

        @foreach ($buttonTypes as $type)
        <div class="category-section">

            {{-- Title --}}
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                {{ ucwords(str_replace('_',' ', $type)) }}
            </h3>

            <div class="grid grid-cols-1 gap-4 p-4">

                @foreach ($buttonCustomizeOptions as $item)

                @php
                $key = $type . '_' . $item['key'];
                $value = $customizes[$key] ?? $item['value'];
                @endphp

                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">{{ $item['label'] }}</label>

                    <div class="flex items-center border-rounded border-primary">

                        {{-- RADIO --}}
                        @if ($item['type'] === 'radio')
                        <div class="flex w-43">

                            @foreach ($item['options'] as $option)
                            @php $checked = ($value == $option['value']) ? 'checked' : ''; @endphp

                            <label class="cursor-pointer flex w-full">
                                <input type="radio"
                                    id="{{ $key }}_{{ $option['value'] }}"
                                    name="{{ $key }}"
                                    value="{{ $option['value'] }}"
                                    {{ $checked }}
                                    class="hidden peer">

                                <span class="py-2 flex-1 text-sm text-center border-rounded inline-block
                                            peer-checked:bg-black peer-checked:text-white transition-all duration-200">
                                    {{ $option['label'] }}
                                </span>
                            </label>
                            @endforeach

                        </div>
                        {{-- RANGE --}}
                        @elseif($item['type'] === 'range')
                        <div class="flex items-center w-43 gap-2 px-2 py-3">
                            <input type="range"
                                name="{{ $key }}"
                                min="{{ $item['min'] }}"
                                max="{{ $item['max'] }}"
                                step="{{ $item['step'] }}"
                                value="{{ $value }}"
                                class="w-full accent-black"
                                oninput="this.nextElementSibling.innerText = this.value + '{{ $item['unit'] }}'">

                            <span class="text-xs text-primary min-w-10 text-right">
                                {{ $value }}{{ $item['unit'] }}
                            </span>
                        </div>

                        {{-- SELECT --}}
                        @elseif($item['type'] === 'select')
                        <select name="{{ $key }}" class="focus:ring-0 w-43 p-2.5 text-sm">
                            @foreach($item['options'] as $option)
                            <option value="{{ $option['value'] }}" {{ $value == $option['value'] ? 'selected' : '' }}>
                                {{ $option['label'] }}
                            </option>
                            @endforeach
                        </select>

                        {{-- DEFAULT TEXT FIELD --}}
                        @else
                        <input type="text"
                            name="{{ $key }}"
                            value="{{ $value }}"
                            class="w-34 p-2 border-none text-md" />
                        @endif

                    </div>
                </div>

                @endforeach
            </div>

        </div>
        @endforeach

        @php
        $inputCustomizeOptions = [
            [
                'key'=>'shape','label'=>'Roundness','type'=>'range',
                'min'=>0,'max'=>50,'step'=>2,'unit'=>'px',
                'value'=>4
            ],
            [
                'key'=>'padding_vertical','label'=>'Padding Vertical','type'=>'range',
                'min'=>4,'max'=>40,'step'=>1,'unit'=>'px',
                'value'=>10
            ],
            [
                'key'=>'padding_horizontal','label'=>'Padding Horizontal','type'=>'range',
                'min'=>4,'max'=>40,'step'=>1,'unit'=>'px',
                'value'=>16
            ],
            [
                'key'=>'border_width','label'=>'Border Width','type'=>'range',
                'min'=>0,'max'=>4,'step'=>1,'unit'=>'px',
                'value'=>1
            ]
        ];
        @endphp

        <div class="category-section">
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                Inputs Settings
            </h3>

            <div class="grid grid-cols-1 gap-4 p-4">
                @foreach ($inputCustomizeOptions as $item)
                @php
                $key = 'input_' . $item['key'];
                $value = $customizes[$key] ?? $item['value'];
                @endphp

                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">{{ $item['label'] }}</label>

                    <div class="flex items-center border-rounded border-primary">
                        @if($item['type'] === 'range')
                        <div class="flex items-center w-43 gap-2 px-2 py-3">
                            <input type="range"
                                name="{{ $key }}"
                                min="{{ $item['min'] }}"
                                max="{{ $item['max'] }}"
                                step="{{ $item['step'] }}"
                                value="{{ $value }}"
                                class="w-full accent-black"
                                oninput="this.nextElementSibling.innerText = this.value + '{{ $item['unit'] }}'">

                            <span class="text-xs text-primary min-w-10 text-right">
                                {{ $value }}{{ $item['unit'] }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
