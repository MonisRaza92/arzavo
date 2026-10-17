{{-- Upload Content Modal (Popup Exception: shadow-2xl, max rounded-lg) --}}
<div id="contentAddPopup"
     class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">

    <div class="rounded-lg bg-primary w-full max-w-lg shadow-2xl overflow-hidden flex flex-col my-auto transition"
         onclick="event.stopPropagation()">

        {{-- Modal Header --}}
        <div class="p-5 flex items-center justify-between border-b border-subtle/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-theme-text" id="contentPickerTitle">
                        Upload Media Asset
                    </h3>
                    <p class="text-xs text-theme-text-muted mt-0.5">
                        Store videos, PDFs, and media safely in tenant cloud storage
                    </p>
                </div>
            </div>

            <button type="button"
                    onclick="closeUploadModal()"
                    class="w-8 h-8 rounded-full bg-secondary text-theme-text-muted hover:text-theme-text flex items-center justify-center text-xs transition cursor-pointer"
                    title="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <form id="contentAddForm"
              action="{{ route('admin.contents.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="p-5 space-y-4">

                {{-- CONTENT TYPE SELECTOR PILLS --}}
                <div>
                    <label class="block text-xs text-theme-text-muted font-normal mb-2">
                        Select Content Type <span class="text-brand">*</span>
                    </label>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button"
                                onclick="selectContentType('video', this)"
                                class="content-type-btn px-3.5 py-2.5 rounded-full text-xs font-normal transition inline-flex items-center justify-center gap-2 bg-brand text-primary">
                            <i class="fa-solid fa-video text-[11px]"></i>
                            <span>Video</span>
                        </button>

                        <button type="button"
                                onclick="selectContentType('pdf', this)"
                                class="content-type-btn px-3.5 py-2.5 rounded-full text-xs font-normal transition inline-flex items-center justify-center gap-2 bg-secondary text-theme-text hover:bg-subtle">
                            <i class="fa-solid fa-file-pdf text-[11px]"></i>
                            <span>PDF</span>
                        </button>

                        <button type="button"
                                onclick="selectContentType('image', this)"
                                class="content-type-btn px-3.5 py-2.5 rounded-full text-xs font-normal transition inline-flex items-center justify-center gap-2 bg-secondary text-theme-text hover:bg-subtle">
                            <i class="fa-solid fa-image text-[11px]"></i>
                            <span>Image</span>
                        </button>

                        <button type="button"
                                onclick="selectContentType('audio', this)"
                                class="content-type-btn px-3.5 py-2.5 rounded-full text-xs font-normal transition inline-flex items-center justify-center gap-2 bg-secondary text-theme-text hover:bg-subtle">
                            <i class="fa-solid fa-music text-[11px]"></i>
                            <span>Audio</span>
                        </button>
                    </div>

                    <input type="hidden" name="type" id="contentTypeInput" value="video">
                </div>

                {{-- CUSTOM FILE DISPLAY NAME --}}
                <div>
                    <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                        Display Title / Name <span class="text-[11px] text-theme-text-muted">(Optional)</span>
                    </label>

                    <input type="text"
                           name="filename"
                           id="contentFilenameInput"
                           placeholder="e.g. Calculus Chapter 1 Overview"
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none">
                </div>

                {{-- DRAG & DROP FILE ZONE --}}
                <div>
                    <label class="block text-xs text-theme-text-muted font-normal mb-1.5">
                        Choose Media File <span class="text-brand">*</span>
                    </label>

                    <div id="dropzoneBox"
                         onclick="document.getElementById('contentFileInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)"
                         class="rounded-lg bg-secondary p-6 text-center cursor-pointer border-2 border-dashed border-subtle/80 hover:border-brand/60 transition group">

                        {{-- Empty State Placeholder --}}
                        <div id="contentUploadPlaceholder" class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg mb-2.5 group-hover:scale-105 transition">
                                <i id="uploadPlaceholderIcon" class="fa-solid fa-video"></i>
                            </div>
                            <span class="text-xs font-normal text-theme-text" id="uploadPlaceholderText">
                                Click or drag video file here to upload
                            </span>
                            <span class="text-[11px] text-theme-text-muted mt-1" id="uploadSupportedText">
                                Supports MP4, WebM, OGG up to 100MB
                            </span>
                        </div>

                        {{-- File Selected State --}}
                        <div id="contentUploadSelected" class="hidden flex items-center justify-between p-3 rounded-lg bg-primary text-xs">
                            <div class="flex items-center gap-2.5 truncate">
                                <div class="w-8 h-8 rounded-full bg-success-bg text-success flex items-center justify-center text-xs shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="text-left truncate">
                                    <p class="font-normal text-theme-text truncate" id="selectedFileName">filename.ext</p>
                                    <p class="text-[10px] text-theme-text-muted" id="selectedFileSize">0 MB</p>
                                </div>
                            </div>
                            <button type="button"
                                    onclick="clearSelectedFile(event)"
                                    class="w-6 h-6 rounded-full bg-secondary text-theme-text-muted hover:text-danger flex items-center justify-center text-xs shrink-0 transition"
                                    title="Remove selected file">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <input type="file"
                           name="file"
                           id="contentFileInput"
                           required
                           accept="video/mp4,video/webm,video/ogg"
                           class="hidden"
                           onchange="handleFileSelected(this)">
                </div>

                {{-- Upload Progress Feedback --}}
                <div id="uploadProgressContainer" class="hidden space-y-1.5">
                    <div class="flex justify-between items-center text-[11px] text-theme-text-muted">
                        <span id="uploadStatusText">Uploading file...</span>
                        <span id="uploadPercentText">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-secondary rounded-full overflow-hidden">
                        <div id="uploadProgressBar" class="h-full bg-brand rounded-full transition-all duration-200" style="width: 0%"></div>
                    </div>
                </div>

                {{-- Error Alert Box --}}
                <div id="uploadErrorAlert" class="hidden p-3 rounded-lg bg-danger-bg text-danger text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                    <span id="uploadErrorMessage">An error occurred while uploading.</span>
                </div>

            </div>

            {{-- Modal Actions --}}
            <div class="p-5 flex items-center justify-end gap-3 border-t border-subtle/50">
                <button type="button"
                        onclick="closeUploadModal()"
                        class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition cursor-pointer">
                    Cancel
                </button>

                <button type="submit"
                        id="submitUploadBtn"
                        class="px-6 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs disabled:opacity-50">
                    <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                    <span id="submitBtnText">Upload File</span>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const typeConfigs = {
        video: {
            icon: 'fa-video',
            accept: 'video/mp4,video/webm,video/ogg',
            label: 'video file',
            supported: 'Supports MP4, WebM, OGG'
        },
        pdf: {
            icon: 'fa-file-pdf',
            accept: 'application/pdf',
            label: 'PDF document or notes',
            supported: 'Supports PDF files'
        },
        image: {
            icon: 'fa-image',
            accept: 'image/jpeg,image/png,image/webp',
            label: 'image file',
            supported: 'Supports JPG, PNG, WEBP'
        },
        audio: {
            icon: 'fa-music',
            accept: 'audio/mpeg,audio/mp3,audio/wav,audio/ogg',
            label: 'audio recording',
            supported: 'Supports MP3, WAV, OGG'
        }
    };

    function openUploadModal() {
        const popup = document.getElementById('contentAddPopup');
        if (popup) {
            popup.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeUploadModal() {
        const popup = document.getElementById('contentAddPopup');
        if (popup) {
            popup.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        resetUploadForm();
    }

    function selectContentType(type, btn) {
        document.querySelectorAll('.content-type-btn').forEach(b => {
            b.classList.remove('bg-brand', 'text-primary');
            b.classList.add('bg-secondary', 'text-theme-text', 'hover:bg-subtle');
        });

        btn.classList.remove('bg-secondary', 'text-theme-text', 'hover:bg-subtle');
        btn.classList.add('bg-brand', 'text-primary');

        document.getElementById('contentTypeInput').value = type;

        const config = typeConfigs[type] || typeConfigs.video;
        const fileInput = document.getElementById('contentFileInput');
        fileInput.accept = config.accept;

        const icon = document.getElementById('uploadPlaceholderIcon');
        if (icon) {
            icon.className = 'fa-solid ' + config.icon;
        }

        const label = document.getElementById('uploadPlaceholderText');
        if (label) {
            label.innerText = 'Click or drag ' + config.label + ' here to upload';
        }

        const supported = document.getElementById('uploadSupportedText');
        if (supported) {
            supported.innerText = config.supported;
        }

        clearSelectedFile();
    }

    function handleFileSelected(input) {
        if (!input.files || !input.files.length) return;
        const file = input.files[0];

        document.getElementById('contentUploadPlaceholder')?.classList.add('hidden');
        const selectedBox = document.getElementById('contentUploadSelected');
        if (selectedBox) selectedBox.classList.remove('hidden');

        document.getElementById('selectedFileName').innerText = file.name;
        document.getElementById('selectedFileSize').innerText = formatBytesToHuman(file.size);

        // Autofill title if empty
        const titleInput = document.getElementById('contentFilenameInput');
        if (titleInput && !titleInput.value.trim()) {
            const rawName = file.name.replace(/\.[^/.]+$/, "");
            titleInput.value = rawName.replace(/[-_]/g, ' ');
        }

        hideUploadError();
    }

    function clearSelectedFile(e) {
        if (e) {
            e.stopPropagation();
        }
        const fileInput = document.getElementById('contentFileInput');
        if (fileInput) fileInput.value = '';

        document.getElementById('contentUploadSelected')?.classList.add('hidden');
        document.getElementById('contentUploadPlaceholder')?.classList.remove('hidden');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneBox')?.classList.add('border-brand', 'bg-subtle');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneBox')?.classList.remove('border-brand', 'bg-subtle');
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzoneBox')?.classList.remove('border-brand', 'bg-subtle');

        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
            const fileInput = document.getElementById('contentFileInput');
            fileInput.files = e.dataTransfer.files;
            handleFileSelected(fileInput);
        }
    }

    function formatBytesToHuman(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showUploadError(msg) {
        const alertBox = document.getElementById('uploadErrorAlert');
        const msgEl = document.getElementById('uploadErrorMessage');
        if (alertBox && msgEl) {
            msgEl.innerText = msg;
            alertBox.classList.remove('hidden');
        }
    }

    function hideUploadError() {
        document.getElementById('uploadErrorAlert')?.classList.add('hidden');
    }

    function resetUploadForm() {
        const form = document.getElementById('contentAddForm');
        if (form) form.reset();
        clearSelectedFile();
        hideUploadError();

        const progressContainer = document.getElementById('uploadProgressContainer');
        if (progressContainer) progressContainer.classList.add('hidden');

        const progressBar = document.getElementById('uploadProgressBar');
        if (progressBar) progressBar.style.width = '0%';

        const submitBtn = document.getElementById('submitUploadBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            document.getElementById('submitBtnText').innerText = 'Upload File';
        }
    }

    // Modal click backdrop to close
    document.getElementById('contentAddPopup')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeUploadModal();
        }
    });

    // Form submission with XMLHttpRequest for live progress bar
    document.addEventListener('DOMContentLoaded', () => {
        initUploadFormHandler();
    });
    document.addEventListener('turbo:load', () => {
        initUploadFormHandler();
    });

    function initUploadFormHandler() {
        const form = document.getElementById('contentAddForm');
        if (!form || form.dataset.initialized) return;
        form.dataset.initialized = 'true';

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            hideUploadError();

            const fileInput = document.getElementById('contentFileInput');
            if (!fileInput.files || !fileInput.files.length) {
                showUploadError('Please select a file to upload.');
                return;
            }

            const submitBtn = document.getElementById('submitUploadBtn');
            const submitText = document.getElementById('submitBtnText');
            const progressContainer = document.getElementById('uploadProgressContainer');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressText = document.getElementById('uploadPercentText');

            submitBtn.disabled = true;
            submitText.innerText = 'Uploading...';
            progressContainer.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.innerText = '0%';

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressText.innerText = percent + '%';
                }
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    submitBtn.disabled = false;
                    submitText.innerText = 'Upload File';

                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.success && res.data) {
                                // Dispatch custom event for Alpine workspace
                                window.dispatchEvent(new CustomEvent('content-uploaded', {
                                    detail: res.data
                                }));

                                closeUploadModal();
                            } else {
                                showUploadError(res.message || 'Upload failed. Please check the file.');
                            }
                        } catch (err) {
                            showUploadError('Invalid response from server.');
                        }
                    } else {
                        try {
                            const errRes = JSON.parse(xhr.responseText);
                            showUploadError(errRes.message || 'File upload failed. Ensure the format is valid.');
                        } catch (err) {
                            showUploadError('Server returned error ' + xhr.status + '.');
                        }
                    }
                }
            };

            xhr.send(formData);
        });
    }
</script>