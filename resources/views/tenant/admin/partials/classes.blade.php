<div class="classes-cards my-6">
    <h2 class="text-xl font-semibold text-primary">Classes</h2>
    <div class="flex flex-row flex-nowrap overflow-x-auto scrollbar whitespace-nowrap gap-4 mt-2">

        @foreach ($classes as $class)
        <div class="class-card overflow-hidden shrink-0 border-rounded h-[350px] w-[240px] relative bg-primary border-primary">
            <img class="object-cover w-full h-full" src="{{ asset($class->image) }}" alt="Class Image">
            <h3 class="absolute bottom-0 left-0 w-full px-3 py-2 text-md font-medium bg-primary text-primary">
                {{ $class->name }} <i class="fa-solid fa-right-arrow"></i>
            </h3>
            <button onclick="document.getElementById('classMenu{{ $class->id }}').classList.toggle('hidden')" class="absolute cursor-pointer right-2 bottom-2 text-primary z-10" title="Menu">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <div id="classMenu{{ $class->id }}" class="hidden p-2 flex flex-col items-start gap-2 z-11 absolute right-2 bottom-8 bg-primary border-rounded border-primary">
                <button onclick="document.getElementById('updateClassForm{{ $class->id }}').classList.toggle('hidden')" class="text-primary" title="Edit">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                <button onclick="event.preventDefault(); document.getElementById('classDeleteForm{{ $class->id }}').submit();" type="button" class="text-primary" title="Delete">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
            <form id="classDeleteForm{{ $class->id }}" class="hidden" action="{{ route('admin-delete-class', ['id' => $class->id]) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
            <form id="updateClassForm{{ $class->id }}" class="hidden absolute top-0 left-0 w-full h-full border-rounded border-primary bg-primary p-4 z-20" action="{{ route('admin-update-class', ['id' => $class->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-semibold mb-4">Update Class</h3>
                <div class="mb-3">
                    <label for="className{{ $class->id }}" class="block text-sm font-medium mb-1">Class Name</label>
                    <input type="text" id="className{{ $class->id }}" name="name" value="{{ $class->name }}" class="w-full border text-sm p-2 border-primary border-rounded" required>
                </div>
                <div class="mb-3">
                    <label for="classImage{{ $class->id }}" class="block text-sm font-medium mb-1">Class Image</label>
                    <input type="file" id="classImage{{ $class->id }}" name="image" accept="image/*" class="w-full text-sm p-2 border-primary border-rounded">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('updateClassForm{{ $class->id }}').classList.add('hidden')" class="text-sm px-4 py-2 border-rounded bg-gray-300">Cancel</button>
                    <button type="submit" class="text-sm px-4 py-2 border-rounded bg-invert text-invert">Update</button>
                </div>
            </form>
        </div>
        @endforeach

        <!-- Add Class Inline Card -->
        <div class="class-card overflow-hidden shrink-0 border-rounded h-[350px] w-[240px] border-primary relative flex items-center justify-center bg-primary">

            <form id="addClassForm" action="{{ route('admin-add-class') }}" method="POST" enctype="multipart/form-data"
                class="w-full h-full relative">
                @csrf
                <!-- Preview Background -->
                <img id="classImagePreview"
                    class="object-cover w-full h-full absolute top-0 left-0 border-rounded hidden"
                    alt="Preview" style="pointer-events: none;">

                <!-- Overlay Inputs (Label always center) -->
                <div id="classOverlay"
                    class="absolute inset-0 flex flex-col items-center justify-center text-center z-10">
                    <input type="file" id="classImageInput" name="image" accept="image/*" class="hidden">
                    <label for="classImageInput" class="cursor-pointer bg-opacity-40 px-3 py-2 rounded">
                        <i class="fa-solid fa-image text-2xl"></i>
                        <p class="text-xs mt-1">Upload / Change Image</p>
                    </label>
                </div>

                <!-- Inputs at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-2 flex gap-2 z-11 bg-primary">
                    <input type="text" name="name" placeholder="Add New Class"
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
    const classImageInput = document.getElementById('classImageInput');
    const classImagePreview = document.getElementById('classImagePreview');

    classImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                classImagePreview.src = e.target.result;
                classImagePreview.classList.remove('hidden'); // show preview
            };
            reader.readAsDataURL(file);
        }
    });
</script>