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
            @php 
                $id = $schemeKey.'_'.$item['key'];
                $inputId = 'edit_'.$schemeKey.'_'.$item['key'];
                $isGradient = ($item['type'] ?? 'color') === 'gradient';
            @endphp

            <div class="flex items-center justify-between px-4 py-1 my-4">
                <label class="text-xs w-1/2">
                    {{ $item['label'] }}
                </label>

                <div class="overflow-hidden border-rounded border-primary">
                    @if($isGradient)
                    <input
                        type="text"
                        id="{{ $inputId }}"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        value=""
                        class="h-9.5 w-32 text-black/5 scale-140 auto-save gradient-input outline-0 active:outline-0 focus:outline-0 cursor-pointer border-rounded"
                        data-gradient-picker>
                    @else
                    <input
                        type="color"
                        id="{{ $inputId }}"
                        name="colors[0][{{ $schemeKey }}][{{ $item['key'] }}]"
                        value="#000000"
                        class="h-8 w-32 scale-140 auto-save solid-color-input outline-0 active:outline-0 focus:outline-0 cursor-pointer border-rounded">
                    @endif
                </div>
            </div>
            @endforeach

            @endforeach
            <div class="flex sticky bottom-0 bg-primary border-top items-center">
                <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold" onclick="document.getElementById('colorSchemeEditModal').classList.add('hidden')">
                    Close <i class="fa-solid fa-xmark"></i>
                </button>
                <button type="submit" id="updateColorSchemeBtn" class="bg-invert text-invert flex-1 text-center py-3 uppercase font-semibold">
                    <span id="updateColorSchemeText">Save</span> <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    function openColorSchemeEditModal(schemeId) {

        if (!schemeId) return;

        // Show modal first
        document.getElementById("colorSchemeEditModal").classList.remove("hidden");
        document.getElementById("colorSchemeEditForm").action =
            `/admin/scheme/${schemeId}`;
        
        // Initialize gradient pickers on modal open
        setTimeout(() => {
            if (window.initGradientPickers) {
                window.initGradientPickers();
            }
        }, 100);

        fetch(`/admin/scheme/get/${schemeId}`)
            .then(res => res.json())
            .then(data => {
                // Ensure colors is in array format
                let colors = data.colors;
                
                // If colors is not an array, wrap it in array
                if (!Array.isArray(colors)) {
                    colors = [colors];
                }
                
                // Get first item from array (colors[0])
                const colorData = colors[0] || {};

                // First, set all values in the inputs
                Object.entries(colorData).forEach(([group, items]) => {
                    if (items && typeof items === 'object') {
                        Object.entries(items).forEach(([key, value]) => {
                            const inputId = `edit_${group}_${key}`;
                            const input = document.getElementById(inputId);

                            if (input && value) {
                                input.value = value || '';
                            }
                        });
                    }
                });

                // Then update all pickers after a brief delay
                setTimeout(() => {
                    // Update each input with its value and style
                    Object.entries(colorData).forEach(([group, items]) => {
                        if (items && typeof items === 'object') {
                            Object.entries(items).forEach(([key, value]) => {
                                const inputId = `edit_${group}_${key}`;
                                const input = document.getElementById(inputId);
                                
                                if (input && value) {
                                    // Set the input value
                                    input.value = value || '';
                                    
                                    // Set background style for gradient inputs
                                    if (input.hasAttribute('data-gradient-picker') && value) {
                                        input.style.background = value;
                                        if (value.includes('linear-gradient') || value.includes('gradient')) {
                                            input.style.backgroundImage = value;
                                            input.style.color = 'transparent';
                                            input.style.textShadow = '0 0 0 #333';
                                        }
                                    }
                                    
                                    // Trigger events so pickers can pick up the value
                                    input.dispatchEvent(new Event('input', { bubbles: true }));
                                }
                            });
                        }
                    });
                    
                    // Re-initialize gradient pickers with new values
                    document.querySelectorAll('#colorSchemeEditModal [data-gradient-picker]').forEach(input => {
                        if (input._gradientPicker) {
                            input._gradientPicker.destroy();
                            input._gradientPicker = null;
                        }
                        input._gradientPicker = new GradientPicker(input);
                    });
                }, 200);
            })
            .catch(err => console.error('Scheme load failed', err));
    }
    
    // AJAX form submission for edit color scheme
    document.getElementById('colorSchemeEditForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const saveBtn = document.getElementById('updateColorSchemeBtn');
        const saveText = document.getElementById('updateColorSchemeText');
        const schemeId = form.action.split('/').pop();
        
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
            if (data.status === 'success' || data.success) {
                // Update the scheme card in the list - wait a bit for DB update
                setTimeout(() => {
                    // Direct approach: fetch and update the card
                    if (schemeId) {
                        fetch(`/admin/scheme/get/${schemeId}`)
                            .then(res => res.json())
                            .then(schemeData => {
                                // First try the main function
                                if (typeof window.updateSchemeCard === 'function') {
                                    window.updateSchemeCard(schemeId);
                                } 
                                // Then try direct update
                                else if (typeof window.updateSchemeCardDirectly === 'function') {
                                    window.updateSchemeCardDirectly(schemeData);
                                }
                                // Also try calling addSchemeCardToList directly
                                else if (typeof window.addSchemeCardToList === 'function') {
                                    window.addSchemeCardToList(schemeData);
                                }
                                // Last resort: direct DOM update
                                else {
                                    updateSchemeCardDirectly(schemeData);
                                }
                            })
                            .catch(err => {
                                console.error('Failed to refresh scheme:', err);
                                // Trigger event as fallback
                                const event = new CustomEvent('schemeUpdated', { detail: { schemeId: schemeId } });
                                window.dispatchEvent(event);
                                if (window.parent) {
                                    window.parent.dispatchEvent(event);
                                }
                            });
                    }
                }, 300);
                
                // Reload preview if exists
                const iframe = document.getElementById('livePreviewContent');
                if (iframe) {
                    iframe.contentWindow.location.reload();
                }
            } else {
                alert('Failed to update color scheme');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('An error occurred while updating');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveText.textContent = 'Save';
        });
    });

    // AUTO-SAVE FORM (AJAX) - for auto-save on input change
    function autoSubmitColorForm() {
        const form = document.getElementById('colorSchemeEditForm');
        if (!form) return;
        
        const formData = new FormData(form);
        const schemeId = form.action.split('/').pop();

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
                if (data.status === 'success' || data.success) {
                    // Update the scheme card in the list - wait a bit for DB update
                    setTimeout(() => {
                        if (schemeId) {
                            // Direct fetch and update
                            fetch(`/admin/scheme/get/${schemeId}`)
                                .then(res => res.json())
                                .then(schemeData => {
                                    // Try multiple methods to update
                                    if (typeof window.updateSchemeCard === 'function') {
                                        window.updateSchemeCard(schemeId);
                                    } 
                                    else if (typeof window.addSchemeCardToList === 'function') {
                                        window.addSchemeCardToList(schemeData);
                                    }
                                    else if (typeof window.updateSchemeCardDirectly === 'function') {
                                        window.updateSchemeCardDirectly(schemeData);
                                    }
                                    else {
                                        updateSchemeCardDirectly(schemeData);
                                    }
                                })
                                .catch(err => {
                                    console.error('Auto-save update failed:', err);
                                    // Trigger event as fallback
                                    const event = new CustomEvent('schemeUpdated', { detail: { schemeId: schemeId } });
                                    window.dispatchEvent(event);
                                });
                        }
                    }, 300);
                    
                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) iframe.contentWindow.location.reload();
                }
            })
            .catch(err => console.error('Auto-save error:', err));
    }

    // Direct DOM update function as fallback
    function updateSchemeCardDirectly(schemeData) {
        if (!schemeData) return;
        
        const grid = document.getElementById('colorSchemesGrid');
        if (!grid) {
            console.error('colorSchemesGrid not found');
            return;
        }
        
        const schemeId = schemeData.id;
        const colors = schemeData.colors[0] || schemeData.colors || {};
        
        const schemeColors = colors.scheme_colors || {};
        const primaryBtn = colors.primary_btn || {};
        const secondaryBtn = colors.secondary_btn || {};
        
        const bg = schemeColors.background || '#ffffff';
        const heading = schemeColors.heading || '#111111';
        const paragraph = schemeColors.paragraph || '#3a3a3a';
        const border = schemeColors.border || '#e5e5e5';
        const primaryBtnBg = primaryBtn.background || '#111111';
        const primaryBtnBorder = primaryBtn.border || '#111111';
        const secondaryBtnBg = secondaryBtn.background || 'transparent';
        const secondaryBtnBorder = secondaryBtn.border || '#d4d4d4';
        
        const existingCard = grid.querySelector(`[data-scheme-id="${schemeId}"]`);
        if (existingCard) {
            // Update existing card styles directly
            const cardDiv = existingCard.querySelector('div[onclick]');
            if (cardDiv) {
                cardDiv.style.background = bg;
                cardDiv.style.borderColor = border;
            }
            
            // Update heading color - find the heading element
            const headingContainer = existingCard.querySelector('.flex.items-center.gap-1');
            if (headingContainer) {
                headingContainer.style.color = heading;
            }
            
            // Update paragraph color
            const textSpans = existingCard.querySelectorAll('span.text-\\[10px\\]');
            textSpans.forEach(span => {
                if (!span.querySelector('.fa-font')) { // Not the heading span
                    span.style.color = paragraph;
                }
            });
            
            // Update button previews
            const buttons = existingCard.querySelectorAll('.px-3\\.5, [class*="px-3"]');
            if (buttons.length >= 1 && buttons[0]) {
                buttons[0].style.background = primaryBtnBg;
                buttons[0].style.borderColor = primaryBtnBorder;
            }
            if (buttons.length >= 2 && buttons[1]) {
                buttons[1].style.background = secondaryBtnBg;
                buttons[1].style.borderColor = secondaryBtnBorder;
            }
        } else {
            console.error('Card not found for scheme ID:', schemeId);
        }
    }
    
    // Make function globally available
    window.updateSchemeCardDirectly = updateSchemeCardDirectly;

    // AUTO-SAVE ON INPUT CHANGE (AJAX)
    let colorSaveTimer;

    document.addEventListener('input', e => {
        if (!e.target.classList.contains('auto-save')) return;

        clearTimeout(colorSaveTimer);
        colorSaveTimer = setTimeout(() => {
            autoSubmitColorForm();
        }, 500);
    });
</script>