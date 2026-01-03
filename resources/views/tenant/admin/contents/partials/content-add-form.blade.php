<div id="contentAddPopup" class="hidden fixed inset-0 z-100 bg-black/80 flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        {{-- Header --}}
        <div class="flex justify-between items-center z-101 p-4 border-bottom sticky top-0 bg-primary">
            <h3 class="text-lg font-bold text-primary" id="contentPickerTitle">
                <i class="fa-solid fa-folder-open"></i> <span>Upload Content</span>
            </h3>
            <button onclick="document.getElementById('contentAddPopup').classList.add('hidden')" class="text-xl text-primary">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="contentAddForm" action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="p-4 space-y-4">

                {{-- CONTENT TYPE --}}
                <div>
                    <label class="block text-tertiary text-xs mb-2">
                        Content Type <span class="text-accent">*</span>
                    </label>

                    <div class="grid grid-cols-2 gap-2">

                        <button type="button"
                            onclick="selectContentType('video', this)"
                            class="px-4 py-3 text-sm font-medium border-primary border-rounded
                                   bg-invert text-invert">
                            <i class="fa-solid fa-video"></i>
                            Video
                        </button>

                        <button type="button"
                            onclick="selectContentType('pdf', this)"
                            class="px-4 py-3 text-sm font-medium border-primary border-rounded
                                   bg-primary text-primary">
                            <i class="fa-solid fa-file-pdf"></i>
                            Book / Notes
                        </button>

                        <button type="button"
                            onclick="selectContentType('image', this)"
                            class="px-4 py-3 text-sm font-medium border-primary border-rounded
                                   bg-primary text-primary">
                            <i class="fa-solid fa-image"></i>
                            Image
                        </button>

                        <button type="button"
                            onclick="selectContentType('audio', this)"
                            class="px-4 py-3 text-sm font-medium border-primary border-rounded
                                   bg-primary text-primary">
                            <i class="fa-solid fa-music"></i>
                            Audio
                        </button>

                    </div>

                    <input type="hidden" name="type" id="contentTypeInput" value="video">
                </div>

                {{-- CUSTOM FILE NAME --}}
                <div>
                    <label class="block text-tertiary text-xs mb-1">
                        File Name
                    </label>

                    <input
                        type="text"
                        name="filename"
                        placeholder="e.g. Algebra Introduction"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- UPLOAD AREA --}}
                <div>
                    <label class="block text-tertiary text-xs mb-1">
                        Upload File <span class="text-accent">*</span>
                    </label>

                    <div
                        onclick="document.getElementById('contentFileInput').click()"
                        class="relative bg-secondary border-primary border-rounded mt-2 cursor-pointer">

                        <div id="contentUploadPlaceholder"
                            class="flex flex-col items-center justify-center h-32 text-tertiary text-xs">
                            <i class="fa-solid fa-cloud-arrow-up text-lg mb-1"></i>
                            Click to upload video
                        </div>

                        <div id="contentUploadFilename"
                            class="hidden flex items-center justify-center h-32 text-primary text-sm font-medium">
                        </div>
                    </div>

                    <input
                        type="file"
                        name="file"
                        id="contentFileInput"
                        required
                        accept="video/*"
                        class="hidden"
                        onchange="showSelectedContentFile()">
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 border-top p-4">
                <button
                    type="button"
                    onclick="document.getElementById('contentAddPopup').classList.add('hidden')"
                    class="px-4 py-2 text-xs bg-secondary text-secondary bg-hover-tertiary border-rounded">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 text-sm bg-invert text-invert border-rounded hover-invert">
                    Upload
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    function selectContentType(type, btn) {

        // reset all buttons
        btn.parentElement.querySelectorAll('button').forEach(b => {
            b.classList.remove('bg-invert', 'text-invert');
            b.classList.add('bg-primary', 'text-primary');
        });

        // activate selected
        btn.classList.remove('bg-primary', 'text-primary');
        btn.classList.add('bg-invert', 'text-invert');

        document.getElementById('contentTypeInput').value = type;

        const input = document.getElementById('contentFileInput');
        input.value = '';

        if (type === 'video') input.accept = 'video/*';
        else if (type === 'pdf') input.accept = 'application/pdf';
        else if (type === 'image') input.accept = 'image/*';
        else if (type === 'audio') input.accept = 'audio/*';

        document.getElementById('contentUploadFilename').classList.add('hidden');
        const placeholder = document.getElementById('contentUploadPlaceholder');
        placeholder.classList.remove('hidden');
        placeholder.innerText = 'Click to upload ' + type;
    }

    function showSelectedContentFile() {
        const input = document.getElementById('contentFileInput');
        if (!input.files.length) return;

        document.getElementById('contentUploadPlaceholder').classList.add('hidden');
        const box = document.getElementById('contentUploadFilename');
        box.innerText = input.files[0].name;
        box.classList.remove('hidden');
    }
    document.addEventListener('turbo:load', () => {

        const form = document.getElementById('contentAddForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Uploading…';

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {

                    if (!res.success) {
                        alert(res.message || 'Upload failed');
                        return;
                    }

                    // ✅ SUCCESS
                    appendContent(res.data);
                    closeContentPopup();

                    // 🔄 reset form
                    form.reset();

                    // 🔥 OPTIONAL: trigger refresh / event
                    document.dispatchEvent(
                        new CustomEvent('content-uploaded', {
                            detail: res.data
                        })
                    );

                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Upload';
                });
        });
    });

    /* ------------------------
       Helpers
    ------------------------- */

    function closeContentPopup() {
        document.getElementById('contentAddPopup')
            ?.classList.add('hidden');
    }

    function appendContent(content) {

        const grid = document.getElementById('contents');
        if (!grid) return;
        const noContent = document.getElementById('no_content');
        if (noContent) {
            noContent.remove();
        }


        const wrapper = document.createElement('div');
        wrapper.className =
            'break-inside-avoid group relative border-primary border-rounded overflow-hidden';

        let mediaHtml = '';

        if (content.type === 'image') {
            mediaHtml = `
            <a href="${content.url}">
                <img src="${content.url}"
                     class="w-full h-auto object-contain" />
            </a>
        `;
        } else if (content.type === 'video') {
            mediaHtml = `
            <video src="${content.url}"
                   class="w-full h-auto object-contain"
                   muted loop preload="metadata"
                   onmouseenter="this.play()"
                   onmouseleave="this.pause();this.currentTime=0;">
            </video>
        `;
        } else if (content.type === 'pdf') {
            mediaHtml = `
            <a href="${content.url}">
                <div class="flex items-center justify-center py-16">
                    <i class="fa-solid fa-file-pdf text-5xl text-red-500"></i>
                </div>
            </a>
        `;
        } else if (content.type === 'audio') {
            mediaHtml = `
            <div class="flex items-center justify-center py-16">
                <i class="fa-solid fa-music text-5xl text-accent"></i>
            </div>
        `;
        }

        wrapper.innerHTML = `
        ${mediaHtml}

        <span class="bg-tertiary text-primary absolute top-1 left-1
                     text-[12px] px-3 py-1 rounded-full">
            ${content.type.toUpperCase()}
        </span>

        <div class="text-primary text-sm bg-primary px-4 py-2 border-top">
            <p class="font-semibold overflow-hidden">
                ${content.filename}
            </p>
            <p class="opacity-80">
                  Size: ${formatSize(content.size)}
            </p>
        </div>

        <div class="px-4 py-2 border-top flex justify-between
                    text-xs items-center bg-primary">
            <p class="text-tertiary">
                ${new Date().toLocaleDateString()}
            </p>

            <form action="/admin/contents/${content.id}"
                  method="POST"
                  onsubmit="return confirm('Delete this content?');">
                <input type="hidden" name="_token"
                       value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button class="text-secondary text-hover-primary">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    `;

        // 🔥 prepend = top me add
        grid.prepend(wrapper);
    }

    function formatSize(bytes) {
        if (!bytes || bytes === 0) return '-';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
</script>