<div class="layout-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('layout-settings-menu', 'arrow-layout')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Layout</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-layout"></i>
    </button>

    <div class="overflow-hidden max-h-0" id="layout-settings-menu">

        @php
        $layoutOptions = [
        'Structure' => [
        [
        'key' => 'container_width',
        'label' => 'Container Width',
        'type' => 'select',
        'options' => [
        ['value'=>'1000','name'=>'Narrow (1000px)'],
        ['value'=>'1200','name'=>'Standard (1200px)'],
        ['value'=>'1400','name'=>'Wide (1400px)'],
        ['value'=>'full','name'=>'Full Width (100%)'],
        ],
        'value' => '1200'
        ],
        [
        'key' => 'global_padding',
        'label' => 'Global Padding',
        'type' => 'select',
        'options' => [
        ['value'=>'0','name'=>'0px'],
        ['value'=>'8','name'=>'8px'],
        ['value'=>'16','name'=>'16px'],
        ['value'=>'24','name'=>'24px'],
        ['value'=>'32','name'=>'32px'],
        ['value'=>'40','name'=>'40px']
        ],
        'value' => '16'
        ],
        ],
        ];
        @endphp

        @foreach ($layoutOptions as $category => $items)
        <div class="category-section">

            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                {{ $category }}
            </h3>

            <div class="grid grid-cols-1 gap-4 p-4">

                @foreach ($items as $item)
                @php
                $key = $item['key'];
                $value = $customizes[$key] ?? $item['value'];
                @endphp

                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">{{ $item['label'] }}</label>

                    <div class="flex items-center border-rounded border-primary">

                        {{-- Radio --}}
                        @if ($item['type'] === 'radio')
                        <div class="flex w-43">
                            @foreach ($item['options'] as $option)
                            @php $checked = ($value === $option) ? 'checked' : ''; @endphp

                            <label class="cursor-pointer flex w-full">
                                <input type="radio"
                                    id="{{ $key }}_{{ $option }}"
                                    name="{{ $key }}"
                                    value="{{ $option }}"
                                    {{ $checked }}
                                    class="hidden peer">

                                <span class="py-2 flex-1 text-sm text-center border-rounded inline-block
                                        peer-checked:bg-black peer-checked:text-white transition-all duration-200">
                                    {{ ucfirst($option) }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        {{-- Select --}}
                        @elseif($item['type'] === 'select')
                        <select name="{{ $key }}" class="focus:ring-0 w-43 p-2 text-sm">
                            @foreach($item['options'] as $option)
                            <option value="{{ $option['value'] }}" {{ $value == $option['value'] ? 'selected' : '' }}>
                                {{ $option['name'] }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Default input --}}
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