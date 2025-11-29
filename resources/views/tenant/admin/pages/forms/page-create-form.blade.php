<div id="page-create-form" class="hidden fixed top-0 left-0 flex justify-center items-center bg-zinc-900/50 inset-0 z-20 w-full h-full">
    <div class=" bg-primary border-rounded lg:w-1/3 w-full">
        <div class="flex justify-between items-center p-4 border-bottom">
            <h2 class="text-xl font-semibold">Create New Page</h2>
            <button type="button" onclick="document.getElementById('page-create-form').classList.add('hidden')"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-4">
            <form method="POST" action="{{ route('admin.pages.store') }}">
                @csrf
                <div class="flex gap-4">
                    <div class="flex flex-col">
                        <label>Title</label>
                        <input type="text" name="name" placeholder="Home" class="mb-1 border-primary border-rounded p-2" required>

                    </div>
                    <div class="flex flex-col">
                        <label>Slug (URL)</label>
                        <input type="text" name="slug" placeholder="home" class="border-primary border-rounded p-2" required>
                        <span class="text-xs text-secondary mb-1 block w-full">Lowercase and no sapces</span>
                    </div>
                </div>
                <label>Meta Title</label>
                <input type="text" name="meta_title" placeholder="Ex : ARZAQ INSIGHT - Home" class="w-full mb-1 border-primary border-rounded p-2">

                <label>Meta Description</label>
                <textarea name="meta_description" class="w-full mb-1 border-primary border-rounded p-2"></textarea>

                <label>Status</label>
                <select name="status" class="w-full mb-2 border-primary border-rounded p-2">
                    <option value="1">Published</option>
                    <option value="0">Draft</option>
                </select>
                <div class="flex justify-end items-center gap-2 mt-4">
                    <button type="button" class="border-invert font-bold px-2 py-1.5 border-rounded" onclick="document.getElementById('page-create-form').classList.add('hidden')">Close</button>
                    <button type="submit" class="bg-invert border-invert font-bold px-2 py-1.5 border-rounded text-invert">Create Page</button>
                </div>
            </form>
        </div>
    </div>
</div>