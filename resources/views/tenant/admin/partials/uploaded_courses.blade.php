<div class="uploaded-courses my-4">
    <div class="tabs flex gap-4 overflow-x-auto whitespace-nowrap p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <button class="default-button-outline uppercase font-bold tab-button active-li" data-tab="all-courses">All Courses</button>
        <button class="default-button-outline uppercase font-bold tab-button" data-tab="published-courses">Published</button>
        <button class="default-button-outline uppercase font-bold tab-button" data-tab="draft-courses">Draft</button>
        <button class="default-button-outline uppercase font-bold tab-button" data-tab="archived-courses">Archived</button>
    </div>
    <div class="tab-content mt-4">
        <!-- Content for all courses -->
        <div class="course-list grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 tab-pane" id="all-courses">
            @foreach ($courses as $course)
            <div class="course-card rounded-md overflow-hidden relative" style="background-color:var(--secondary-background);">
                <img src="{{ asset($course->thumbnail ?? 'images/course.jpg') }}" alt="" class="w-full h-auto object-cover">
                <div class="course-and-Subject->name flex items-center gap-2 absolute top-2 right-2">
                    @if ($course->is_recommended)
                    <span class="course-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-red-500 text-white">Recommended</span>
                    @endif
                    @if ($course->is_new)
                    <span class="Subject->name-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-yellow-400 text-black">New</span>
                    @endif
                </div>
                <span class="absolute left-2 top-2 bg-gray-500 text-white uppercase text-xs font-bold pr-2 p-1 rounded-sm"><i class="fa-solid fa-file"></i> {{ $course->status }}</span>
                <div class="card-content p-4 relative rounded-md" style="border: 1px solid var(--border-color);">
                    <div class="course-and-Subject->name flex items-center gap-2">
                        <span class="course-name text-xs rounded-sm p-1 pr-2 uppercase " style="color:var(--text-light); background-color: var(--primary-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-book"></i> {{ $course->Subject->name }}</span>
                        <span class="Subject->name-name text-xs rounded-sm p-1 pr-2 uppercase" style="color:var(--text-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
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
                    <!-- <div class="course-details flex justify-between items-center mt-4 border-t pt-3" style="border-color:var(--border-color); color:var(--secondary-text-color);">
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
                        <div class="action-buttons flex items-center gap-2 mt-4">
                            <a href="{{ route('update-course', $course->id) }}" class="default-button text-center py-2 uppercase font-bold" style="width: calc(100% - 2rem);">Edit</a>
                            <form action="{{ route('delete-course') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $course->id }}">
                                <button type="submit" class="default-button-outline w-full text-center py-2 uppercase font-bold text-red-500! border-red-500! hover:bg-red-500! hover:text-white!">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Content for published courses -->
        <div class="course-list hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 tab-pane" id="published-courses">
            @foreach ($courses as $course)
            @if ($course->status === 'published')
            <div class="course-card rounded-md overflow-hidden relative" style="background-color:var(--secondary-background);">
                <img src="{{ asset($course->thumbnail ?? 'images/course.jpg') }}" alt="" class="w-full h-auto object-cover">
                <div class="course-and-Subject->name flex items-center gap-2 absolute top-2 right-2">
                    @if ($course->is_recommended)
                    <span class="course-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-red-500 text-white">Recommended</span>
                    @endif
                    @if ($course->is_new)
                    <span class="Subject->name-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-yellow-400 text-black">New</span>
                    @endif
                </div>
                <span class="absolute left-2 top-2 bg-gray-500  text-white uppercase text-xs font-bold pr-2 p-1 rounded-sm"><i class="fa-solid fa-file"></i> {{ $course->status }}</span>
                <div class="card-content p-4 relative rounded-md" style="border: 1px solid var(--border-color);">
                    <div class="course-and-Subject->name flex items-center gap-2">
                        <span class="course-name text-xs rounded-sm p-1 pr-2 uppercase " style="color:var(--text-light); background-color: var(--primary-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-book"></i> {{ $course->Subject->name }}</span>
                        <span class="Subject->name-name text-xs rounded-sm p-1 pr-2 uppercase" style="color:var(--text-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
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
                    <!-- <div class="course-details flex justify-between items-center mt-4 border-t pt-3" style="border-color:var(--border-color); color:var(--secondary-text-color);">
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
                        <div class="action-buttons flex items-center gap-2 mt-4">
                            <a href="{{ route('update-course', $course->id) }}" class="default-button text-center py-2 uppercase font-bold" style="width: calc(100% - 2rem);">Edit</a>
                            <form action="{{ route('delete-course') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $course->id }}">
                                <button type="submit" class="default-button-outline w-full text-center py-2 uppercase font-bold text-red-500! border-red-500! hover:bg-red-500! hover:text-white!">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <!-- Content for archived courses -->
        <div class="course-list hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 tab-pane" id="archived-courses">
            @foreach ($courses as $course)
            @if ($course->status === 'archived')
            <div class="course-card rounded-md overflow-hidden relative" style="background-color:var(--secondary-background);">
                <img src="{{ asset($course->thumbnail ?? 'images/course.jpg') }}" alt="" class="w-full h-auto object-cover">
                <div class="course-and-Subject->name flex items-center gap-2 absolute top-2 right-2">
                    @if ($course->is_recommended)
                    <span class="course-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-red-500 text-white">Recommended</span>
                    @endif
                    @if ($course->is_new)
                    <span class="Subject->name-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-yellow-400 text-black">New</span>
                    @endif
                </div>
                <span class="absolute left-2 top-2 bg-gray-500  text-white uppercase text-xs font-bold pr-2 p-1 rounded-sm"><i class="fa-solid fa-file"></i> {{ $course->status }}</span>
                <div class="card-content p-4 relative rounded-md" style="border: 1px solid var(--border-color);">
                    <div class="course-and-Subject->name flex items-center gap-2">
                        <span class="course-name text-xs rounded-sm p-1 pr-2 uppercase " style="color:var(--text-light); background-color: var(--primary-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-book"></i> {{ $course->Subject->name }}</span>
                        <span class="Subject->name-name text-xs rounded-sm p-1 pr-2 uppercase" style="color:var(--text-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
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
                    <!-- <div class="course-details flex justify-between items-center mt-4 border-t pt-3" style="border-color:var(--border-color); color:var(--secondary-text-color);">
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
                        <div class="action-buttons flex items-center gap-2 mt-4">
                            <a href="{{ route('update-course', $course->id) }}" class="default-button text-center py-2 uppercase font-bold" style="width: calc(100% - 2rem);">Edit</a>
                            <form action="{{ route('delete-course') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $course->id }}">
                                <button type="submit" class="default-button-outline w-full text-center py-2 uppercase font-bold text-red-500! border-red-500! hover:bg-red-500! hover:text-white!">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <!-- Content for draft courses -->
        <div class="course-list hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 tab-pane" id="draft-courses">
            @foreach ($courses as $course)
            @if ($course->status === 'draft')
            <div class="course-card rounded-md overflow-hidden relative" style="background-color:var(--secondary-background);">
                <img src="{{ asset($course->thumbnail ?? 'images/course.jpg') }}" alt="" class="w-full h-auto object-cover">
                <div class="course-and-Subject->name flex items-center gap-2 absolute top-2 right-2">
                    @if ($course->is_recommended)
                    <span class="course-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-red-500 text-white">Recommended</span>
                    @endif
                    @if ($course->is_new)
                    <span class="Subject->name-name text-xs rounded-sm font-bold uppercase px-3 py-1  bg-yellow-400 text-black">New</span>
                    @endif
                </div>
                <span class="absolute left-2 top-2 bg-gray-500  text-white uppercase text-xs font-bold pr-2 p-1 rounded-sm"><i class="fa-solid fa-file"></i> {{ $course->status }}</span>
                <div class="card-content p-4 relative rounded-md" style="border: 1px solid var(--border-color);">
                    <div class="course-and-Subject->name flex items-center gap-2">
                        <span class="course-name text-xs rounded-sm p-1 pr-2 uppercase " style="color:var(--text-light); background-color: var(--primary-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-book"></i> {{ $course->Subject->name }}</span>
                        <span class="Subject->name-name text-xs rounded-sm p-1 pr-2 uppercase" style="color:var(--text-color); border: 1px solid var(--primary-color);"><i class="fa-solid fa-globe"></i> {{ $course->language }}</span>
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
                    <!-- <div class="course-details flex justify-between items-center mt-4 border-t pt-3" style="border-color:var(--border-color); color:var(--secondary-text-color);">
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
                        <div class="action-buttons flex items-center gap-2 mt-4">
                            <a href="{{ route('update-course', $course->id) }}" class="default-button text-center py-2 uppercase font-bold" style="width: calc(100% - 2rem);">Edit</a>
                            <form action="{{ route('delete-course') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $course->id }}">
                                <button type="submit" class="default-button-outline w-full text-center py-2 uppercase font-bold text-red-500! border-red-500! hover:bg-red-500! hover:text-white!">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            if (tabButtons.length > 0 && tabPanes.length > 0) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        // Remove active class from all buttons and panes
                        tabButtons.forEach(btn => btn.classList.remove('active-li'));
                        tabPanes.forEach(pane => pane.classList.add('hidden'));

                        // Add active class to the clicked button and corresponding pane
                        this.classList.add('active-li');
                        const targetPane = document.getElementById(targetTab);
                        if (targetPane) {
                            targetPane.classList.remove('hidden');
                        }
                    });
                });
            }
        });
    </script>
</div>