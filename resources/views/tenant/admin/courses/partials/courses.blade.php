@if($courses->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">

    @foreach ($courses as $course)
    <div class="bg-primary border-primary border-rounded overflow-hidden hover-primary transition-all">

        {{-- THUMBNAIL --}}
        <div class="relative">
            <img
                src="{{ $course->thumbnail ?? asset($course->thumbnail) }}"
                class="w-full aspect-video object-cover border-bottom"
                alt="{{ $course->title }}">

            {{-- BADGES --}}
            <div class="absolute top-2 right-2 flex gap-1">
                @if($course->is_recommended)
                <span class="text-xs font-semibold px-2 py-1 bg-accent text-invert border-rounded">
                    Recommended
                </span>
                @endif
                @if($course->is_new)
                <span class="text-xs font-semibold px-2 py-1 bg-accent-secondary text-invert border-rounded">
                    New
                </span>
                @endif
            </div>

            {{-- STATUS --}}
            <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-1 bg-invert text-invert border-rounded uppercase">
                {{ $course->status }}
            </span>
        </div>

        {{-- CONTENT --}}
        <div class="p-4">

            {{-- META --}}
            <div class="flex flex-wrap gap-2 text-xs mb-2">

                {{-- SUBJECTS --}}
                @foreach($course->subjects->take(2) as $subject)
                <span class="px-2 py-1 border-primary border-rounded text-tertiary">
                    <i class="fa-solid fa-book"></i> {{ $subject->name }}
                </span>
                @endforeach

                {{-- LANGUAGE --}}
                <span class="px-2 py-1 border-primary border-rounded text-tertiary">
                    <i class="fa-solid fa-globe"></i> {{ $course->language }}
                </span>

                {{-- CERTIFICATE --}}
                @if($course->enable_certificates)
                <span class="px-2 py-1 bg-invert text-invert border-rounded">
                    <i class="fa-solid fa-certificate"></i> Certified
                </span>
                @endif
            </div>

            {{-- TITLE --}}
            <h3 class="text-lg font-bold text-primary leading-tight mb-3">
                {{ Str::limit($course->title, 45) }}
            </h3>

            {{-- AUTHOR --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <img src=""
                        class="w-8 h-8 rounded-full"
                        alt="">
                    <div>
                        <p class="text-sm font-semibold text-primary">
                            {{ $course->author->name ?? 'Instructor' }}
                        </p>
                        <p class="text-xs text-tertiary">
                            Instructor
                        </p>
                    </div>
                </div>

                {{-- RATING (STATIC FOR NOW) --}}
                <div class="flex items-center gap-1 text-yellow-500 text-sm">
                    ★★★★☆
                    <span class="text-primary font-semibold">4.5</span>
                </div>
            </div>

            {{-- PRICE --}}
            <div class="border-top pt-3">

                <div class="flex items-center gap-2 mb-3">
                    @if($course->discount_price)
                    <span class="text-xl font-bold text-accent">
                        ₹{{ $course->discount_price }}
                    </span>
                    <span class="text-sm line-through text-tertiary">
                        ₹{{ $course->price }}
                    </span>
                    @else
                    <span class="text-xl font-bold text-accent">
                        ₹{{ $course->price }}
                    </span>
                    @endif
                </div>

                {{-- ACTIONS --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                        class="flex-1 text-center py-2 bg-invert text-invert border-rounded font-semibold">
                        Edit
                    </a>

                    <form method="POST"
                        action="{{ route('admin.courses.destroy', $course->id) }}"
                        onsubmit="return confirm('Delete this course?')">
                        @csrf
                        @method('DELETE')
                        <button
                            class="px-4 py-2 border-accent text-accent border-rounded hover-invert">
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
    @endforeach

</div>
@else
<div class="p-6 text-center text-tertiary">
    No courses available yet.
</div>
@endif
