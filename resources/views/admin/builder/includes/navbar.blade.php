<div class="editor-navbar flex w-full h-16 justify-between items-center py-2 bg-primary px-4 border-bottom fixed top-0 left-0 z-50">
    <div class="editor-navbar-left flex items-center gap-4">
        <a href="{{ route('admin') }}" class="text-2xl"><i class="fa-solid fa-right-from-bracket rotate-180"></i></a>
        <h3 class="text-primary font-semibold text-xl hidden md:block">Horizon</h3>
        <div class="badge bg-green-100 hidden md:block px-2 py-1 text-xs rounded-full"><i class="fas fa-circle text-green-500"></i> Live</div>
    </div>
    <div class="editor-navbar-center flex items-center gap-4">
        <form action="{{ route('admin.builder.index') }}" method="GET" id="pageSelectForm">
            <div class="relative inline-block lg:w-56 w-auto">
                <!-- Button -->
                <button id="pageSelectBtn" type="button"
                    class="flex items-center justify-between w-full p-2 md:px-4 border border-gray-300 rounded font-semibold bg-white">
                    <span id="selectedPage" class="flex items-center gap-2">
                        <i class="fa-solid fa-window-restore"></i>
                        {{ $page->name ?? 'Select Page' }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-gray-500"></i>
                </button>

                <!-- Dropdown options -->
                <div id="pageDropdown"
                    class="hidden absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded overflow-hidden shadow-lg">
                    @foreach($pages as $p)
                    <div class="dropdown-option flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-gray-100"
                        data-value="{{ $p->id }}"
                        onclick="document.getElementById('pageSelectForm').submit();">
                        <i class="fa-solid fa-window-restore"></i>
                        {{ $p->name }}
                    </div>
                    @endforeach
                </div>

                <!-- Hidden input to submit -->
                <input type="hidden" name="page_id" id="pageInput" value="{{ $page->id }}">
            </div>
        </form>
    </div>
    <div class="editor-navbar-right flex items-center gap-2 lg:gap-4">
        <div class="view-toggle md:flex items-center gap-2 border-rounded bg-secondary p-1 hidden">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="desktop" class="hidden peer" checked>
                <span class="radio-label btn border-rounded p-1.5 pb-1 peer-checked:text-white peer-checked:bg-black!"><i class="text-xl fas fa-desktop"></i></span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="mobile" class="hidden peer">
                <span class="radio-label btn border-rounded p-1.5 pb-1 peer-checked:text-white peer-checked:bg-black!"><i class="text-xl fas fa-mobile-alt"></i></span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="full-view" class="hidden peer">
                <span class="radio-label btn border-rounded p-1.5 pb-1 peer-checked:text-white peer-checked:bg-black!"><i class="text-xl fas fa-expand"></i></span>
            </label>
        </div>
        <button onclick="submitCustomizesForm()" class="btn bg-invert text-invert px-3 py-2 font-bold border-rounded">Save</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('pageSelectBtn');
        const dropdown = document.getElementById('pageDropdown');
        const selected = document.getElementById('selectedPage');
        const hiddenInput = document.getElementById('pageInput');
        const form = document.getElementById('pageSelectForm');

        // Toggle dropdown visibility
        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Handle option click
        document.querySelectorAll('.dropdown-option').forEach(option => {
            option.addEventListener('click', () => {
                // Update button text
                selected.innerHTML = option.innerHTML;

                // Update hidden input value
                hiddenInput.value = option.getAttribute('data-value');

                // Close dropdown
                dropdown.classList.add('hidden');

                // Submit form automatically
                form.submit();
            });
        });

        // Close dropdown if clicking outside
        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="view-mode"]');
        const preview = document.getElementById('livePreviewContent');
        const editorSidebar = document.getElementById('editorSidebar');
        const previeweSection = document.getElementById('previeweSection');

        if (!preview) return;

        // 🔹 Load saved mode or default to desktop
        const savedMode = localStorage.getItem('view-mode') || 'desktop';
        applyViewMode(savedMode);

        // Set the corresponding radio button checked
        radios.forEach(radio => {
            if (radio.value === savedMode) {
                radio.checked = true;
            }

            radio.addEventListener('change', e => {
                const mode = e.target.value;
                localStorage.setItem('view-mode', mode); // 💾 Save selected mode
                applyViewMode(mode);
            });
        });

        function applyViewMode(mode) {
            // saare responsive classes remove karo
            preview.classList.remove('w-full', 'md:w-[420px]', 'max-w-none', 'mx-auto', 'border');
            editorSidebar.classList.remove('hidden');
            previeweSection.classList.add('ml-[300px]');

            if (mode === 'desktop') {
                // 💻 Desktop view
                preview.classList.add('w-full');
            } else if (mode === 'mobile') {
                // 📱 Mobile view
                preview.classList.add('md:w-[420px]', 'mx-auto', 'border', 'border-primary');
            } else if (mode === 'full-view') {
                // 🖥️ Full width
                preview.classList.add('w-full', 'max-w-none');
                editorSidebar.classList.add('hidden');
                previeweSection.classList.remove('ml-[300px]');
            }
        }
    });
</script>