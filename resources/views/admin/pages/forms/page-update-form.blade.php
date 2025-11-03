<div id="page-update-form" class="fixed hidden top-0 left-0 flex justify-center items-center bg-zinc-900/50 inset-0 z-20 w-full h-full">
    <div class=" bg-primary border-rounded lg:w-1/3 w-full">
        <div class="flex justify-between items-center p-4 border-bottom">
            <h2 class="text-xl font-semibold">Update Page</h2>
            <button type="button" onclick="document.getElementById('page-update-form').classList.add('hidden')"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div class="p-4">
            <form id="updatePageForm" method="POST" action="{{ route('admin.pages.update', $page->id) }}">
                @csrf
                @method('PUT')

                <div class="flex gap-4">
                    <div class="flex flex-col">
                        <label>Title</label>
                        <input id="edit_name" type="text" name="name" value="{{ $page->name }}" placeholder="Home" class="mb-1 border-primary border-rounded p-2" required>
                    </div>

                    <div class="flex flex-col">
                        <label>Slug (URL)</label>
                        <input id="edit_slug" type="text" name="slug" value="{{ $page->slug }}" placeholder="home" class="border-primary border-rounded p-2" required>
                        <span class="text-xs text-secondary mb-1 block w-full">Lowercase and no spaces</span>
                    </div>
                </div>

                <label>Meta Title</label>
                <input id="edit_meta_title" type="text" name="meta_title" value="{{ $page->meta_title }}" placeholder="Ex: ARZAQ INSIGHT - Home" class="w-full mb-1 border-primary border-rounded p-2">

                <label>Meta Description</label>
                <textarea id="edit_meta_description" name="meta_description" class="w-full mb-1 border-primary border-rounded p-2">{{ $page->meta_description }}</textarea>

                <label>Status</label>
                <select id="edit_status" name="status" class="w-full mb-2 border-primary border-rounded p-2">
                    <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>Draft</option>
                </select>

                <div class="flex justify-end items-center gap-2 mt-4">
                    <button type="button" class="border-invert font-bold px-2 py-1.5 border-rounded" onclick="document.getElementById('page-update-form').classList.add('hidden')">Close</button>
                    <button type="submit" class="bg-invert border-invert font-bold px-2 py-1.5 border-rounded text-invert">Update Page</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function openEditModal(id, name, slug, meta_title, meta_description, status) {
        document.getElementById('page-update-form').classList.remove('hidden');
        document.getElementById('updatePageForm').action = `/admin/pages/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_slug').value = slug;
        document.getElementById('edit_meta_title').value = meta_title;
        document.getElementById('edit_meta_description').innerHTML = `{{ $page->meta_description }}`;
        document.getElementById('edit_status').value = status;
    }
</script>