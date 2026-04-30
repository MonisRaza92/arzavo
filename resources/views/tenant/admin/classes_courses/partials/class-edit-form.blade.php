<div id="classEditPopup" class="hidden fixed inset-0 z-100 bg-black/90 flex items-center justify-center pt-10">

    <div class="popup-content bg-primary border-primary border-rounded w-full max-w-md h-full sm:h-auto md:max-h-10/12 overflow-auto scrollbar">
        <form action="" id="classEditForm" method="POST">
            <div class="p-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editClassId">

                {{-- Image --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Image (optional)
                    </label>
                    <x-input.image name="updateimage"  />
                </div>


                {{-- Category --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Academic Category
                    </label>
                    <select name="academic_category_id" id="editClassCategoryId"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                        <option value="">-- Select Category (Optional) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Class / Course Name <span class="text-accent">*</span>
                    </label>
                    <input type="text"
                        name="name"
                        id="editClassName"
                        required
                        placeholder="e.g. NEET 2026"
                        class="w-full p-2 bg-primary border-primary border-rounded input-focus text-sm">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="block text-tertiary text-xs mb-1">
                        Description
                    </label>
                    <textarea name="description"
                        rows="3"
                        id="editClassDescription"
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
                                Enable this class to make it visible to students
                            </span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox"
                                name="status"
                                id="editClassStatus"
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
                    onclick="document.getElementById('classEditPopup').classList.add('hidden')"
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
    function functionGetClassCourseDetails(classId) {

        fetch(`/admin/classes/courses/${classId}/get`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields with the fetched data
                document.getElementById('classEditForm').action = `/admin/classes/courses/${data.id}/update`;
                document.getElementById('editClassId').value = data.id;
                document.getElementById('editClassCategoryId').value = data.academic_category_id || '';
                document.getElementById('editClassName').value = data.name;
                document.getElementById('editClassDescription').value = data.description || '';
                const statusCheckbox = document.getElementById('editClassStatus');
                statusCheckbox.checked = data.status == 1;
                // IMAGE
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
                console.error('Error fetching class/course details:', error);
            });
    }
</script>