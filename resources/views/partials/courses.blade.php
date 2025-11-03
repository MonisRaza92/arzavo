<div class="courses-section mt-15 {{ $courses->isEmpty() ? 'hidden' : '' }}">
    <div class="container">
        <div class="section-heading mb-4">
            <h2 class="text-3xl font-bold mb-3 uppercase" style="color:var(--text-primary);">Featured Courses</h2>
            <p style="color:var(--secondary-text-color);">Discover a variety of courses tailored to your learning needs.</p>
        </div>
        <div class="course-list grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 tab-pane">
            @foreach ($courses->take(6) as $course)
            @if ($course->status === 'published')
            <div class="course-card rounded-md shadow-md overflow-hidden relative" style="background-color:var(--secondary-background);">
                <img src="{{ asset($course->thumbnail ?? 'images/course.jpg') }}" alt="" class="w-full h-auto object-cover">
                <div class="course-and-subject flex items-center gap-2 absolute top-2 right-2">
                    @if ($course->is_recommended)
                    <span class="course-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-red-500 text-white">Recommended</span>
                    @endif
                    @if ($course->is_new)
                    <span class="subject-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-yellow-400 text-black">New</span>
                    @endif
                </div>

                <div class="card-content p-4 relative">
                    <div class="course-and-subject flex items-center gap-2">
                        <span class="course-name text-xs rounded-sm p-1 pr-2 uppercase font-bold" style="color:var(--text-light); background-color: var(--primary-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-book"></i> {{ $course->Subject->name ?? 'N/A' }}</span>
                        <span class="subject-name text-xs rounded-sm p-1 pr-2 uppercase font-bold" style="color:var(--text-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
                        @if ($course->is_certified)
                        <span class="bg-blue-500 border border-blue-500 text-white uppercase text-xs font-bold pr-2 p-1 rounded-sm"><i class="fa-solid fa-certificate"></i> Certified</span>
                        @endif
                    </div>
                    <h3 class="course-title text-2xl uppercase font-bold mt-4">{{ Str::limit($course->title, 35) }}</h3>
                    <div class="instructor-and-rating flex items-center justify-between mt-4">
                        <div class="instructor-details flex items-center gap-3">
                            <img src="{{ asset('images/pngs/notepad.webp') }}" alt="" class="instructor-image w-[30px] h-[30px] rounded-full">
                            <div class="instructor-name-and-education mt-2.5">
                                <h6 class="text-sm/0 font-semibold" style="color:var(--text-color);">{{ $course->teacher->fname }} {{ $course->teacher->lname }}</h6>
                                <span class="text-xs/0 italic" style="color:var(--secondary-text-color);">{{ $course->teacher->qualification ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="rating flex items-center gap-1">
                            <i class="fa-solid fa-star text-yellow-500"></i>
                            <i class="fa-solid fa-star text-yellow-500"></i>
                            <i class="fa-solid fa-star text-yellow-500"></i>
                            <i class="fa-solid fa-star text-yellow-500"></i>
                            <i class="fa-solid fa-star-half-stroke text-yellow-500"></i>
                            <span class="text-sm/0 font-bold" style="color:var(--text-color);">4.5</span>
                        </div>
                    </div>
                    <!-- <div class="course-details flex justify-between items-center mt-4 pt-3" style="border-top: 2px solid var(--border-color); color:var(--secondary-text-color);">
                        <span class="font-bold"><i class="fa-solid fa-clock"></i> {{ $course->duration }} Minutes</span>
                        <span class="font-bold"><i class="fa-solid fa-calendar"></i> {{ $course->created_at->format('d F Y') }}</span>
                    </div> -->
                    <div class="pricing-and-actions mt-4" style="border-top: 2px solid var(--border-color);">
                        <div class="price-and-discount flex items-center gap-1 mt-2 relative">
                            @if ($course->discount_price)
                            <span class="text-xl font-bold" style="color:var(--primary-color);">₹{{ $course->discount_price }}</span>
                            <span class="text-sm line-through" style="color:var(--secondary-text-color);">₹{{ $course->price }}</span>
                            @else
                            <span class="text-xl font-bold" style="color:var(--primary-color);">₹{{ $course->price }}</span>
                            @endif
                            @if ($course->discount_price && $course->price)
                            @php
                            $discountPercentage = round((($course->price - $course->discount_price) / $course->price) * 100);
                            @endphp
                            <div class="discount-percentage absolute right-0 top-1 rounded-sm font-bold text-xs text-emerald-800 bg-emerald-200 px-2 py-1">Discount of {{ $discountPercentage }}% applied</div>
                            @endif
                        </div>
                        <div class="action-buttons flex items-center gap-2 mt-2">
                            <a href="{{ route('view-course', $course->slug) }}" class="default-button text-center py-2 uppercase font-bold" style="width: calc(100% - 2rem);">Explore</a>
                            <form action="{{ route('buy-course', $course->id ) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="default-button-outline w-full text-center py-2 uppercase font-bold">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>