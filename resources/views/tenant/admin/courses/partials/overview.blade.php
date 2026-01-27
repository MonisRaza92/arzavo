<div id="overview" class="tab-content" tab-content="overview">
    <div class="header bg-primary border-rounded border-primary p-4 mb-4">
        <h2 class="text-lg font-semibold"><i class="fa-solid fa-book-open text-base mr-1"></i> Course Overview</h2>
        <p class="text-sm text-gray-500">Here you can view the course overview, description, and other details.</p>
    </div>
    <div class="course-content mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 col-span-1 p-4 bg-primary border-rounded border-primary">
            <div class="course-media">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <img src="{{ media($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full object-cover border-rounded h-50">
                    @if ($course->video)
                        <video src="{{ media($course->video) }}" controls class="object-cover border-rounded h-50"></video>
                    @endif
                </div>
                <div class="content mt-6">
                    <div class="flex gap-2 mb-4">
                        <span class="py-1 px-2 border-rounded border-primary bg-invert text-invert text-xs"><i class="fa-solid fa-globe mr-1"></i> {{ $course->language }}</span>
                        <span class="py-1 px-2 border-rounded border-primary bg-accent text-invert text-xs"><i class="fa-solid fa-layer-group mr-1"></i> {{ $course->level }}</span>
                        <span class="py-1 px-2 border-rounded border-primary bg-primary text-primary text-xs"><i class="fa-solid fa-clock mr-1"></i> {{ $course->duration }}</span>
                        <span class="py-1 px-2 border-rounded border-primary bg-accent-secondary text-invert text-xs"><i class="fa-solid fa-user mr-1"></i> {{ $course->max_students ? $course->max_students : 'Unlimited' }}</span>
                    </div>
                    <h2 class="text-xl font-semibold">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $course->description }}</p>
                </div>
                <div class="border-top p-4">
                </div>
            </div>
        </div>
        <div class="col-span-1 p-4 bg-primary border-rounded border-primary">

        </div>
    </div>
</div>