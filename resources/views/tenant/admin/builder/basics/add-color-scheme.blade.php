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
            @php 
                $id = $schemeKey.'_'.$item['key'];
                $inputId = $schemeKey.'_'.$item['key'];
                $isGradient = ($item['type'] ?? 'color') === 'gradient';
            @endphp

            <div class="flex items-center justify-between px-4 py-1 my-4">
                <label class="text-xs">{{ $item['label'] }}
                </label>
                <div class="overflow-hidden border-rounded border-primary">
                    @if($isGradient)
                    <input
                        type="text"
                        id="{{ $inputId }}"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        value="{{ $item['default'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}"
                        class="h-9.5 w-32 scale-140 auto-save gradient-input outline-0 active:outline-0 focus:outline-0 cursor-pointer border-rounded"
                        data-gradient-picker>
                    @else
                    <input
                        type="color"
                        id="{{ $inputId }}"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        value="{{ $item['default'] ?? '#000000' }}"
                        class="h-8 w-32 scale-140 auto-save solid-color-input outline-0 active:outline-0 focus:outline-0 cursor-pointer border-rounded">
                    @endif
                </div>
            </div>
            @endforeach
            @endforeach


            <div class="flex sticky bottom-0 bg-primary border-top items-center">
                <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold" onclick="closeColorSchemeModal()">
                    Close <i class="fa-solid fa-xmark"></i>
                </button>
                <button type="submit" id="saveColorSchemeBtn" class="bg-invert text-invert flex-1 text-center py-3 uppercase font-semibold">
                    <span id="saveColorSchemeText">Save</span> <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function openColorSchemeModal() {
        document.getElementById("colorSchemeModal").classList.remove("hidden");
        // Reset form
        document.getElementById('colorSchemeForm').reset();
        // Initialize gradient pickers
        setTimeout(() => {
            if (window.initGradientPickers) {
                window.initGradientPickers();
            }
        }, 100);
    }

    function closeColorSchemeModal() {
        document.getElementById("colorSchemeModal").classList.add("hidden");
        // Reset form
        document.getElementById('colorSchemeForm').reset();
    }

    // AJAX form submission for add color scheme
    document.getElementById('colorSchemeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const saveBtn = document.getElementById('saveColorSchemeBtn');
        const saveText = document.getElementById('saveColorSchemeText');
        
        // Disable button
        saveBtn.disabled = true;
        saveText.textContent = 'Saving...';
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.id) {
                // Close modal
                closeColorSchemeModal();
                
                // Fetch the new scheme and add to list
                const schemeId = data.id || data.scheme?.id;
                if (schemeId) {
                    fetch(`/admin/scheme/get/${schemeId}`)
                        .then(res => res.json())
                        .then(schemeData => {
                            addSchemeCardToList(schemeData);
                            
                            // Reload preview if exists
                            const iframe = document.getElementById('livePreviewContent');
                            if (iframe) {
                                iframe.contentWindow.location.reload();
                            }
                        });
                }
            } else {
                alert('Failed to save color scheme');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('An error occurred while saving');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveText.textContent = 'Save';
        });
    });
</script>
