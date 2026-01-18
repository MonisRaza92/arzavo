<div id="contentPickerModal"
    class="fixed inset-0 bg-black/70 hidden z-199 flex justify-center items-start pt-20">

    <div class="bg-primary border-primary border-rounded w-11/12 md:w-3/4 lg:w-1/2 max-h-[80vh] overflow-y-auto scrollbar">

        {{-- Header --}}
        <div class="flex justify-between items-center z-200 p-4 border-bottom sticky top-0 bg-primary">
            <h3 class="text-lg font-bold text-primary" id="contentPickerTitle">
                <i class="fa-solid fa-folder-open"></i> <span>Select Content</span>
            </h3>
            <button onclick="closeContentPicker()" class="text-xl text-primary">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Content Grid --}}
        <div class="p-4 columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" id="contentGrid">
            @foreach($contents as $content)
            <div
                class="content-item group border-primary border-rounded overflow-hidden cursor-pointer bg-primary hover-primary transition"
                data-type="{{ $content->type }}"
                onclick="selectContent('{{ $content->filepath }}', '{{ $content->type }}')">

                {{-- Preview --}}
                <div class="relative bg-secondary overflow-hidden" title="{{ $content->filename }}">

                    @if($content->type === 'image')
                    <img src="{{ media($content->filepath) }}"
                        class="w-full h-auto object-contain transition group-hover:scale-105">

                    @elseif($content->type === 'video')
                    <video
                        src="{{ media($content->filepath) }}"
                        muted
                        preload="metadata"
                        class="w-full h-auto object-cover"
                        onmouseenter="this.play()"
                        onmouseleave="this.pause(); this.currentTime = 0;">
                    </video>

                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-tertiary">
                        <i class="fa-solid fa-file text-2xl mb-1"></i>
                        <span class="text-xs uppercase">{{ $content->type }}</span>
                    </div>
                    @endif

                    {{-- Type Badge --}}
                    <!-- <span class="absolute top-1 left-1 text-[10px] uppercase px-2 py-0.5 rounded-full
                    bg-invert text-invert">
                        {{ $content->type }}
                    </span> -->
                </div>

                {{-- Meta --}}
                <p class="text-xs font-medium text-primary truncate p-2" title="{{ $content->filename }}">
                    {{ $content->filename }}
                </p>
            </div>
            @endforeach
        </div>
        {{-- Footer --}}
        <div class="sticky bottom-0 bg-primary border-top p-4 flex justify-between gap-3">

            {{-- Manage Content --}}
            <a href="{{ route('admin.contents.index') }}"
                class="px-4 py-2 text-sm border-rounded bg-hover-tertiary border-primary transition text-primary flex items-center gap-2">
                <i class="fa-solid fa-gears"></i>
                Manage Content
            </a>

            <div class="flex gap-2">
                <button class="px-4 py-2 text-sm border-rounded border-primary bg-hover-tertiary" onclick="closeContentPicker()">Close</button>
                {{-- Upload New --}}
                <button type="button"
                    id="uploadNewBtn"
                    onclick="openUploadPicker()"
                    class="px-4 py-2 text-sm border-rounded bg-invert text-invert hover:opacity-80 transition flex items-center gap-2">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload New</span>
                </button>
            </div>

        </div>
    </div>
</div>
<form id="quickUploadForm"
    action="{{ route('admin.contents.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="hidden">

    @csrf

    <input type="hidden" name="type" id="quickUploadType">
    <input type="file" name="file" id="quickUploadFile">

</form>
<script>
    window.currentTargetInput ??= null;
    window.currentAllowedType ??= null;
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
        const placeholder = wrapper.querySelector('[data-content-placeholder]');

        if (!preview) return;

        placeholder?.classList.add('hidden');
        preview.classList.remove('hidden');

        if (type === 'image') {
            preview.src = mediaUrl(path);
        } else if (type === 'video') {
            preview.src = mediaUrl(path);
        } else {
            preview.innerHTML = `
            <i class="fa-solid fa-circle-check text-3xl mb-1"></i>
            <span class="text-xs">${type.toUpperCase()} selected</span>
        `;
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
            'content-item group border-primary border-rounded overflow-hidden cursor-pointer bg-primary hover-primary transition';

        div.dataset.type = content.type;

        div.onclick = () => {
            selectContent(content.filepath, content.type);
        };

        let previewHTML = '';

        if (content.type === 'image') {
            previewHTML = `<img src="${mediaUrl(content.filepath)}" class="w-full h-auto object-contain">`;
        } else if (content.type === 'video') {
            previewHTML = `
        <video src="${mediaUrl(content.filepath)}"
            muted preload="metadata"
            class="w-full h-auto object-cover"></video>`;
        } else {
            previewHTML = `
        <div class="w-full h-full flex flex-col items-center justify-center text-tertiary py-16">
            <i class="fa-solid fa-file text-2xl mb-1"></i>
            <span class="text-xs uppercase">${content.type}</span>
        </div>`;
        }

        div.innerHTML = `
        <div class="relative bg-secondary overflow-hidden">
            ${previewHTML}
            <span class="absolute top-1 left-1 text-[10px] uppercase px-2 py-0.5 rounded-full bg-invert text-invert">
                ${content.type}
            </span>
        </div>
        <p class="text-xs font-medium text-primary truncate p-2">
            ${content.filename}
        </p>
    `;

        // 🔥 add to top
        grid.append(div);
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
