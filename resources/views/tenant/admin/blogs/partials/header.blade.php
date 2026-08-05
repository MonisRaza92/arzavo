{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-blog mr-1 text-base"></i>
            Blogs & Stories
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Manage all Blogs & Stories at {{ strtolower(app('currentTenant')->name) }}</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <!-- Search Bar -->
        <input type="text"
            id="classCourseSearch"
            placeholder="Search blogs..."
            class="px-3 py-2 text-sm bg-primary border-primary border-rounded input-focus hidden sm:block">

        <!-- Add View Toggle Button Cards/Table -->
        <div class="border-primary border-rounded hidden sm:flex">
            <button class="p-2 text-sm bg-secondary border-rounded"
                onclick="toggleView('classCourseContainer', 'grid')">
                <i class="fa fa-th-large"></i>
            </button>
            <button class="p-2 text-sm bg-primary border-rounded"
                onclick="toggleView('classCourseContainer', 'list')">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <a href="{{ route('admin.blog.create') }}"
            class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert flex items-center justify-center">
            Add New
            <i class="fa fa-square-plus ml-1"></i>
        </a>
    </div>
</div>
