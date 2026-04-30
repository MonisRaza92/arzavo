<div id="categoryEditPopup" class="hidden fixed inset-0 z-100 bg-black/90 flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        <form action="" id="categoryEditForm" method="POST">
            <div class="p-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editCategoryId">

                {{-- Image --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Image (optional)
                    </label>
                    <x-input.image name="updateimage"  />
                </div>


                {{-- Name --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Category Name <span class="text-accent">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        id="editCategoryName"
                        required
                        placeholder="e.g. High School"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Description
                    </label>
                    <textarea name="description"
                        rows="3"
                        id="editCategoryDescription"
                        placeholder="Short description"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm"></textarea>
                </div>

                {{-- Status --}}
                <div class="mb-4 p-3 border-primary border-rounded block w-full">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-primary text-sm font-semibold mb-1">
                                Status
                            </label>
                            <span class="text-tertiary text-xs">
                                Enable this category to make it visible
                            </span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox"
                                name="status"
                                id="editCategoryStatus"
                                value="1"
                                checked
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black"></div>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex justify-end gap-2 border-top p-4 sticky bottom-0 bg-primary">
                <button type="button"
                    onclick="document.getElementById('categoryEditPopup').classList.add('hidden')"
                    class="px-4 py-2 text-xs bg-secondary text-secondary bg-hover-tertiary border-rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-invert text-invert border-rounded hover-invert">
                    Save
                </button>
            </div>
        </form>

    </div>
</div>
<script>
    function functionGetCategoryDetails(categoryId) {

        fetch(`/admin/academic-categories/${categoryId}/get`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields with the fetched data
                document.getElementById('categoryEditForm').action = `/admin/academic-categories/${data.id}/update`;
                document.getElementById('editCategoryId').value = data.id;
                document.getElementById('editCategoryName').value = data.name;
                document.getElementById('editCategoryDescription').value = data.description || '';
                const statusCheckbox = document.getElementById('editCategoryStatus');
                statusCheckbox.checked = data.status == 1;

                // IMAGE
                const wrapper = document.querySelector('.image-field-updateimage');
                if (wrapper) {
                    const input = wrapper.querySelector('input[name="updateimage"]');
                    const preview = wrapper.querySelector('[data-content-preview]');
                    const placeholder = wrapper.querySelector('[data-content-placeholder]');

                    if (data.image) {
                        const src = typeof window.mediaUrl === 'function' ? window.mediaUrl(data.image) : data.image;
                        preview.src = src;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        if (input) input.value = data.image;
                    } else {
                        preview.classList.add('hidden');
                        preview.src = '';
                        placeholder.classList.remove('hidden');
                        if (input) input.value = '';
                    }
                }

            })
            .catch(error => {
                console.error('Error fetching category details:', error);
            });
    }
</script>