<div id="editorSidebar"
    class="w-90 pt-16 h-dvh border-right fixed flex top-0 left-0 bg-primary overflow-auto scrollbar">
    <div class="tab-btns flex flex-col border-right bg-primary z-30">
        <button type="button" class="tab-btn font-semibold text-lg p-3 text-secondary bg-tertiary text-primary"
            title="Settings" data-target="basics"><i class="fa-solid fa-cog"></i></button>
        <button type="button" class="tab-btn font-semibold text-lg p-3 text-secondary" title="Sections"
            data-target="sections"><i class="fa-solid fa-indent"></i></button>
        <button type="button" class="tab-btn font-semibold text-lg p-3 text-secondary" title="Apps"
            data-target="apps"><i class="fa-brands fa-google-play"></i></button>
    </div>
    <div class="tab-content w-full h-full overflow-auto scrollbar active relative" id="basics" data-content="basics">
        <form id="customize-form" action="{{ route('admin.customizes.store') }}" method="POST">
            @csrf
            @method('POST')
            @include('tenant.admin.builder.basics.logo-settings')
            @include('tenant.admin.builder.basics.color-schemes')
            @include('tenant.admin.builder.basics.typography-settings')
            @include('tenant.admin.builder.basics.buttons-settings')
            @include('tenant.admin.builder.basics.layout-settings')
            @include('tenant.admin.builder.basics.advanced-settings')
        </form>
        @include('tenant.admin.builder.basics.add-color-scheme')
        @include('tenant.admin.builder.basics.edit-color-scheme')
    </div>
    <div class="tab-content w-full h-full overflow-auto scrollbar hidden" id="sections" data-content="sections">
        @include('tenant.admin.builder.sections.sections')
    </div>
    <div class="tab-content w-full h-full overflow-auto scrollbar hidden" id="apps" data-content="apps">
        <p class="p-4">No App Available Right Now</p>
    </div>
</div>
<script>
    function openCustomizesMenu(menuId, arrowId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);

        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0');
            arrow.classList.add('rotate-90');
            localStorage.setItem(`menu_${menuId}`, 'open');
        } else {
            menu.classList.add('max-h-0');
            arrow.classList.remove('rotate-90');
            localStorage.setItem(`menu_${menuId}`, 'closed');
        }
    }

    // Restore menu states on page load
    document.addEventListener('turbo:load', () => {
        // Restore menus: colors-settings-menu, logo-settings-menu, etc.
        const menus = ['colors-settings-menu', 'logo-settings-menu', 'typography-settings-menu', 'border-shadow-settings-menu', 'buttons-settings-menu', 'layout-settings-menu', 'ui-elements-settings-menu', 'animations-settings-menu', 'advanced-settings-menu'];

        menus.forEach(menuId => {
            const menu = document.getElementById(menuId);
            if (!menu) return;

            const arrowId = menuId.replace('-menu', '').replace('settings', '') + 'arrow'.replace('--', '-');
            const savedState = localStorage.getItem(`menu_${menuId}`);
            const arrow = document.getElementById('arrow-' + menuId.replace('-settings-menu', ''));

            if (savedState === 'open') {
                menu.classList.remove('max-h-0');
                arrow?.classList.add('rotate-90');
            } else {
                menu.classList.add('max-h-0');
                arrow?.classList.remove('rotate-90');
            }
        });
    });

    let customizeSubmitTimeout = null;
    window.activeColorSchemeId = null;
    // Listen for any input, change, or file input inside #customize-form
    document.addEventListener('input', handleCustomizeFormChange);
    document.addEventListener('change', handleCustomizeFormChange);
    document.addEventListener('select', handleCustomizeFormChange);

    function handleCustomizeFormChange(e) {
        // ❌ agar event color scheme modal ke andar se aaya ho → ignore
        if (e.target.closest('.color-scheme-form')) {

            // Agar koi active scheme set hai
            if (window.activeColorSchemeId) {

                clearTimeout(customizeSubmitTimeout);

                // Debounce color scheme save
                customizeSubmitTimeout = setTimeout(() => {
                    submitColorScheme(window.activeColorSchemeId);
                }, 100);

            }

            // ❌ Customize form ko yahan se kabhi submit mat karo
            return;
        }
        const form = e.target.closest('#customize-form');
        if (!form) return; // sirf customize-form ke andar kaam kare

        // Clear previous timeout (debounce)
        clearTimeout(customizeSubmitTimeout);

        // Wait 800ms after last input
        customizeSubmitTimeout = setTimeout(() => {
            submitCustomizesForm(form);
        }, 200);
    }

    // Global function (can be called from buttons too)
    function submitCustomizesForm(form = null) {
        form = form || document.getElementById('customize-form');
        if (!form) return;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' || data.success) {
                    // Optional: reload preview iframe if present
                    reloadPreview()

                } else {
                    console.error('❌ Update failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('AJAX update failed:', err));
    }

    window.addEventListener('turbo:load', () => {
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-target');

                // Remove active class from all buttons and contents
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('bg-tertiary', 'text-primary'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));

                // Add active class to clicked button and corresponding content
                button.classList.add('bg-tertiary', 'text-primary');
                document.querySelector(`.tab-content[data-content="${target}"]`).classList.remove('hidden');

                // ✅ Save current tab to localStorage
                localStorage.setItem('activeTab', target);
            });
        });
    });

    // ✅ On page load, restore the last active tab
    window.addEventListener('turbo:load', () => {
        const savedTab = localStorage.getItem('activeTab');
        if (savedTab) {
            // Remove all active classes first
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('bg-tertiary', 'text-primary'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));

            // Then activate the saved tab
            const activeButton = document.querySelector(`.tab-btn[data-target="${savedTab}"]`);
            const activeContent = document.querySelector(`.tab-content[data-content="${savedTab}"]`);

            if (activeButton && activeContent) {
                activeButton.classList.add('bg-tertiary', 'text-primary');
                activeContent.classList.remove('hidden');
            }
        } else {
            // Optional: Default to first tab if nothing saved
            const firstButton = document.querySelector('.tab-btn');
            const firstContent = document.querySelector('.tab-content');
            if (firstButton && firstContent) {
                firstButton.classList.add('bg-tertiary', 'text-primary');
                firstContent.classList.remove('hidden');
            }
        }
    });

    function openEditorTab(tabName) {
        // Remove active from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-tertiary', 'text-primary');
        });

        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Activate target
        const btn = document.querySelector(`.tab-btn[data-target="${tabName}"]`);
        const content = document.querySelector(`.tab-content[data-content="${tabName}"]`);

        if (btn && content) {
            btn.classList.add('bg-tertiary', 'text-primary');
            content.classList.remove('hidden');

            // persist
            localStorage.setItem('activeTab', tabName);
        }
    }

    window.openEditorTab = openEditorTab;



    //    COLOR SCHEME UPDATE FORM
    function submitColorScheme(schemeId) {

        const colorForm = document.getElementById(`colorSchemeEditForm${schemeId}`);
        const btnText = document.getElementById(`updateColorSchemeText${schemeId}`);
        if (!Number.isInteger(Number(schemeId))) {
            console.error("Invalid schemeId:", schemeId);
            return;
        }

        if (!colorForm) {
            console.error("Color scheme form not found", schemeId);
            return;
        }

        const formData = new FormData(colorForm);

        btnText.innerText = "Saving...";

        fetch(colorForm.action, {
            method: "POST", // _method spoofing allowed
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
            .then(async res => {
                const text = await res.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Non-JSON response:", text);
                    throw e;
                }
            })
            .then(data => {
                btnText.innerText = "Saved";
                if (typeof reloadPreview === 'function') {
                    reloadPreview();
                }
            })
            .catch(err => {
                console.error("Color scheme save failed:", err);
                btnText.innerText = "Save";
                console.log('Update Failed')
            });
    }
</script>