<!-- COLOR SCHEME MODAL -->
<div id="colorSchemeModal"
    class="absolute bg-primary top-0 left-0 right-0 bottom-0 z-29 hidden">
    <form id="colorSchemeForm" action="{{ route('admin.scheme.store') }}" method="POST">
        @csrf

        <div id="schemeFields">
            @foreach(config('color_schemes.color_schemes') as $schemeKey => $items)
            <h3 class="font-semibold text-primary p-4 border-bottom {{ $schemeKey === 'scheme_colors' ? '' : 'border-top' }}">
                {{ ucwords(str_replace('_', ' ', $schemeKey)) }}
            </h3>

            @foreach($items as $item)
            @php $id = $schemeKey.'_'.$item['key']; @endphp

            <div class="flex items-center justify-between px-4 py-1 my-4">
                <label class="text-xs">{{ $item['label'] }}</label>
                <div class="flex items-center gap-1">
                    <div class="h-8 w-8 border-rounded border-primary overflow-hidden">
                        <div id="colorPicker{{ $id }}" class="h-full w-full cursor-pointer"></div>
                    </div>
                    <input type="text"
                        id="{{ $id }}Code"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        class="h-8 w-24 ml-2 p-2 border-rounded border-primary auto-save">
                </div>
            </div>
            @endforeach
            @endforeach


            <div class="flex sticky bottom-0 bg-primary border-top items-center">
                <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold" onclick="closeColorSchemeModal()">
                    Close <i class="fa-solid fa-xmark"></i>
                </button>
                <button type="submit" class="bg-invert text-invert flex-1 text-center py-3 uppercase font-semibold">
                    Save <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function openColorSchemeModal() {
        document.getElementById("colorSchemeModal").classList.remove("hidden");
        setTimeout(initPickers, 50); // delay so DOM is visible
    }
    

    function closeColorSchemeModal() {
        document.getElementById("colorSchemeModal").classList.add("hidden");
    }

    let pickrInstances = [];

    function initPickers() {

        if (pickrInstances.length) return; // prevent duplicate init

        const schemeConfigs = {
            @foreach(config('color_schemes.color_schemes') as $schemeKey => $items)
            @foreach($items as $item)
            @php $id = $schemeKey.
            '_'.$item['key'];@endphp '{{ $id }}': {
                el: '#colorPicker{{ $id }}',
                inputId: '{{ $id }}Code'
            },
            @endforeach
            @endforeach
        };

        Object.entries(schemeConfigs).forEach(([id, config]) => {
            const instance = Pickr.create({
                el: config.el,
                theme: 'monolith',
                default: document.getElementById(config.inputId).value || '',
                comparison: true,
                swatches: [
                    '#920000',
                    '#F44336',
                    '#E91E63',
                    '#9C27B0',
                    '#673AB7',
                    '#3F51B5',
                    '#2196F3',
                    '#03A9F4',
                    '#00BCD4',
                    '#009688',
                    '#4CAF50',
                    '#8BC34A',
                    '#FFEB3B',
                    '#FFC107',
                    '#FF9800',
                    '#FF5722',
                    '#795548',
                    '#9E9E9E',
                    '#607D8B',
                    '#000000',
                    '#FFFFFF'
                ],
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        cancel: true,
                        save: true
                    }
                }
            });

            document.getElementById(config.inputId).addEventListener('input', (e) => {
                setTimeout(() => {
                    if (e.target.value) {
                        instance.setColor(e.target.value);
                    } else {
                        instance.setColor(null);
                    }
                }, 1000);
            });
            instance.on('save', (color) => {
                const value = color.toHEXA().toString();
                document.getElementById(config.inputId).value = value;
                pickrInstances.forEach(p => p.hide());
            });
            // instance.on('change', (color) => {
            //     const value = color.toHEXA().toString();
            //     document.getElementById(config.inputId).value = value;
            // });
            instance.on('cancel', () => {
                const value = document.getElementById(config.inputId).value = '';
                instance.setColor(null);
                pickrInstances.forEach(p => p.hide());
            });

            pickrInstances.push(instance);
        });
    }
</script>