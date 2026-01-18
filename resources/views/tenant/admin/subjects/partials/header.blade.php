{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-book mr-1 text-base"></i>
            Subjects Management
        </h2>
        <p class="text-sm text-secondary hidden sm:block">Manage all subjects across different classes & courses at {{ strtolower(app('currentTenant')->name) }}</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <!-- Search Bar -->
        <input type="text"
            id="subjectsSearch"
            placeholder="Search Subjects..."
            class="px-3 py-2 text-sm bg-primary border-primary border-rounded input-focus hidden sm:block">

            <!-- Select class -->
            <div class="md:flex border-primary border-rounded hidden pr-2">
                <form action="" method="get">
                    <select name="class_filter" id="classFilter"
                    class="px-3 py-2 text-sm border-rounded"
                    onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    @foreach ($classCourses as $class)
                        <option value="{{ $class->name }}" {{ request('class_filter') == $class->name ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            
            <!-- Add View Toggle Button Cards/Table -->
            <div class="border-primary border-rounded hidden sm:flex">
                <button class="p-2 text-sm bg-secondary border-rounded"
                    onclick="toggleView('subjectsContainer', 'grid')">
                    <i class="fa fa-th-large"></i>
                </button>
                <button class="p-2 text-sm bg-primary border-rounded"
                    onclick="toggleView('subjectsContainer', 'list')">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

        <button
            class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert"
            onclick="document.getElementById('subjectAddPopup').classList.remove('hidden')">
            Add New
            <i class="fa fa-square-plus ml-1"></i>
        </button>
    </div>
</div>
