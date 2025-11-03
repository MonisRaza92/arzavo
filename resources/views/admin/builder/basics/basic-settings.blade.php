<div class="basic-settings bg-primary border-rounded border-primary mb-4">
    <button onclick="openCustomizesMenu('basic-settings-menu', 'arrow-basic')" type="button" class=" p-4 flex justify-between items-center w-full text-lg font-bold uppercase"><span><i class="fa-solid fa-cog"></i> Basic Settings</span> <i class="fas fa-angle-right transition-all duration-300" id="arrow-basic"></i></button>
    <div id="basic-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">

        <!-- LOGO & FAVICONS -->
        <div class="logo-favicon-settings border-top p-4 pb-8 ">
            <h3 class="font-semibold text-primary mb-4 text-lg">
                <i class="fa-solid fa-globe"></i> Logo & Favicon
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([
                ['key' => 'logo', 'label' => 'Logo', 'icon' => 'fa-image', 'bg' => 'bg-primary'],
                ['key' => 'invert_logo', 'label' => 'Invert Logo', 'icon' => 'fa-adjust', 'bg' => 'bg-primary'],
                ['key' => 'favicon', 'label' => 'Favicon', 'icon' => 'fa-star', 'bg' => 'bg-primary'],
                ] as $item)
                @php $key = $item['key']; $hasImage = !empty($customizes[$key]); @endphp

                <div class="relative group border-primary border-rounded overflow-hidden">
                    <label for="{{ $key }}Input" class="cursor-pointer aspect-video">
                        <div class="flex items-center justify-between p-2">
                            <h3 class="font-semibold text-primary text-lg flex items-center gap-2">
                                <i class="fa-solid {{ $item['icon'] }} text-sm opacity-70"></i>
                                {{ $item['label'] }}
                            </h3>
                        </div>

                        <!-- Upload Area -->
                        <div class="relative bg-secondary border-top cursor-pointer" onclick="openImageMenu('{{ $key }}Input')">
                            @if ($hasImage)
                            <img id="{{ $key }}Preview"
                                src="{{ asset($customizes[$key]) }}"
                                alt="{{ $item['label'] }}"
                                class="w-full object-contain p-4">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                <span class="text-white text-sm bg-black/50 px-3 py-1 rounded-md">Change</span>
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center h-32 text-primary/80">
                                <i class="fa-solid {{ $item['icon'] }} text-3xl mb-2"></i>
                                <span class="text-sm">Upload {{ $item['label'] }}</span>
                            </div>
                            @endif
                        </div>

                        <input type="text" name="{{ $key }}" id="{{ $key }}Input" value="{{$customizes[$key]}}" class="hidden" onchange="submitCustomizesForm()">
                        <div class="absolute top-2.5 right-2">
                            <button type="button" class="text-tertiary" onclick="deleteImage('{{ $key }}')">
                                <i class="fa-solid text-md fa-trash"></i>
                            </button>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>


        <!-- THEME & COLORS -->
        <div class="theme-colors-section border-top p-4 pb-8">
            <h3 class="font-semibold text-primary mb-4 text-lg">
                <i class="fa-solid fa-palette"></i> Theme Colors
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ([
                ['key' => 'background_color', 'label' => 'Background Color', 'type' => 'color', 'value' => 'f5f5f5'],
                ['key' => 'primary_color', 'label' => 'Primary Color', 'type' => 'color', 'value' => 'ffffff'],
                ['key' => 'secondary_color', 'label' => 'Secondary Color', 'type' => 'color', 'value' => 'f5f5f5'],
                ['key' => 'accent_color', 'label' => 'Accent Color', 'type' => 'color', 'value' => '111111'],
                ] as $item)
                @php $key = $item['key']; @endphp

                <div class="flex flex-col border-rounded border-primary p-2">
                    <label for="{{ $key }}Input" class="text-primary mb-1">{{ $item['label'] }}</label>
                    <input type="{{ $item['type'] }}" id="{{ $key }}Input" name="{{ $key }}" class="h-10 w-full border-rounded" value="{{ $customizes[$key] ?? $item['value'] }}" class="w-full h-10 border-rounded p-2">
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-colors-section border-top p-4 pb-8">
            <h3 class="font-semibold text-primary mb-4 text-lg">
                <i class="fa-solid fa-font"></i> Text & Colors
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ([
                ['key' => 'primary_text_color', 'label' => 'Primary Color', 'type' => 'color', 'value' => '111111'],
                ['key' => 'secondary_text_color', 'label' => 'Secondary Color', 'type' => 'color', 'value' => '333333'],
                ['key' => 'accent_text_color', 'label' => 'Accent Color', 'type' => 'color', 'value' => '000000'],
                ['key' => 'font_family', 'label' => 'Font Family', 'type' => 'font', 'value' => 'Arial, sans-serif'],
                ] as $item)
                @php $key = $item['key']; @endphp

                <div class="flex flex-col border-rounded border-primary p-2">
                    <label for="{{ $key }}Input" class="font-semibold text-primary mb-2">{{ $item['label'] }}</label>

                    @if($item['type'] === 'font')
                    @php
                    $fonts = [
                    'Arial, sans-serif',
                    'Helvetica, sans-serif',
                    'Verdana, sans-serif',
                    'Tahoma, sans-serif',
                    'Trebuchet MS, sans-serif',
                    'Georgia, serif',
                    'Times New Roman, serif',
                    'Courier New, monospace',
                    'Brush Script MT, cursive',
                    ];
                    $currentFont = $customizes[$key] ?? $item['value'];
                    @endphp
                    <select id="{{ $key }}Input" name="{{ $key }}" class="h-10 w-full border-rounded border-primary px-3">
                        @foreach($fonts as $font)
                        <option value="{{ $font }}" {{ $currentFont === $font ? 'selected' : '' }}>{{ $font }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="{{ $item['type'] }}" id="{{ $key }}Input" name="{{ $key }}" class="h-10 w-full {{ $item['type'] === 'text' ? 'border-rounded border-primary p-3' : '' }}" value="{{ $customizes[$key] ?? $item['value'] }}">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        <!-- BORDER SETTINGS -->
        <div class="border-settings-section border-top p-4 pb-8">
            <h3 class="font-semibold text-primary mb-4 text-lg">
                <i class="fa-solid fa-border-all"></i> Border Width
            </h3>

            <!-- BORDER WIDTH -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([
                ['name' => 'None', 'value' => 'none', 'width' => '0px'],
                ['name' => 'Thin', 'value' => '1px', 'width' => '1px'],
                ['name' => 'Medium', 'value' => '2px', 'width' => '2px'],
                ['name' => 'Thick', 'value' => '3px', 'width' => '3px'],
                ] as $item)
                <div
                    onclick="selectCustomizeOption('border_width', '{{ $item['value'] }}')"
                    class="cursor-pointer border-primary border-rounded p-3 flex flex-col items-center justify-center gap-2 transition 
                    hover:ring-2 hover:ring-primary 
                    {{ ($customizes['border_width'] ?? '') === $item['value'] ? 'ring-2 ring-primary' : '' }}">
                    <div class="w-18 h-18 bg-tertiary border-rounded" style="border: {{ $item['width'] }} solid var(--bg-invert);"></div>
                    <span class="text-sm font-medium text-primary">{{ $item['name'] }}</span>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="border_width" id="border_width" value="{{ $customizes['border_width'] ?? 'none' }}">
        </div>
        <!-- BORDER RADIUS -->
        <div class="border-radius-section border-top p-4 pb-8">
            <h3 class="font-semibold text-primary mb-4 text-lg">
                <i class="fa-solid fa-border-all"></i> Border Radius
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                @foreach ([
                ['name' => 'Sharp', 'value' => 'none', 'radius' => '0px'],
                ['name' => 'Subtle', 'value' => '6px', 'radius' => '6px'],
                ['name' => 'Smooth', 'value' => '12px', 'radius' => '12px'],
                ['name' => 'Rounded', 'value' => '18px', 'radius' => '18px'],
                ['name' => 'Soft', 'value' => '24px', 'radius' => '24px'],
                ['name' => 'Pill', 'value' => '32px', 'radius' => '32px'],
                ] as $item)
                <div
                    onclick="selectCustomizeOption('border_radius', '{{ $item['value'] }}')"
                    class="cursor-pointer border-primary border-rounded p-3 flex flex-col items-center justify-center gap-2 transition 
                    hover:ring-2 hover:ring-primary 
                    {{ ($customizes['border_radius'] ?? '') === $item['value'] ? 'ring-2 ring-primary' : '' }}">
                    <div class="w-18 h-18 bg-tertiary border-rounded" style="border-radius: {{ $item['radius'] }};"></div>
                    <span class="text-sm font-medium text-primary">{{ $item['name'] }}</span>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="border_radius" id="border_radius" value="{{ $customizes['border_radius'] ?? 'none' }}">
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="flex justify-end p-4 border-top">
            <button type="submit" class="btn bg-invert text-invert font-bold px-6 py-2 border-rounded">Save Basic Settings</button>
        </div>
    </div>
</div>