@extends('layouts.website')
@section('title', 'Course Details - ' . ($settings["site_name"] ?? "ARZAQ INSIGHTS"))
@section('content')
<div class="single-course container flex flex-col lg:flex-row gap-4">
    <div class="course w-full lg:w-3/4">
        <div class="relative w-full aspect-video rounded-lg overflow-hidden">
            <!-- Thumbnail -->
            <img id="course-thumbnail"
                src="{{ asset($course->thumbnail) }}"
                alt="Course Thumbnail"
                class="absolute inset-0 w-full h-full object-cover z-10">

            <!-- Play Button -->
            <button id="play-btn" type="button"
                class="absolute inset-0 flex items-center justify-center z-20 bg-black/40 text-white text-5xl">
                ▶
            </button>

            <!-- Video Player -->
            <video id="course-video"
                class="w-full h-full hidden"
                preload="metadata"
                controlslist="nodownload noremoteplayback"
                controls>
                <source src="{{ asset($course->video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    <div class="related-course w-full lg:w-1/4 rounded-lg shadow-sm p-2 px-3 hidden md:block" style="background-color:var(--secondary-background);">
        <h2 class="text-xl font-bold mb-2 uppercase" style="color: var(--primary-color);">Related Courses</h2>
        <ul class="related-course-list">
            @foreach ($courses as $related)
            @if($related->status === 'published' && $related->slug !== request()->route('slug'))
            <div class="course-item mb-4 p-2 rounded-sm" style="background-color:var(--background-color);">
                <a href="{{ route('view-course', $related->slug) }}" class="flex items-center gap-3">
                    <img src="{{ asset($related->thumbnail ?? 'images/course.jpg') }}" alt="Course Thumbnail" class="h-16 object-cover rounded-sm" style="aspect-ratio: 16/9;">
                    <div class="course-info">
                        <h3 class="text-sm font-bold" style="color:var(--text-color);">{{ Str::limit($related->title, 30) }}</h3>
                        <span class="text-xs" style="color:var(--secondary-text-color);">By: {{ $related->teacher->fname }} {{ $related->teacher->lname }}</span>
                    </div>
                </a>
            </div>
            @endif
            @endforeach
        </ul>
    </div>
</div>
<div class="container">
    <div class="course-details my-6 rounded-lg p-4 shadow-sm" style="background-color:var(--secondary-background);">
        <h1 class="text-3xl font-bold mb-2 uppercase" style="color:var(--primary-color);">{{ $course->title }}</h1>
        <div class="flex flex-row flex-wrap gap-4 mt-4 mb-6">
            <span class="text-sm bg-orange-200 text-orange-700 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-book"></i> {{ $course->Class->name}}</span>
            <span class="text-sm bg-zinc-200 text-zinc-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-book"></i> {{ $course->Category->name}}</span>
            <span class="text-sm bg-blue-200 text-blue-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-book"></i> {{ $course->Subject->name}}</span>
            <span class="text-sm bg-green-200 text-green-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
            @if ($course->is_certified)
            <span class="text-sm bg-yellow-200 text-yellow-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-certificate"></i> Certified</span>
            @endif
            @if ($course->is_recommended)
            <span class="text-sm bg-red-200 text-red-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-thumbs-up"></i> Recommended</span>
            @endif
            @if ($course->is_new)
            <span class="text-sm bg-purple-200 text-purple-800 uppercase font-bold rounded-sm p-1 pr-2"><i class="fa-solid fa-star"></i> New</span>
            @endif
        </div>
        <p class="mb-4" style="color:var(--secondary-text-color);">{{ $course->description }}</p>
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 justify-between md:mt-6 mt-10">
            <div class="flex items-center gap-4">
                @if ($course->teacher && $course->teacher->profile_picture)
                <img src="{{ asset($course->teacher->profile_picture ?? 'images/pngs/notepad.webp') }}" alt="Instructor Image" class="w-18 h-18 rounded-sm object-cover">
                @else
                <h2 class="font-bold rounded-md text-6xl flex justify-center items-center w-18 h-17.5 pb-2" style="background-color: var(--primary-color); color:var(--text-light);">{{ strtoupper(substr($course->teacher->fname, 0, 1)) }}</h2>
                @endif

                <div class="instructor-info">
                    <h3 class="text-xl font-bold" style="color:var(--text-color);">{{ $course->teacher->fname }} {{ $course->teacher->lname }}</h3>
                    <p class="text-sm italic" style="color:var(--secondary-text-color);">{{ $course->teacher->qualification ?? 'N/A' }}</p>
                    <span class="text-sm" style="color: var(--secondary-text-color);">Published on : {{ \Carbon\Carbon::parse($course->created_at)->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="pricing w-full md:w-1/3 mt-6 pt-4 md:mt-0 md:pt-0 md:border-0 border-t-2" style="border-color: var(--border-color);">
                <form action="{{ route('buy-course', $course->id ) }}" method="POST" class="w-full">
                    @csrf
                    <div class="price-and-discount flex items-center gap-1 mt-2 relative">
                        @if ($course->discount_price)
                        @if ($course->discount_price == 0)
                        <span class="text-3xl font-bold text-green-600">Free</span>
                        @else
                        <span class="text-3xl font-bold" style="color:var(--primary-color);">₹{{ $course->discount_price }}</span>
                        @endif
                        <span class="text-sm line-through" style="color:var(--secondary-text-color);">₹{{ $course->price }}</span>
                        @else
                        @if ($course->price == 0)
                        <span class="text-3xl font-bold text-green-600">Free</span>
                        @else
                        <span class="text-3xl font-bold" style="color:var(--primary-color);">₹{{ $course->price }}</span>
                        @endif
                        @endif
                        @if ($course->discount_price && $course->price)
                        @php
                        $discountPercentage = round((($course->price - $course->discount_price) / $course->price) * 100);
                        @endphp
                        <div class="discount-percentage absolute right-0 top-1 rounded-sm font-bold text-xs text-emerald-800 bg-emerald-200 px-2 py-1">Discount of {{ $discountPercentage }}% applied</div>
                        @endif
                    </div>
                    @if ($course->price == 0 || $course->discounted_price == 0)
                    <button type="button" class="default-button w-full text-center py-2 mt-2 uppercase font-bold">Add To Cart</button>
                    @else
                    <button type="submit" class="default-button w-full text-center py-2 mt-2 uppercase font-bold">Buy Now</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="{{ $courses->count() == 1 ? 'hidden' : '' }} related-course w-full lg:w-1/4 rounded-lg shadow-sm p-2 px-3 md:hidden mt-4" style="background-color:var(--secondary-background);">
        <h2 class="text-xl font-bold mb-2 uppercase" style="color: var(--primary-color);">Related Courses</h2>
        <ul class="related-course-list">
            @foreach ($courses as $related)
            @if($related->status === 'published' && $related->slug !== request()->route('slug'))
            <div class="course-item mb-4 p-2 rounded-sm" style="background-color:var(--background-color);">
                <a href="{{ route('view-course', $related->slug) }}" class="flex items-center gap-3">
                    <img src="{{ asset($related->thumbnail ?? 'images/course.jpg') }}" alt="Course Thumbnail" class="h-16 object-cover rounded-sm" style="aspect-ratio: 16/9;">
                    <div class="course-info">
                        <h3 class="text-sm font-bold" style="color:var(--text-color);">{{ Str::limit($related->title, 30) }}</h3>
                        <span class="text-xs" style="color:var(--secondary-text-color);">By: {{ $related->teacher->fname }} {{ $related->teacher->lname }}</span>
                    </div>
                </a>
            </div>
            @endif
            @endforeach
        </ul>
    </div>
</div>
@include('partials.footer')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const playBtn = document.getElementById("play-btn");
        const thumbnail = document.getElementById("course-thumbnail");
        const video = document.getElementById("course-video");

        playBtn.addEventListener("click", function(e) {
            e.preventDefault(); // stop form submission if inside a form
            thumbnail.classList.add("hidden"); // hide thumbnail
            playBtn.classList.add("hidden"); // hide play button
            video.classList.remove("hidden"); // show video

            video.play().catch(err => {
                console.error("Video play failed:", err);
            });
        });

        video.addEventListener("ended", function() {
            thumbnail.classList.remove("hidden"); // show thumbnail again
            playBtn.classList.remove("hidden"); // show play button
            video.classList.add("hidden"); // hide video
            video.pause();
            video.currentTime = 0; // reset
        });
    });
</script>
@endsection