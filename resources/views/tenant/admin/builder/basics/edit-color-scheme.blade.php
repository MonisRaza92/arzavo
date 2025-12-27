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
                <label class="text-xs">{{ $item['label'] }}</label>
                <div class="flex items-center gap-1">
                    <div class="h-8 w-8 border-rounded border-primary overflow-hidden">
                        <div id="edit_colorPicker{{ $id }}" class="h-full w-full cursor-pointer"></div>
                    </div>
                    <input type="text"
                        id="edit_{{ $id }}Code"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        class="h-8 w-24 ml-2 p-2 border-rounded border-primary auto-save">
                </div>
            </div>
            @endforeach
            @endforeach


            <div class="flex sticky bottom-0 bg-primary border-top items-center">
                <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold" onclick="closeColorSchemeEditModal()">
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
    let editPickers = [];

    function openColorSchemeEditModal(schemeId) {

        document.getElementById("colorSchemeEditModal").classList.remove("hidden");
        document.getElementById("colorSchemeEditForm").action = `/admin/scheme/${schemeId}`;

        fetch(`/admin/scheme/get/${schemeId}`)
            .then(res => res.json())
            .then(data => {

                const colors = Array.isArray(data.colors) ?
                    data.colors[0] :
                    data.colors;

                Object.entries(colors).forEach(([group, items]) => {
                    Object.entries(items).forEach(([key, value]) => {

                        const inputId = `edit_${group}_${key}Code`;
                        const pickerBoxId = `edit_colorPicker${group}_${key}`;

                        const inputEl = document.getElementById(inputId);
                        const pickerBox = document.getElementById(pickerBoxId);

                        if (inputEl) inputEl.value = value;
                        if (pickerBox) pickerBox.style.background = value;
                    });
                });

                setTimeout(initEditPickers, 50);
            });
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
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('auto-save') && e.target.closest('#colorSchemeEditModal')) {
            autoSubmitColorForm(); // AJAX submit, no page refresh
        }
    });

    // NORMAL FORM SUBMIT (FULL REFRESH ON SAVE BUTTON)
    document.getElementById('colorSchemeEditForm').addEventListener('submit', function(e) {
        // DO NOT preventDefault → full page refresh
    });

    // CLOSE MODAL
    function closeColorSchemeEditModal() {
        document.getElementById("colorSchemeEditModal").classList.add("hidden");
        editPickers.forEach(p => p.destroy());
        editPickers = [];
    }

    // INITIALIZE COLOR PICKERS
    function initEditPickers() {
        editPickers.forEach(p => p.destroy());
        editPickers = [];

        const inputs = document.querySelectorAll("#colorSchemeEditModal input");

        inputs.forEach(input => {
            const inputId = input.id;
            const schemeId = inputId.replace("edit_", "").replace("Code", "");
            const pickerEl = document.getElementById(`edit_colorPicker${schemeId}`);

            if (!pickerEl) return;

            const instance = Pickr.create({
                el: `#edit_colorPicker${schemeId}`,
                theme: "monolith",
                default: input.value || "",
                comparison: true,
                swatches: [
                    '#920000', '#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#03A9F4', '#00BCD4', '#009688',
                    '#4CAF50', '#8BC34A', '#FFEB3B', '#FFC107', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B', '#000000', '#FFFFFF'
                ],
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        save: true,
                        cancel: true
                    }
                }
            });

            instance.on("save", (color) => {
                const hex = color.toHEXA().toString();
                input.value = hex;
                pickerEl.style.background = hex;

                setTimeout(() => {
                    instance.hide();
                    autoSubmitColorForm(); // AJAX submit on save
                }, 100);
            });

            instance.on("cancel", () => {
                setTimeout(() => instance.hide(), 100);
            });

            input.addEventListener("input", (e) => {
                setTimeout(() => {
                    pickerEl.style.background = e.target.value;
                    instance.setColor(e.target.value);
                }, 800);
            });

            editPickers.push(instance);
        });
    }
</script>