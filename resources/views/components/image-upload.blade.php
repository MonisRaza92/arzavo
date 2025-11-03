<div id="mediaModal" class="fixed inset-0 bg-black/50 flex justify-center items-start pt-20 hidden z-50">
    <div class="bg-white border-rounded w-11/12 md:w-3/4 lg:w-1/2 max-h-[80vh] overflow-y-auto scrollbar">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 left-0 w-full">
            <h3 class="text-lg font-bold text-primary"><i class="fa-solid fa-image"></i> Select Image</h3>
            <button onclick="closeImageMenu()" class="text-xl text-primary"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($images as $image)
                <div class="border-rounded overflow-hidden cursor-pointer" onclick="selectImage('{{ asset($image->filepath) }}', '{{ $image->filename }}')">
                    <img src="{{ asset($image->filepath) }}" alt="{{ $image->filename }}" class="w-full object-cover">
                </div>
                @endforeach
            </div>
        </div>
        <div class="p-4 sticky bottom-0 left-0 flex justify-end w-full">
            <form id="image-upload-form" action="{{ route('admin-upload-image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="imageInput" class="bg-invert text-md text-invert py-2 px-3 border-rounded">Upload File <i class="fas fa-upload pl-2 border-left"></i></label>
                <input type="file" name="image" accept="image/*" class="hidden" id="imageInput" onchange="submitImagesForm()">
            </form>
        </div>
    </div>
</div>
<script>
    function submitImagesForm() {
        document.getElementById('image-upload-form').submit();
    }


    let currentInputId = '';

    function openImageMenu(inputId) {
        currentInputId = inputId;
        const modal = document.getElementById('mediaModal');
        modal.classList.remove('hidden');
    }

    function closeImageMenu() {
        const modal = document.getElementById('mediaModal');
        modal.classList.add('hidden');
    }

    function selectImage(imageUrl, imageName) {
        if (!currentInputId) return;

        const input = document.getElementById(currentInputId);
        if (!input) return;

        // Update hidden input value
        input.value = imageUrl;

        // Get preview ID and related elements
        const previewId = currentInputId.replace('Input', 'Preview');
        let preview = document.getElementById(previewId);
        const uploadArea = input.closest('label').querySelector('.relative.bg-secondary');

        // 🖼️ If preview doesn't exist, create it dynamically
        if (!preview && uploadArea) {
            // Remove placeholder (upload icon/text)
            const placeholder = uploadArea.querySelector('div.flex.flex-col');
            if (placeholder) placeholder.remove();

            // Create new <img> tag
            preview = document.createElement('img');
            preview.id = previewId;
            preview.className = 'w-full object-contain p-4 fade-in';
            uploadArea.prepend(preview);

            // Add overlay (Change text)
            const overlay = document.createElement('div');
            overlay.className = 'absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition';
            overlay.innerHTML = `<span class="text-white text-sm bg-black/50 px-3 py-1 rounded-md">Change</span>`;
            uploadArea.appendChild(overlay);
        }

        // ✅ Update or show the image
        if (preview) {
            preview.src = imageUrl;
            preview.classList.remove('hidden');
        }

        // ✅ Close the modal
        closeImageMenu();

        // 🧠 Find both possible forms
        const sectionForm = input.closest('.editSectionForm');
        const customizeForm = document.getElementById('customize-form');

        // ✅ Submit section form (if available)
        if (sectionForm && typeof window.submitSectionForm === 'function') {
            window.submitSectionForm(sectionForm);
        }

        // ✅ Submit customize form (if available)
        if (customizeForm && typeof window.submitCustomizesForm === 'function') {
            // Thoda delay do taaki first request overlap na kare
            setTimeout(() => {
                window.submitCustomizesForm(customizeForm);
            }, 300);
        }
    }
</script>