<!-- COLOR SCHEME MODAL -->
<div id="colorSchemeEditModal"
    class="absolute bg-primary top-0 left-0 right-0 bottom-0 z-29 hidden">
    <form id="colorSchemeEditForm" action="" method="POST">
        @csrf
        @method('PUT')


        <div id="schemeFields">
            @foreach(config('color_schemes.color_schemes') as $schemeKey => $items)

            <h3 class="font-semibold text-primary p-4 border-bottom {{ $schemeKey === 'scheme_colors' ? '' : 'border-top' }}">
                {{ ucwords(str_replace('_', ' ', $schemeKey)) }}
            </h3>

            @foreach($items as $item)
            @php $id = $schemeKey.'_'.$item['key']; @endphp

            <div class="flex items-center justify-between px-4 py-1 my-4">
                <label class="text-xs w-1/2">
                    {{ $item['label'] }}
                </label>

                <div class="overflow-hidden border-rounded border-primary p-1">
                    <input
                        type="text"
                        id="edit_{{ $id }}Code"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        class="h-8 w-32 auto-save color-input outline-0 active:outline-0 focus:outline-0    "
                        data-coloris>
                </div>
            </div>
            @endforeach

            @endforeach
            <div class="flex sticky bottom-0 bg-primary border-top items-center">
                <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold" onclick="document.getElementById('colorSchemeEditModal').classList.add('hidden')">
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
    function openColorSchemeEditModal(schemeId) {

        if (!schemeId) return;

        document.getElementById("colorSchemeEditModal").classList.remove("hidden");
        document.getElementById("colorSchemeEditForm").action =
            `/admin/scheme/${schemeId}`;

        fetch(`/admin/scheme/get/${schemeId}`)
            .then(res => res.json())
            .then(data => {

                const colors = Array.isArray(data.colors) ?
                    data.colors[0] :
                    data.colors;

                Object.entries(colors).forEach(([group, items]) => {
                    Object.entries(items).forEach(([key, value]) => {

                        const input = document.getElementById(
                            `edit_${group}_${key}Code`
                        );

                        if (!input) return;

                        input.value = value;
                    });
                });

                // 🔥 THIS IS THE MISSING PIECE
                if (window.Coloris) {
                    Coloris.refresh();
                }
            })
            .catch(err => console.error('Scheme load failed', err));
    }
    
    // AUTO-SAVE FORM (AJAX)
    function autoSubmitColorForm() {
        const form = document.getElementById('colorSchemeEditForm');
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) iframe.contentWindow.location.reload();
                } else {
                    console.error('Update failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('Error:', err));
    }

    // AUTO-SAVE ON INPUT CHANGE (AJAX)
    let colorSaveTimer;

    document.addEventListener('input', e => {
        if (!e.target.classList.contains('auto-save')) return;

        clearTimeout(colorSaveTimer);
        colorSaveTimer = setTimeout(() => {
            autoSubmitColorForm();
        }, 100);
    });
</script>