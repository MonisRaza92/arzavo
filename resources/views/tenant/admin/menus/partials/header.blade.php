{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-link mr-1 text-base"></i>
            Menu Lists <span class="hidden sm:block">Management</span>
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Manage all Menus and links at {{ strtolower(app('currentTenant')->name) }}</p>
    </div>

    <div class="right-content flex gap-2 items-center relative">
        <!-- Search Bar -->
        <input type="text"
            id="classCourseSearch"
            placeholder="Search Menu List..."
            class="px-3 py-2 text-sm bg-primary border-primary border-rounded input-focus hidden sm:block">

        <button
            class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert"
            onclick="document.getElementById('menuAddPopup').classList.toggle('hidden')">
            Add New
            <i class="fa fa-square-plus ml-1"></i>
        </button>
        <div class="form absolute right-0 top-full bg-primary p-3 border-primary border-rounded mt-2 hidden" id="menuAddPopup">
            <form action="{{ route('admin.menus.store') }}" method="post">
                @csrf
                <div class="flex gap-2 items-end">
                    <div class="field">
                        <label for="menuName" class="text-sm text-secondary font-semibold">Menu Name</label>
                        <p class="text-xs text-tertiary">Create menu for header, footer or custom</p>
                        <input type="text" name="name" id="menuName" placeholder="e.g. header, footer, custom" class="border-primary mt-2 border-rounded p-2">
                    </div>
                    <div class="btns flex flex-col items-end">
                        <button type="button" class="absolute top-3 right-3" onclick="document.getElementById('menuAddPopup').classList.add('hidden')"><i class="fa-solid fa-xmark text-lg text-primary"></i></button>
                        <button type="submit" class="bg-invert text-invert px-4 py-2 h-fit border-rounded border-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>