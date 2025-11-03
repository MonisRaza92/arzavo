@extends('layouts.app')
@section('title', 'Student Profile - '.Auth::user()->fname.' '.Auth::user()->lname)
@section('content')
<div class="my-4 container">
    <!-- Profile Overview -->
    <div class="relative rounded-md overflow-hidden mb-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <!-- Banner -->
        <div class="w-full bg-gray-200 relative">
            <img src="{{ asset(Auth::user()->banner ?? 'images/banner.webp') }}" class="w-full h-full object-cover" style="aspect-ratio: 16 / 4;">
            <form action="{{ route('profile-banner-update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="banner" class="hidden" id="bannerInput" onchange="this.form.submit()">
                <label for="bannerInput" class="absolute top-2 right-2 text-xs rounded-md p-2 cursor-pointer" style="background-color: var(--background-color); color: var(--text-color); border: 1px solid var(--border-color);"><i class="fas fa-camera"></i> Update</label>
            </form>
        </div>

        <!-- Profile Image + Basic Info -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center">
            <div class="relative -mt-18 w-32 flex-shrink-0">
                @if (Auth::user()->profile_picture)
                <img src="{{ asset(Auth::user()->profile_picture) }}"
                    class="w-32 h-32 rounded-md object-cover">
                @else
                <h2 class="font-bold text-8xl flex justify-center items-center w-32 h-32 rounded-md"
                    style="background-color: var(--primary-color); color: var(--text-light);">
                    {{ strtoupper(substr(Auth::user()->fname, 0, 1)) }}
                </h2>
                @endif
                <form action="{{ route('profile-picture-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_picture" class="hidden" id="profilePictureInput" onchange="this.form.submit()">
                    <label for="profilePictureInput" class="absolute bottom-1 right-1 text-xs rounded-sm p-1 cursor-pointer" style="background-color: var(--background-color); color: var(--text-color); border: 1px solid var(--border-color);"><i class="fas fa-camera"></i></label>
                </form>
            </div>
            <div class="md:ml-6 mt-6 md:mt-0 flex justify-between items-center w-full relative">
                <div>
                    <h2 class="text-2xl font-bold">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h2>
                    <p class="text-sm" style="color: var(--secondary-text-color);">{{ Auth::user()->username }}</p>
                </div>
                <button onclick="document.getElementById('profileEditForm').classList.remove('hidden')" class="absolute default-button -top-19 md:top-0 md:relative right-0 rounded-md px-4 py-2  flex justify-center items-center" title="Update Profile">Edit Profile <i class="fas fa-pencil-alt text-lg ml-2"></i></button>
            </div>
        </div>
        <div class="info px-6 pb-4">
            <p class="text-xs" style="color: var(--secondary-text-color);">Headline</p>
            <p class="text-lg" style="color: var(--text-color);">{{ Auth::user()->headline ?? 'Not added yet.' }}</p>
            <p class="text-sm mt-2" style="color: var(--secondary-text-color);"><i class="fas fa-map-marker-alt text-xs -ml-0.5"></i> {{ Auth::user()->city ?? 'Not Updated' }} {{ Auth::user()->state ?? '' }} <i class="fas fa-calendar-alt text-xs ml-2"></i> {{ Auth::user()->created_at->format('F j, Y') }}</p>
            <p class="text-sm mt-2" style="color: var(--text-color);"><i class="fas fa-chalkboard text-xs -ml-0.5"></i> {{ Auth::user()->class->name ?? 'Not Assigned' }} <i class="fas fa-video text-xs ml-2"></i> {{ Auth::user()->subject->name ?? 'Not Assigned' }}</p>
        </div>
        <div class="tabs mt-2 px-6 mb-4">
            <ul class="flex whitespace-nowrap overflow-x-auto scrollbar py-4 mb-4">
                <li><a href="#overview" class="tab-link px-4 default-button-outline ml-0 rounded-full! active-li"><i class="fas fa-user mr-1 text-sm"></i> Overview</a></li>
                <li><a href="#purchases" class="tab-link px-4 default-button-outline ml-4 rounded-full!"><i class="fas fa-shopping-cart mr-1 text-sm"></i> Purchases</a></li>
                <li><a href="#posts" class="tab-link px-4 default-button-outline ml-4 rounded-full!"><i class="fas fa-pencil-alt mr-1 text-sm"></i> Posts</a></li>
                <li><a href="#settings" class="tab-link px-4 default-button-outline ml-4 rounded-full!"><i class="fas fa-cog mr-1 text-sm"></i> Settings</a></li>
                <li><a href="#payments" class="tab-link px-4 default-button-outline ml-4 rounded-full!"><i class="fas fa-credit-card mr-1 text-sm"></i> Payments</a></li>
            </ul>
        </div>
    </div>
    <!-- Profile Edit Form -->
    <div id="profileEditForm" class="hidden fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 h-full md:h-11/12 w-full md:w-auto z-10000 px-4 rounded-md shadow-2xl overflow-y-auto scrollbar"
        style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">

        <div class="flex justify-between items-start sticky top-0 p-4 pt-6" style="background-color: var(--secondary-background);">
            <h3 class="text-2xl font-bold" style="color: var(--primary-color);">Edit Profile</h3>
            <button type="button" class="close-btn  text-xl" onclick="document.getElementById('profileEditForm').classList.add('hidden')"><i class="fas fa-times"></i></button>
        </div>

        <form id="profileForm" action="{{ route('profile-info-update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- BASIC INFORMATION -->
            <div class="px-4">
                <h4 class="text-lg font-semibold mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fname" class="block text-sm font-medium" style="color: var(--secondary-text-color);">First Name *</label>
                        <input type="text" name="fname" id="fname" value="{{ Auth::user()->fname }}" maxlength="255" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                    </div>
                    <div>
                        <label for="lname" class="block text-sm font-medium" style="color: var(--secondary-text-color);">Last Name *</label>
                        <input type="text" name="lname" id="lname" value="{{ Auth::user()->lname }}" maxlength="255" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Username *</label>
                        <input type="text" name="username" id="username" value="{{ Auth::user()->username }}" maxlength="100" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                        <p id="usernameError" class="text-red-500 text-sm mt-1 hidden">Username cannot exceed 100 characters.</p>
                        <p class="text-xs mt-1" style="color: var(--secondary-text-color);">Max 100 characters</p>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium" style="color: var(--secondary-text-color);">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="{{ Auth::user()->dob }}" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div class="md:col-span-2">
                        <label for="headline" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Headline</label>
                        <input type="text" name="headline" id="headline" value="{{ Auth::user()->headline }}" maxlength="100" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" placeholder="e.g., Creative Designer | Marketing Specialist">
                        <p id="headlineError" class="text-red-500 text-sm mt-1 hidden">Headline cannot exceed 100 characters.</p>
                        <p class="text-xs mt-1" style="color: var(--secondary-text-color);">Max 100 characters</p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="about" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">About</label>
                        <textarea name="about" id="about" maxlength="300" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" rows="4" placeholder="Write about yourself (max 300 characters)">{{ Auth::user()->about }}</textarea>
                        <p id="aboutError" class="text-red-500 text-sm mt-1 hidden">About cannot exceed 300 characters.</p>
                        <p class="text-xs mt-1" style="color: var(--secondary-text-color);">Max 300 characters</p>
                    </div>
                </div>
            </div>


            <!-- EDUCATION / SUBJECT DETAILS -->
            <div class="px-4">
                <h4 class="text-lg font-semibold mb-4">Education</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="class" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Class</label>
                        <select name="class_id" id="class" value="{{ Auth::user()->class }}"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" @if(Auth::user()->role === 'student') disabled @endif>
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ Auth::user()->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Subject</label>
                        <select name="subject_id" id="subject" value="{{ Auth::user()->subject }}"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" @if(Auth::user()->role === 'student') disabled @endif>
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ Auth::user()->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <!-- CONTACT INFORMATION -->
            <div class="px-4">
                <h4 class="text-lg font-semibold mb-4">Contact Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Email *</label>
                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);"
                            required>
                    </div>
                    <div>
                        <label for="number" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Phone Number *</label>
                        <input type="text" name="number" id="number" value="{{ Auth::user()->number }}"
                            maxlength="15"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);"
                            required>
                    </div>
                </div>
            </div>

            <!-- ADDRESS DETAILS -->
            <div class="px-4">
                <h4 class="text-lg font-semibold mb-4">Address Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="address" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Address</label>
                        <input type="text" name="address" id="address" value="{{ Auth::user()->address }}"
                            maxlength="500"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);"
                            placeholder="Enter full address">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">City</label>
                        <input type="text" name="city" id="city" value="{{ Auth::user()->city }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">State</label>
                        <input type="text" name="state" id="state" value="{{ Auth::user()->state }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Country</label>
                        <input type="text" name="country" id="country" value="{{ Auth::user()->country }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="pincode" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Pincode</label>
                        <input type="text" name="pincode" id="pincode" value="{{ Auth::user()->pincode }}"
                            maxlength="20"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="flex justify-between items-center sticky bottom-0 py-4" style="background-color: var(--secondary-background);">
                <p class="text-sm" style="color: var(--secondary-text-color);">Fields marked with * are required</p>
                <button type="submit" class="default-button uppercase">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- FRONTEND VALIDATION SCRIPT -->
    <script>
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            let valid = true;

            const fields = [{
                    el: 'username',
                    max: 100,
                    error: 'Username cannot exceed 100 characters.'
                },
                {
                    el: 'headline',
                    max: 100,
                    error: 'Headline cannot exceed 100 characters.'
                },
                {
                    el: 'about',
                    max: 300,
                    error: 'About cannot exceed 300 characters.'
                },
                {
                    el: 'fname',
                    max: 255,
                    error: 'First Name cannot exceed 255 characters.'
                },
                {
                    el: 'lname',
                    max: 255,
                    error: 'Last Name cannot exceed 255 characters.'
                },
                {
                    el: 'email',
                    max: 255,
                    error: 'Email cannot exceed 255 characters.'
                },
                {
                    el: 'number',
                    max: 15,
                    error: 'Phone number cannot exceed 15 characters.'
                },
                {
                    el: 'class',
                    max: 255,
                    error: 'Class cannot exceed 255 characters.'
                },
                {
                    el: 'subject',
                    max: 255,
                    error: 'Subject cannot exceed 255 characters.'
                },
                {
                    el: 'address',
                    max: 500,
                    error: 'Address cannot exceed 500 characters.'
                },
                {
                    el: 'city',
                    max: 255,
                    error: 'City cannot exceed 255 characters.'
                },
                {
                    el: 'state',
                    max: 255,
                    error: 'State cannot exceed 255 characters.'
                },
                {
                    el: 'country',
                    max: 255,
                    error: 'Country cannot exceed 255 characters.'
                },
                {
                    el: 'pincode',
                    max: 20,
                    error: 'Pincode cannot exceed 20 characters.'
                },
            ];

            fields.forEach(f => {
                const input = document.getElementById(f.el);
                const errorElId = f.el + 'Error';
                let errorEl = document.getElementById(errorElId);

                if (!errorEl) {
                    // create error p if missing
                    errorEl = document.createElement('p');
                    errorEl.id = errorElId;
                    errorEl.className = 'text-red-500 text-sm mt-1 hidden';
                    errorEl.innerText = f.error;
                    input.insertAdjacentElement('afterend', errorEl);
                }

                input.classList.remove('border-red-500');
                errorEl.classList.add('hidden');

                if (input.value.length > f.max) {
                    input.classList.add('border-red-500');
                    errorEl.classList.remove('hidden');
                    valid = false;
                }
            });

            if (!valid) e.preventDefault();
        });
    </script>

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" id="overview">
        <div class="stats lg:col-span-3 grid grid-cols-2 lg:grid-cols-4 gap-4">
            @if (Auth::user()->role === 'student')
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Upcoming Fee (Monthly)</p>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹1000</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Due Date</p>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">12/8/2025</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Due Amount</p>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹95,622</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Amount Spend</p>
                <p class="text-3xl font-bold" style="color: var(--primary-color);">₹12,569</p>
            </div>
            @else
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Purchased Course</p>
                <p class="text-4xl font-bold" style="color: var(--primary-color);">2</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Purchased Notes</p>
                <p class="text-4xl font-bold" style="color: var(--primary-color);">32</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Purchased Quizzes</p>
                <p class="text-4xl font-bold" style="color: var(--primary-color);">15</p>
            </div>
            <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
                <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Amount Spend</p>
                <p class="text-4xl font-bold" style="color: var(--primary-color);">₹12,569</p>
            </div>
            @endif
        </div>
        <div class="md:col-span-2 p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
            <div class="education grid grid-cols-2 gap-4">
                <div class="about col-span-2 pb-4" style="border-bottom: 1px solid var(--border-color);">
                    <h3 class="text-xl font-semibold mb-2" style="color: var(--primary-color);">About</h3>
                    <p style="color: var(--text-color);">{{ Auth::user()->about ?? 'Not added yet.' }}</p>
                </div>
                <div class="contact-information col-span-2 pb-4" style="border-bottom: 1px solid var(--border-color);">
                    <h3 class="text-xl font-semibold mb-2" style="color: var(--primary-color);">Contact Information</h3>
                    <p class="mb-1" style="color: var(--secondary-text-color);"><i class="fas fa-envelope text-xs mr-2"></i> {{ Auth::user()->email ?? 'Not added yet.' }}</p>
                    <p class="mb-1" style="color: var(--secondary-text-color);"><i class="fas fa-phone text-xs mr-2"></i> {{ Auth::user()->phone ?? 'Not added yet.' }}</p>
                    <p class="mb-1" style="color: var(--secondary-text-color);"><i class="fas fa-map-marker-alt text-xs mr-2"></i> {{ Auth::user()->city ?? 'Not added yet.' }} {{ Auth::user()->state ?? '' }} {{ Auth::user()->country ?? '' }}</p>
                </div>
                <div class="social-icons col-span-2">
                    <h3 class="text-xl font-semibold mb-2" style="color: var(--primary-color);">Social Media</h3>
                    <div class="flex gap-4">
                        <a href="#" class="text-2xl" style="color: var(--text-color);"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl" style="color: var(--text-color);"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl" style="color: var(--text-color);"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-2xl" style="color: var(--text-color);"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
            <h3 class="text-xl font-semibold mb-2 pb-2" style="color: var(--primary-color); border-bottom: 1px solid var(--border-color);">Payment Histories</h3>
            <ul class="space-y-2">
                <li class="flex items-start pb-2" style="border-bottom: 1px solid var(--border-color);">
                    <i class="fas fa-credit-card text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p style="color: var(--text-color);"><strong>Payment:</strong> Course on Web Development</p>
                        <p class="text-sm" style="color: var(--secondary-text-color);">2 hours ago</p>
                    </div>
                </li>
                <li class="flex items-start pb-2" style="border-bottom: 1px solid var(--border-color);">
                    <i class="fas fa-credit-card text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p style="color: var(--text-color);"><strong>Payment:</strong> Course on Data Science</p>
                        <p class="text-sm" style="color: var(--secondary-text-color);">1 day ago</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection