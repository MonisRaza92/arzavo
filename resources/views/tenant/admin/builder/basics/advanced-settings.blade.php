<div class="advanced-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('advanced-settings-menu','arrow-advanced')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-code mr-1"></i> Advanced</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-advanced"></i>
    </button>

    <div id="advanced-settings-menu" class="overflow-hidden max-h-0">

        @php
        $advancedSettings = [

        'Custom Code' => [
        ['key'=>'custom_css','label'=>'Custom CSS','type'=>'textarea','value'=>''],
        ['key'=>'custom_js','label'=>'Custom JS','type'=>'textarea','value'=>''],
        ],

        'Security & Restrictions' => [
        ['key'=>'right_click','label'=>'Right Click','type'=>'radio','options'=>['enable','disable'],'value'=>'disable'],
        ['key'=>'copy_content','label'=>'Copy Content','type'=>'radio','options'=>['enable','disable'],'value'=>'disable'],
        ['key'=>'inspect_element','label'=>'Inspect Element','type'=>'radio','options'=>['allow','block'],'value'=>'block'],
        ],

        ];
        @endphp

        @foreach($advancedSettings as $category => $items)
        <div class="category-section">

            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">{{ $category }}</h3>

            <div class="grid grid-cols-1 gap-4 p-4">

                @foreach($items as $item)
                @php
                $key = $item['key'];
                $value = $customizes[$key] ?? $item['value'];
                @endphp

                <div class="flex items-center justify-between">

                    <label class="text-primary text-xs">{{ $item['label'] }}</label>

                    {{-- TEXTAREA --}}
                    @if($item['type'] === 'textarea')
                    <textarea
                        name="{{ $key }}"
                        rows="4"
                        class="w-full p-2 border-rounded border-primary focus:ring-0 text-sm">{{ $value }}</textarea>

                    {{-- RADIO --}}
                    @elseif($item['type'] === 'radio')
                    <div class="flex w-43 border-primary border-rounded">
                        @foreach($item['options'] as $option)
                        @php $checked = ($value === $option) ? 'checked' : ''; @endphp

                        <label class="cursor-pointer flex w-full">
                            <input type="radio"
                                id="{{ $key }}_{{ $option }}"
                                name="{{ $key }}"
                                value="{{ $option }}"
                                {{ $checked }}
                                class="hidden peer">

                            <span class="py-2 flex-1 text-sm text-center border-rounded inline-block
                                transition-all duration-200
                                peer-checked:bg-black peer-checked:text-white">
                                {{ ucfirst($option) }}
                            </span>
                        </label>

                        @endforeach
                    </div>

                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
