<div class="typography-settings bg-primary border-bottom">

    {{-- HEADER --}}
    <button
        onclick="openCustomizesMenu('typography-settings-menu', 'arrow-typography')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-language mr-1"></i> Typography</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-typography"></i>
    </button>

    {{-- BODY --}}
    <div id="typography-settings-menu"
        class="typography-settings-menu overflow-hidden max-h-0 transition-all duration-500">

        @php
        /**
        * NOTE:
        * - Font size => range (1–100)
        * - Font family => select
        * - Transform => radio
        * - Weight => select
        */

        $fontFamilies = [
        'outfit'=>'Outfit',
        'Arial, sans-serif'=>'Arial',
        'Verdana, sans-serif'=>'Verdana',
        'Helvetica, sans-serif'=>'Helvetica',
        'Georgia, serif'=>'Georgia',
        'Times New Roman, serif'=>'Times New Roman',
        'Courier New, monospace'=>'Courier New',
        'Tahoma, sans-serif'=>'Tahoma',
        'Trebuchet MS, sans-serif'=>'Trebuchet MS',
        ];

        $weights = ['200'=>'Thin','400'=>'Normal','600'=>'Semibold','800'=>'Bold', '900'=>'Extra Bold'];

        $typographyCategories = [

        'Font Family' => [
        ['key'=>'heading_font_family','label'=>'Heading','type'=>'select','options'=>$fontFamilies,'value'=>'Arial, sans-serif'],
        ['key'=>'paragraph_font_family','label'=>'Paragraph','type'=>'select','options'=>$fontFamilies,'value'=>'Arial, sans-serif'],
        ['key'=>'secondary_text_font_family','label'=>'Body Text','type'=>'select','options'=>$fontFamilies,'value'=>'Arial, sans-serif'],
        ['key'=>'link_font_family','label'=>'link','type'=>'select','options'=>$fontFamilies,'value'=>'Arial, sans-serif'],
        ],

        'Heading 1' => [
        ['key'=>'heading_1_font_size','label'=>'Font Size','type'=>'range','value'=>44],
        ['key'=>'heading_1_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_1_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_1_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],

        'Heading 2' => [
        ['key'=>'heading_2_font_size','label'=>'Font Size','type'=>'range','value'=>40],
        ['key'=>'heading_2_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_2_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_2_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],
        'Heading 3' => [
        ['key'=>'heading_3_font_size','label'=>'Font Size','type'=>'range','value'=>36],
        ['key'=>'heading_3_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_3_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_3_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],
        'Heading 4' => [
        ['key'=>'heading_4_font_size','label'=>'Font Size','type'=>'range','value'=>32],
        ['key'=>'heading_4_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_4_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_4_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],
        'Heading 5' => [
        ['key'=>'heading_5_font_size','label'=>'Font Size','type'=>'range','value'=>28],
        ['key'=>'heading_5_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_5_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_5_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],
        'Heading 6' => [
        ['key'=>'heading_6_font_size','label'=>'Font Size','type'=>'range','value'=>24],
        ['key'=>'heading_6_line_height','label'=>'Line Height','type'=>'select','options'=>['1.1'=>'Compact','1.3'=>'Standard','1.8'=>'Spacious'],'value'=>'1.1'],
        ['key'=>'heading_6_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','uppercase'],'value'=>'default'],
        ['key'=>'heading_6_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'600'],
        ],

        'Paragraph' => [
        ['key'=>'paragraph_font_size','label'=>'Font Size','type'=>'range','value'=>16],
        ['key'=>'paragraph_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','capitalize'],'value'=>'default'],
        ['key'=>'paragraph_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'400'],
        ],

        'Body' => [
        ['key'=>'body_text_font_size','label'=>'Font Size','type'=>'range','value'=>12],
        ['key'=>'body_text_text_transform','label'=>'Transform','type'=>'radio','options'=>['default','capitalize'],'value'=>'default'],
        ['key'=>'body_text_weight','label'=>'Weight','type'=>'select','options'=>$weights,'value'=>'400'],
        ],
        ];
        @endphp

        {{-- RENDER --}}
        @foreach ($typographyCategories as $category => $items)

        <div class="border-top">
            <h3 class="text-sm px-4 py-3 font-semibold text-primary border-bottom">
                {{ $category }}
            </h3>

            <div class="grid gap-4 p-4">

                @foreach ($items as $item)
                @php
                $key = $item['key'];
                $value = $customizes[$key] ?? $item['value'];
                @endphp

                <div class="flex justify-between items-center">
                    <label class="text-xs text-primary">{{ $item['label'] }}</label>

                    {{-- RANGE (FONT SIZE) --}}
                    @if ($item['type'] === 'range')
                    <div class="flex items-center gap-2 w-38 py-2">
                        <input
                            type="range"
                            min="1"
                            max="100"
                            step="1"
                            name="{{ $key }}"
                            value="{{ $value }}"
                            oninput="this.nextElementSibling.textContent = this.value"
                            class="w-full cursor-pointer accent-black">

                        <span class="text-xs border-rounded text-center p-1 w-8 bg-secondary font-semibold">
                            {{ $value }}
                        </span>
                    </div>

                    {{-- RADIO --}}
                    @elseif ($item['type'] === 'radio')
                    <div class="flex border-primary border-rounded w-38 overflow-hidden">
                        @foreach ($item['options'] as $opt)
                        <label class="cursor-pointer w-full flex">
                            <input type="radio"
                                name="{{ $key }}"
                                value="{{ $opt }}"
                                class="hidden peer"
                                {{ $value === $opt ? 'checked' : '' }}>
                            <span class="text-center flex-1 p-2.5 text-xs border-rounded peer-checked:bg-black peer-checked:text-white">
                                {{ ucfirst($opt) }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                    {{-- SELECT --}}
                    @else
                    <select name="{{ $key }}" class="w-38 p-2 text-sm border-rounded border-primary">
                        @foreach ($item['options'] as $k => $v)
                        <option value="{{ is_numeric($k) ? $k : $v }}"
                            {{ $value == (is_numeric($k) ? $k : $v) ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>

                @endforeach
            </div>
        </div>

        @endforeach
    </div>
</div>
