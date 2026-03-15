<div
    class="editor-navbar flex w-full h-16 justify-between items-center py-3 bg-primary px-4 border-bottom sticky top-0 left-0 z-2">
    <div class="editor-navbar-left flex items-center gap-4">
        <a href="{{ route('admin.themes.index') }}" title="Back to themes" class="text-xl"><i
                class="fa-solid fa-right-from-bracket rotate-180"></i></a>
        <h3 class="text-primary font-semibold text-xl capitalize hidden md:block">{{ $theme->theme_slug }}</h3>
        @if($theme->status === 'published')
            <div class="badge bg-green-100 hidden md:block px-2 py-1 text-xs rounded-full"><i
                    class="fas fa-circle text-green-500"></i> Live</div>
        @else
            <div class="badge bg-yellow-100 hidden md:block px-2 py-1 text-xs rounded-full"><i
                    class="fas fa-circle text-yellow-500"></i> Draft</div>
        @endif
    </div>
    <div class="editor-navbar-center flex grow items-center justify-center gap-4">
        <form action="{{ route('admin.builder.index', ['theme' => $theme->theme_slug]) }}" method="GET"
            id="pageSelectForm">
            <div class="relative inline-block lg:w-92 w-full">
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
                            data-value="{{ $p->slug }}">
                            <i class="fa-solid fa-window-maximize"></i>
                            {{ $p->name }}
                        </div>
                    @endforeach
                </div>

                <!-- Hidden input to submit -->
                <input type="hidden" name="page" id="pageInput" value="{{ $page->slug }}">
            </div>
        </form>
    </div>
    <div class="editor-navbar-right flex items-center gap-2">
        <!-- //preview btn new tab -->
        <a title="See live preview"
            href="{{ route('website.preview', ['theme' => $theme->theme_slug, 'slug' => $page->slug, 'theme_id' => $theme->id]) }}"
            target="_blank" class="btn bg-tertiary shadow-inner text-lg p-1.5 pl-2.25 font-semibold border-rounded"><i
                class="fa-solid fa-arrow-right hover:scale-110 transition-all duration-200 -rotate-45 mr-1"></i></a>

        <div
            class="view-toggle md:flex items-center border-rounded bg-tertiary hidden overflow-hidden border-primary shadow-inner">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="desktop" class="hidden peer" checked>
                <span
                    class="radio-label btn border-rounded py-2 px-2.25 peer-checked:text-black peer-checked:shadow peer-checked:bg-white!"><i class="fa-solid fa-desktop text-lg"></i></span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="mobile" class="hidden peer">
                <span
                    class="radio-label btn border-rounded py-2 px-2.5 peer-checked:text-black peer-checked:shadow peer-checked:bg-white!"><i class="fa-solid fa-mobile-screen-button text-lg"></i></span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="view-mode" value="full-view" class="hidden peer">
                <span
                    class="radio-label btn border-rounded py-2 px-2.25 peer-checked:text-black peer-checked:shadow peer-checked:bg-white!"><i class="fa-solid fa-expand text-lg"></i></span>
            </label>
        </div>
        <button id="saveBtn" class="btn bg-invert text-invert px-3 py-2 font-bold border-rounded">Save</button>

        <script>
            document.getElementById('saveBtn').addEventListener('click', function () {
                const btn = this;
                const originalText = btn.innerHTML;

                // Change to "Saving"
                btn.innerHTML = 'Saving...';
                btn.disabled = true;

                // After 1 second, change to "Saved"
                setTimeout(() => {
                    btn.innerHTML = 'Saved';
                    btn.style.backgroundColor = '#444444'; // Darker color for saved state
                }, 2000);

                // After 5 more seconds (6 total), change back to original
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.style.backgroundColor = ''; // Reset to original color
                }, 6000);
            });
        </script>
    </div>
</div>

<script>
    function initPageDropdown() {
        const btn = document.getElementById('pageSelectBtn');
        const dropdown = document.getElementById('pageDropdown');
        const selected = document.getElementById('selectedPage');
        const hiddenInput = document.getElementById('pageInput');
        const form = document.getElementById('pageSelectForm');

        if (!btn || !dropdown || !selected || !hiddenInput || !form) {
            return;
        }

        // 🔒 prevent double-binding
        if (btn.dataset.bound === 'true') return;
        btn.dataset.bound = 'true';

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        dropdown.querySelectorAll('.dropdown-option').forEach(option => {
            option.addEventListener('click', function (e) {
                e.stopPropagation();

                selected.innerHTML = option.innerHTML;
                hiddenInput.value = option.dataset.value;

                dropdown.classList.add('hidden');
                form.submit();
            });
        });

        document.addEventListener('click', function (e) {
            if (
                !btn.contains(e.target) &&
                !dropdown.contains(e.target)
            ) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // 🚀 Turbo lifecycle (THIS is the fix)
    document.addEventListener('turbo:load', () => {
        requestAnimationFrame(initPageDropdown);
    });

    document.addEventListener('turbo:render', () => {
        requestAnimationFrame(initPageDropdown);
    });


    document.addEventListener('turbo:load', function () {
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
            previeweSection.classList.add('ml-90');

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
                previeweSection.classList.remove('ml-90');
            }
        }
    });
</script>