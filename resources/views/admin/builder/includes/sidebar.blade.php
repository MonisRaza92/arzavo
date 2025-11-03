<div id="editorSidebar" class="w-[300px] mt-16 h-full border-right fixed top-0 left-0 bg-primary overflow-auto scrollbar pb-10">
    <div class="tab-btns border-bottom flex justify-center sticky top-0 left-0 bg-primary z-10">
        <button type="button" class="tab-btn w-1/2 font-semibold text-sm bg-invert text-invert p-4" data-target="basics"><i class="fas fa-cog"></i> BASICS</button>
        <button type="button" class="tab-btn w-1/2 font-semibold text-sm p-4" data-target="sections"><i class="fas fa-th"></i> SECTIONS</button>
    </div>
    <div class="tab-content active" id="basics" data-content="basics">
        <form id="customize-form" action="{{ route('admin-update-customizes') }}" method="POST">
            @csrf
            @include('admin.customizes.basics.logo-settings')
            @include('admin.customizes.basics.colors-settings')
            @include('admin.customizes.basics.typography-settings')
            @include('admin.customizes.basics.border-shadow-settings')
            @include('admin.customizes.basics.buttons-settings')
            @include('admin.customizes.basics.layout-settings')
            @include('admin.customizes.basics.ui-elements-settings')
            @include('admin.customizes.basics.animations-settings')
            @include('admin.customizes.basics.advanced-settings')
        </form>
    </div>
    <div class="tab-content hidden" id="sections" data-content="sections">
        @include('admin.customizes.sections.sections')
    </div>
</div>
<script>
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
                    console.log('✅ Customizations updated successfully');

                    // Optional: reload preview iframe if present
                    const iframe = document.getElementById('livePreviewContent');
                    if (iframe) {
                        iframe.contentWindow.location.reload();
                    }

                    showSavedToast();
                } else {
                    console.error('❌ Update failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('AJAX update failed:', err));
    }


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

    // ✅ On page load, restore the last active tab
    window.addEventListener('DOMContentLoaded', () => {
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