<div class="logo-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('logo-settings-menu', 'arrow-logo')" type="button" class=" p-4 flex justify-between items-center w-full text-md font-semibold"><span><i class="fa-solid fa-globe text-sm"></i> Logo & Favicon</span> <i class="fas fa-angle-right transition-all duration-300" id="arrow-logo"></i></button>
    <div id="logo-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
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
                    <div id="{{ $key }}PreviewContainer" class="relative bg-secondary border-top cursor-pointer" onclick="openImageMenu('{{ $key }}Input')">
                        @if ($hasImage)
                        <img id="{{ $key }}Preview"
                            src="{{ asset($customizes[$key]) }}"
                            alt="{{ $item['label'] }}"
                            class="w-full object-contain p-4">
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                            <span class="text-white text-sm bg-black/50 px-3 py-1 rounded-md">Change</span>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center h-32 text-primary">
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
</div>
<script>
    function deleteImage(key) {
        document.getElementById(key + 'Input').value = '';
        const overlay = document.getElementById(key + 'Preview');
        overlay.src = '{{ asset('images/ARZAQ-dark-logo.png')}}';
        submitCustomizesForm();
    }
</script>