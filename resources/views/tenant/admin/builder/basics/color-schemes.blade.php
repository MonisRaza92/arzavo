<div class="colors-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('colors-settings-menu', 'arrow-colors')" type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-brush mr-1"></i> Colors</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-colors"></i>
    </button>

    <div class="colors-settings-menu overflow-hidden max-h-0" id="colors-settings-menu">
        <div class="info p-4 border-top pb-0">
            <h3 class="text-sm font-semibold text-primary">Saved Schemes</h3>
            <p class="text-xs text-secondary mt-1">Color schemes will be applied to sections throughout your website.
            </p>
        </div>
        <div class="grid grid-cols-3 gap-2 p-4" id="colorSchemesGrid">

            @foreach($colorSchemes as $scheme)

                @php
                    $bg = $scheme->scheme_colors->background;
                    $heading = $scheme->scheme_colors->heading;
                    $paragraph = $scheme->scheme_colors->paragraph;
                    $border = $scheme->scheme_colors->border;

                    $primaryBtnBg = $scheme->primary_btn->background;
                    $primaryBtnText = $scheme->primary_btn->text;
                    $primaryBtnBorder = $scheme->primary_btn->border;

                    $secondaryBtnBg = $scheme->secondary_btn->background;
                    $secondaryBtnText = $scheme->secondary_btn->text;
                    $secondaryBtnBorder = $scheme->secondary_btn->border;
                @endphp

                <div data-scheme-id="{{ $scheme->id }}">
                    <div class="h-16 p-2 border-primary border-rounded cursor-pointer flex flex-col justify-center items-center"
                        onclick="window.activeColorSchemeId = {{ $scheme->id }}; document.getElementById('colorSchemeEditModal{{ $scheme->id }}').classList.remove('hidden')"
                        style="background: {{ $bg }}; border-color: {{ $border }};">

                        <div class="text-center">
                            <!-- Text Preview -->
                            <div class="flex items-center gap-1 -mb-1.5" style="color: {{ $heading }};">
                                <i class="fa-solid fa-font text-[10px]"></i>
                                <span class="text-xs font-semibold">Heading</span>
                            </div>
                            <span class="text-[10px]" style="color: {{ $paragraph }};">Paragraph</span>
                        </div>

                        <!-- Buttons Preview -->
                        <div class="flex gap-2 items-center mt-auto">
                            <div class="px-3.5 py-1 border-rounded border"
                                style="background: {{ $primaryBtnBg }}; border-color: {{ $primaryBtnBorder }};">
                            </div>

                            <div class="px-3.5 py-1 border-rounded border"
                                style="background: {{ $secondaryBtnBg }}; border-color: {{ $secondaryBtnBorder }};">
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-center mt-1">
                        {{ ucwords(str_replace('_', ' ', $scheme->key)) }}
                    </p>
                </div>

            @endforeach

            <!-- ADD NEW SCHEME CARD -->
            <div class="p-4 border border-primary h-16 border-rounded cursor-pointer flex flex-col justify-center items-center text-center transition-all hover:opacity-80"
                onclick="openColorSchemeModal()" id="addNewSchemeCard">
                <div class="text-2xl font-bold">+</div>
                <div class="text-[10px] font-semibold">Add New</div>
            </div>

        </div>

        <script>
            // Function to add or update scheme card in the list
            function addSchemeCardToList(schemeData) {
                const grid = document.getElementById('colorSchemesGrid');
                if (!grid) {
                    console.error('colorSchemesGrid not found');
                    return;
                }

                const schemeId = schemeData.id;
                if (!schemeId) {
                    console.error('Scheme ID missing in data');
                    return;
                }

                const colors = schemeData.colors[0] || schemeData.colors || {};

                // Extract colors with fallbacks
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

                // Check if card already exists - search within grid
                let existingCard = grid.querySelector(`[data-scheme-id="${schemeId}"]`);

                // Escape CSS values for safe insertion (handle quotes and special chars)
                const escapeCssValue = (val) => {
                    if (!val) return '';
                    // Replace any single quotes with escaped version for onclick attributes
                    return String(val).replace(/'/g, "\\'");
                };

                const cardHTML = `
            <div data-scheme-id="${schemeId}">
                <div class="h-16 p-2 border-primary border-rounded cursor-pointer flex flex-col justify-center items-center"
                    onclick="openColorSchemeEditModal('${schemeId}')"
                    style="background: ${bg}; border-color: ${border};">
                    <div class="text-center">
                        <div class="flex items-center gap-1 -mb-1.5" style="color: ${heading};">
                            <i class="fa-solid fa-font text-[10px]"></i>
                            <span class="text-xs font-semibold">Heading</span>
                        </div>
                        <span class="text-[10px]" style="color: ${paragraph};">Paragraph</span>
                    </div>
                    <div class="flex gap-2 items-center mt-auto">
                        <div class="px-3.5 py-1 border-rounded border"
                            style="background: ${primaryBtnBg}; border-color: ${primaryBtnBorder};">
                        </div>
                        <div class="px-3.5 py-1 border-rounded border"
                            style="background: ${secondaryBtnBg}; border-color: ${secondaryBtnBorder};">
                        </div>
                    </div>
                </div>
                <p class="text-xs text-center mt-1">Scheme ${schemeId}</p>
            </div>
        `;

                if (existingCard) {
                    // Update existing card - replace the entire card
                    existingCard.outerHTML = cardHTML;
                    console.log('Scheme card updated:', schemeId);
                } else {
                    // Add new card before "Add New" card
                    const addNewCard = document.getElementById('addNewSchemeCard');
                    if (addNewCard) {
                        addNewCard.insertAdjacentHTML('beforebegin', cardHTML);
                        console.log('New scheme card added:', schemeId);
                    } else {
                        grid.insertAdjacentHTML('beforeend', cardHTML);
                        console.log('New scheme card added to end:', schemeId);
                    }
                }
            }

            // Function to update scheme card (for edit)
            function updateSchemeCard(schemeId) {
                if (!schemeId) {
                    console.error('Scheme ID is required');
                    return;
                }

                fetch(`/admin/scheme/get/${schemeId}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to fetch scheme');
                        }
                        return res.json();
                    })
                    .then(schemeData => {
                        if (schemeData && schemeData.id) {
                            addSchemeCardToList(schemeData);
                        } else {
                            console.error('Invalid scheme data received');
                        }
                    })
                    .catch(err => {
                        console.error('Failed to fetch scheme:', err);
                        // Try again after a short delay
                        setTimeout(() => {
                            fetch(`/admin/scheme/get/${schemeId}`)
                                .then(res => res.json())
                                .then(schemeData => {
                                    if (schemeData && schemeData.id) {
                                        addSchemeCardToList(schemeData);
                                    }
                                })
                                .catch(e => console.error('Retry failed:', e));
                        }, 300);
                    });
            }

            // Make functions available globally - ensure they're always accessible
            window.addSchemeCardToList = addSchemeCardToList;
            window.updateSchemeCard = updateSchemeCard;

            // Expose functions globally on multiple events to ensure availability
            function exposeColorSchemeFunctions() {
                window.addSchemeCardToList = addSchemeCardToList;
                window.updateSchemeCard = updateSchemeCard;
            }

            // Expose on DOMContentLoaded
            document.addEventListener('DOMContentLoaded', exposeColorSchemeFunctions);

            // Expose on turbo:load (for Turbo navigation)
            document.addEventListener('turbo:load', exposeColorSchemeFunctions);

            // Expose immediately
            exposeColorSchemeFunctions();

            // Listen for custom scheme update events
            window.addEventListener('schemeUpdated', (e) => {
                if (e.detail && e.detail.schemeId) {
                    updateSchemeCard(e.detail.schemeId);
                }
            });

            // Listen on turbo:load as well
            document.addEventListener('turbo:load', () => {
                window.addEventListener('schemeUpdated', (e) => {
                    if (e.detail && e.detail.schemeId) {
                        updateSchemeCard(e.detail.schemeId);
                    }
                });
            });
        </script>
    </div>

</div>
<script>
    function openColorSchemeModal() {
        const modal = document.getElementById("colorSchemeModal");
        const form = document.getElementById("colorSchemeForm");

        modal.classList.remove("hidden");
        form.reset();
    }

    function closeColorSchemeModal() {
        const modal = document.getElementById("colorSchemeModal");
        const form = document.getElementById("colorSchemeForm");

        modal.classList.add("hidden");
        form.reset();
    }
</script>