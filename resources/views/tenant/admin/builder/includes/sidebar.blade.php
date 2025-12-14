<div id="editorSidebar" class="w-[300px] pt-16 h-dvh border-right fixed top-0 left-0 bg-primary overflow-auto scrollbar">
    <div class="tab-btns border-bottom flex justify-center sticky top-0 left-0 bg-primary z-30">
        <button type="button" class="tab-btn w-1/2 font-semibold text-lg border-right bg-invert text-invert p-3" title="Settings" data-target="basics"><i class="fas fa-cog"></i></button>
        <button type="button" class="tab-btn w-1/2 font-semibold text-lg border-right p-3" title="Sections" data-target="sections"><i class="fa-solid fa-code"></i></button>
        <button type="button" class="tab-btn w-1/2 font-semibold text-lg p-3" title="Apps" data-target="apps"><i class="fa-solid fa-layer-group"></i></button>
    </div>
    <div class="tab-content active relative" id="basics" data-content="basics">
        <form id="customize-form" action="{{ route('admin.customizes.store') }}" method="POST">
            @csrf
            @include('tenant.admin.builder.basics.logo-settings')
            @include('tenant.admin.builder.basics.color-schemes')
            @include('tenant.admin.builder.basics.typography-settings')
            @include('tenant.admin.builder.basics.border-shadow-settings')
            @include('tenant.admin.builder.basics.buttons-settings')
            @include('tenant.admin.builder.basics.layout-settings')
            <!-- @include('tenant.admin.builder.basics.ui-elements-settings') -->
            <!-- @include('tenant.admin.builder.basics.animations-settings') -->
            @include('tenant.admin.builder.basics.advanced-settings')
        </form>
        @include('tenant.admin.builder.basics.add-color-scheme')
        @include('tenant.admin.builder.basics.edit-color-scheme')
    </div>
    <div class="tab-content hidden" id="sections" data-content="sections">
        @include('tenant.admin.builder.sections.sections')
    </div>
    <div class="tab-content hidden" id="apps" data-content="apps">
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

    // Listen for any input, change, or file input inside #customize-form
    document.addEventListener('input', handleCustomizeFormChange);
    document.addEventListener('change', handleCustomizeFormChange);
    document.addEventListener('select', handleCustomizeFormChange);

    function handleCustomizeFormChange(e) {
        const form = e.target.closest('#customize-form');
        if (!form) return; // sirf customize-form ke andar kaam kare

        // Clear previous timeout (debounce)
        clearTimeout(customizeSubmitTimeout);

        // Wait 800ms after last input
        customizeSubmitTimeout = setTimeout(() => {
            submitCustomizesForm(form);
        }, 800);
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
                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) {
                        iframe.contentWindow.location.reload();
                    }

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
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('bg-invert', 'text-invert'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));

                // Add active class to clicked button and corresponding content
                button.classList.add('bg-invert', 'text-invert');
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
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('bg-invert', 'text-invert'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));

            // Then activate the saved tab
            const activeButton = document.querySelector(`.tab-btn[data-target="${savedTab}"]`);
            const activeContent = document.querySelector(`.tab-content[data-content="${savedTab}"]`);

            if (activeButton && activeContent) {
                activeButton.classList.add('bg-invert', 'text-invert');
                activeContent.classList.remove('hidden');
            }
        } else {
            // Optional: Default to first tab if nothing saved
            const firstButton = document.querySelector('.tab-btn');
            const firstContent = document.querySelector('.tab-content');
            if (firstButton && firstContent) {
                firstButton.classList.add('bg-invert', 'text-invert');
                firstContent.classList.remove('hidden');
            }
        }
    });
</script>