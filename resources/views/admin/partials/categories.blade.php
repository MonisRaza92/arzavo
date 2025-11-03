<div class="categories-cards my-6">
    <h2 class="text-xl font-semibold text-primary">Categories</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">

        @foreach ($categories as $category)
        <div class="category-card border-rounded relative bg-primary border-primary overflow-hidden">
            <img class="object-cover w-full" src="{{ asset($category->image) }}" alt="Category Image">
            <div class="flex flex-row justify-between items-center bg-primary relative">
                <h3 class="w-full px-3 py-2 text-md font-medium text-primary">
                    {{ $category->name }} <i class="fa-solid fa-right-arrow"></i>
                </h3>
                <button onclick="document.getElementById('categoryMenu{{ $category->id }}').classList.toggle('hidden')" class="cursor-pointer text-primary z-10 pr-2" title="Menu">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <div id="categoryMenu{{ $category->id }}" class="hidden p-2 flex flex-col items-start gap-2 z-11 absolute right-2 bottom-8 bg-primary border-rounded border-primary">
                    <button onclick="document.getElementById('updateCategoryForm{{ $category->id }}').classList.toggle('hidden')" class="text-primary" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i> Update
                    </button>
                    <button onclick="event.preventDefault(); document.getElementById('categoryDeleteForm{{ $category->id }}').submit();" type="button" class="text-primary" title="Delete">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <form id="categoryDeleteForm{{ $category->id }}" class="hidden" action="{{ route('admin-delete-category', ['id' => $category->id]) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
            <form id="updateCategoryForm{{ $category->id }}" class="hidden absolute top-0 left-0 w-full h-full border-rounded border-primary bg-primary p-2 px-4 z-30" action="{{ route('admin-update-category', ['id' => $category->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-semibold mb-2">Update Category</h3>
                <div class="mb-2">
                    <label for="categoryName{{ $category->id }}" class="block text-sm font-medium mb-1">Category Name</label>
                    <input type="text" id="categoryName{{ $category->id }}" name="name" value="{{ $category->name }}" class="w-full border text-sm p-2 border-primary border-rounded" required>
                </div>
                <div class="mb-2">
                    <label for="categoryImage{{ $category->id }}" class="block text-sm font-medium mb-1">Category Image</label>
                    <input type="file" id="categoryImage{{ $category->id }}" name="image" accept="image/*" class="w-full text-sm p-2 border-primary border-rounded">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('updateCategoryForm{{ $category->id }}').classList.add('hidden')" class="text-sm px-4 py-2 border-rounded bg-gray-300">Cancel</button>
                    <button type="submit" class="text-sm px-4 py-2 border-rounded bg-invert text-invert">Update</button>
                </div>
            </form>
        </div>
        @endforeach

        <!-- Add Category Inline Card -->
        <div class="category-card overflow-hidden border-rounded min-h-50 border-primary relative flex items-center justify-center bg-primary">

            <form id="addCategoryForm" action="{{ route('admin-add-category') }}" method="POST" enctype="multipart/form-data"
                class="w-full h-full relative">
                @csrf
                <!-- Preview Background -->
                <img id="categoryImagePreview"
                    class="object-cover w-full h-full absolute top-0 left-0 border-rounded hidden"
                    alt="Preview" style="pointer-events: none;">

                <!-- Overlay Inputs (Label always center) -->
                <div id="categoryOverlay"
                    class="absolute inset-0 flex flex-col items-center justify-center text-center z-10">
                    <input type="file" id="categoryImageInput" name="image" accept="image/*" class="hidden">
                    <label for="categoryImageInput" class="cursor-pointer  bg-opacity-40 px-3 py-2 rounded">
                        <i class="fa-solid fa-image text-2xl"></i>
                        <p class="text-xs mt-1">Upload / Change Image</p>
                    </label>
                </div>

                <!-- Inputs at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-2 flex gap-2 z-11 bg-primary">
                    <input type="text" name="name" placeholder="Add New Category"
                        class="w-full border text-sm p-2 border-primary border-rounded"
                        required>

                    <button type="submit" class="text-xl p-1 border-primary border-rounded">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const categoryImageInput = document.getElementById('categoryImageInput');
    const categoryImagePreview = document.getElementById('categoryImagePreview');

    categoryImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                categoryImagePreview.src = e.target.result;
                categoryImagePreview.classList.remove('hidden'); // show preview
            };
            reader.readAsDataURL(file);
        }
    });
</script>