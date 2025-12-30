<div id="contentAddPopup" class="hidden fixed inset-0 z-40 bg-invert-secondary flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data">
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
                        File Name <span class="text-accent">*</span>
                    </label>

                    <input
                        type="text"
                        name="filename"
                        required
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
</script>