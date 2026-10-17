<div id="contentPickerModal" class="fixed inset-0 bg-black/75 hidden z-[200] flex justify-center items-start pt-16 px-4">

    <div
        class="bg-primary rounded-lg shadow-2xl w-full max-w-3xl max-h-[85vh] overflow-y-auto scrollbar relative"
        onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="flex justify-between items-center z-10 p-4 border-b border-subtle sticky top-0 bg-primary">
            <h3 class="text-sm font-semibold text-theme-text flex items-center gap-2" id="contentPickerTitle">
                <i class="fa-solid fa-folder-open text-brand"></i> <span>Select Content</span>
            </h3>
            <button onclick="closeContentPicker()" class="w-7 h-7 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Content Grid: Natural Real Dimensions with Auto Adjust --}}
        <div class="p-4 columns-2 sm:columns-3 md:columns-4 gap-3 space-y-3" id="contentGrid">
            @foreach ($contents as $content)
                <div class="content-item group break-inside-avoid rounded-lg overflow-hidden cursor-pointer bg-secondary hover:ring-2 hover:ring-brand transition mb-3"
                    data-type="{{ $content->type }}"
                    onclick="selectContent('{{ $content->filepath }}', '{{ $content->type }}')">

                    {{-- Preview --}}
                    <div class="relative bg-subtle overflow-hidden flex items-center justify-center" title="{{ $content->filename }}">

                        @if ($content->type === 'image')
                            <img src="{{ media($content->filepath) }}"
                                class="w-full h-auto object-contain transition group-hover:scale-105">
                        @elseif($content->type === 'video')
                            <video src="{{ media($content->filepath) }}" muted preload="metadata"
                                class="w-full h-auto object-cover" onmouseenter="this.play()"
                                onmouseleave="this.pause(); this.currentTime = 0;">
                            </video>
                        @else
                            <div class="w-full h-24 flex flex-col items-center justify-center text-theme-text-muted">
                                <i class="fa-solid fa-file text-2xl mb-1"></i>
                                <span class="text-xs uppercase">{{ $content->type }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Meta --}}
                    <p class="text-[11px] font-normal text-theme-text truncate p-2" title="{{ $content->filename }}">
                        {{ $content->filename }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="sticky bottom-0 bg-primary border-t border-subtle p-3 flex justify-between items-center gap-3">

            {{-- Manage Content --}}
            <a href="{{ route('admin.contents.index') }}"
                class="px-3 py-1.5 rounded-full text-xs bg-secondary text-theme-text hover:bg-subtle transition flex items-center gap-1.5 font-normal">
                <i class="fa-solid fa-gears text-[11px]"></i>
                Manage Content
            </a>

            <div class="flex items-center gap-2">
                <button type="button" class="px-4 py-2 rounded-full text-xs bg-secondary text-theme-text hover:bg-subtle transition font-normal"
                    onclick="closeContentPicker()">Close</button>
                {{-- Upload New --}}
                <button type="button" id="uploadNewBtn" onclick="openUploadPicker()"
                    class="px-4 py-2 rounded-full text-xs bg-brand text-primary hover:opacity-90 transition flex items-center gap-1.5 font-normal shadow-sm">
                    <i class="fa-solid fa-upload text-[11px]"></i>
                    <span>Upload New</span>
                </button>
            </div>

        </div>
    </div>
</div>
<form id="quickUploadForm" action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data"
    class="hidden">

    @csrf

    <input type="hidden" name="type" id="quickUploadType">
    <input type="file" name="file" id="quickUploadFile">

</form>
<script>
    window.currentTargetInput ??= null;
    window.currentAllowedType ??= null;
    window.isContentPickerActive = function() {
        const modal = document.getElementById('contentPickerModal');
        return modal && !modal.classList.contains('hidden');
    };
    window.mediaUrl = function(path) {
        if (!path) return '';
        return "{{ rtrim(Storage::url(''), '/') }}/" + path.replace(/^\/+/, '');
    };

    /* -----------------------------
       OPEN MODAL
    --------------------------------*/

    function openContentPicker(inputId, allowedType) {
        currentTargetInput = inputId;
        currentAllowedType = allowedType; // image | video | audio | pdf

        updateContentPickerTitle(allowedType);

        document.getElementById('contentPickerModal').classList.remove('hidden');

        filterByType(allowedType);
    }

    /* -----------------------------
       CLOSE MODAL
    --------------------------------*/
    function closeContentPicker() {
        currentTargetInput = null;
        updateContentPickerTitle(null);
        document.getElementById('contentPickerModal').classList.add('hidden');
    }

    function updateContentPickerTitle(type) {
        const titleEl = document.querySelector('#contentPickerTitle span');
        if (!titleEl) return;

        if (!type) {
            titleEl.innerText = 'Select Content';
            return;
        }

        // Capitalize first letter
        const label = type.charAt(0).toUpperCase() + type.slice(1);

        titleEl.innerText = `Select ${label}`;
    }


    /* -----------------------------
       SELECT CONTENT
    --------------------------------*/
    function selectContent(path, type) {

        if (!currentTargetInput) return;

        const input = document.getElementById(currentTargetInput);
        if (!input) return;

        // 1️⃣ set filepath
        input.value = path;

        // 2️⃣ auto preview handling
        updatePreview(input, path, type);
        input.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        input.dispatchEvent(new Event('change', {
            bubbles: true
        }));

        closeContentPicker();

        // 3️⃣ 🔥 AUTO SUBMIT IF CUSTOMIZE FORM EXISTS

        if (typeof submitCustomizesForm === 'function') {
            const form = document.getElementById('customize-form');
            // small delay so DOM fully update ho jaye
            clearTimeout(customizeSubmitTimeout)
            customizeSubmitTimeout = setTimeout(() => {
                submitCustomizesForm(form);
            }, 200);
        }
        if (window.currentOpenSectionId !== null && typeof submitSectionForm === 'function') {

            const id = window.currentOpenSectionId;
            const form = document.querySelector('#edit-form-' + id + ' .editSectionForm');

            if (!form) return;

            // small delay so DOM update ho jaye
            setTimeout(() => {
                submitSectionForm(form);
            }, 200);
        }

        if (window.currentOpenBlockId !== null && typeof submitBlockForm === 'function') {

            const id = window.currentOpenBlockId;
            const form = document.querySelector('#edit-block-form-' + id + ' .editBlockForm');

            if (!form) return;

            // small delay so DOM update ho jaye
            setTimeout(() => {
                window.submitBlockForm(form);
            }, 200);
        }

    }

    /* -----------------------------
       PREVIEW HANDLER (GENERIC)
    --------------------------------*/
    function updatePreview(input, path, type) {

        const wrapper = input.closest('[data-content-wrapper]');
        if (!wrapper) return;

        const preview = wrapper.querySelector('[data-content-preview]');
        const filePreview = wrapper.querySelector('[data-content-file-preview]');
        const fileNameEl = wrapper.querySelector('[data-content-filename]');
        const placeholder = wrapper.querySelector('[data-content-placeholder]');

        placeholder?.classList.add('hidden');

        if (type === 'image') {
            if (preview) {
                preview.src = mediaUrl(path);
                preview.classList.remove('hidden');
            }
        } else {
            if (preview) preview.classList.add('hidden');
            if (filePreview) {
                filePreview.classList.remove('hidden');
                filePreview.classList.add('flex');
            }
            if (fileNameEl && path) {
                const parts = path.split('/');
                fileNameEl.textContent = parts[parts.length - 1];
            }
        }
    }

    /* -----------------------------
       FILTER
    --------------------------------*/
    function filterByType(type) {
        document.querySelectorAll('.content-item').forEach(el => {
            if (el.dataset.type === type) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    function openUploadPicker() {

        if (!currentAllowedType) return;

        const fileInput = document.getElementById('quickUploadFile');
        const typeInput = document.getElementById('quickUploadType');
        const uploadBtn = document.getElementById('uploadNewBtn');
        const uploadBtnText = uploadBtn.querySelector('span');

        typeInput.value = currentAllowedType;
        fileInput.value = '';

        // 🔥 IMPORTANT: accept attribute set karo
        const acceptMap = {
            image: 'image/*',
            video: 'video/*',
            audio: 'audio/*',
            pdf: 'application/pdf'
        };

        fileInput.setAttribute('accept', acceptMap[currentAllowedType] || '*');

        fileInput.click();

        fileInput.onchange = function() {

            if (!fileInput.files.length) return;

            const file = fileInput.files[0];

            // 🔴 Safety check (extra guard)
            if (!isValidFileType(file, currentAllowedType)) {
                alert('Invalid file type selected');
                fileInput.value = '';
                return;
            }

            // UI: uploading state
            uploadBtnText.innerText = 'Uploading…';
            uploadBtn.disabled = true;
            uploadBtn.classList.add('opacity-70', 'cursor-not-allowed');

            const form = document.getElementById('quickUploadForm');
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {

                    if (!res.success) throw res;

                    appendNewContent(res.data);
                    selectContent(res.data.filepath, res.data.type);

                    uploadBtnText.innerText = 'Upload New';
                    uploadBtn.disabled = false;
                    uploadBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                })
                .catch(err => {
                    alert(err.message || 'Upload failed');
                    uploadBtnText.innerText = 'Upload New';
                    uploadBtn.disabled = false;
                    uploadBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                });
        };
    }


    function appendNewContent(content) {

        // filter respect karo
        if (content.type !== currentAllowedType) return;

        const grid = document.getElementById('contentGrid');
        if (!grid) return;

        const div = document.createElement('div');
        div.className =
            'content-item group break-inside-avoid rounded-lg overflow-hidden cursor-pointer bg-secondary hover:ring-2 hover:ring-brand transition mb-3';

        div.dataset.type = content.type;

        div.onclick = () => {
            selectContent(content.filepath, content.type);
        };

        let previewHTML = '';

        if (content.type === 'image') {
            previewHTML = `<img src="${mediaUrl(content.filepath)}" class="w-full h-auto object-contain transition group-hover:scale-105">`;
        } else if (content.type === 'video') {
            previewHTML = `
        <video src="${mediaUrl(content.filepath)}"
            muted preload="metadata"
            class="w-full h-auto object-cover" onmouseenter="this.play()"
            onmouseleave="this.pause(); this.currentTime = 0;"></video>`;
        } else {
            previewHTML = `
        <div class="w-full h-24 flex flex-col items-center justify-center text-theme-text-muted">
            <i class="fa-solid fa-file text-2xl mb-1"></i>
            <span class="text-xs uppercase">${content.type}</span>
        </div>`;
        }

        div.innerHTML = `
        <div class="relative bg-subtle overflow-hidden flex items-center justify-center" title="${content.filename}">
            ${previewHTML}
        </div>
        <p class="text-[11px] font-normal text-theme-text truncate p-2" title="${content.filename}">
            ${content.filename}
        </p>
    `;

        // 🔥 add to top
        grid.prepend(div);
    }

    function isValidFileType(file, type) {
        const mime = file.type;

        if (type === 'image') return mime.startsWith('image/');
        if (type === 'video') return mime.startsWith('video/');
        if (type === 'audio') return mime.startsWith('audio/');
        if (type === 'pdf') return mime === 'application/pdf';

        return false;
    }
</script>
