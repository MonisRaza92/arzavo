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
                <div class="overflow-hidden border-rounded border-primary p-1">
                    <input
                        type="text"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        class="h-8 w-32 auto-save color-input outline-0 active:outline-0 focus:outline-0    "
                        data-coloris>
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
    }


    function closeColorSchemeModal() {
        document.getElementById("colorSchemeModal").classList.add("hidden");
    }
</script>