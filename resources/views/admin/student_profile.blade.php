@extends('layouts.admin')
@section('title', 'Student Profile - '.$studentProfile->fname.' '.$studentProfile->lname)
@section('content')
<div class="my-4">
    <!-- Profile Overview -->
    <div class="relative rounded-md overflow-hidden mb-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <!-- Banner -->
        <div class="w-full bg-gray-200 relative">
            <img src="{{ asset($studentProfile->banner ?? 'images/banner.webp') }}" class="w-full h-full object-cover" style="aspect-ratio: 16 / 4;">
            <form action="{{ route('profile-banner-update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="banner" class="hidden" id="bannerInput" onchange="this.form.submit()">
                <label for="bannerInput" class="absolute top-2 right-2 text-xs rounded-md p-2 cursor-pointer" style="background-color: var(--background-color); color: var(--text-color); border: 1px solid var(--border-color);"><i class="fas fa-camera"></i> Update</label>
            </form>
        </div>

        <!-- Profile Image + Basic Info -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center">
            <div class="relative -mt-18 w-32 flex-shrink-0">
                @if ($studentProfile->profile_picture)
                <img src="{{ asset($studentProfile->profile_picture) }}"
                    class="w-32 h-32 rounded-md object-cover">
                @else
                <h2 class="font-bold text-8xl flex justify-center items-center w-32 h-32 rounded-md"
                    style="background-color: var(--primary-color); color: var(--text-light);">
                    {{ strtoupper(substr($studentProfile->fname, 0, 1)) }}
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
                    <h2 class="text-2xl font-bold">{{ $studentProfile->fname }} {{ $studentProfile->lname }}</h2>
                    <p class="text-sm" style="color: var(--secondary-text-color);">{{ $studentProfile->username }}</p>
                </div>
                <button onclick="document.getElementById('profileEditForm').classList.remove('hidden')" class="absolute default-button -top-19 md:top-0 md:relative right-0 rounded-md px-4 py-2  flex justify-center items-center" title="Update Profile">Edit Profile <i class="fas fa-pencil-alt text-lg ml-2"></i></button>
            </div>
        </div>
        <div class="info px-6 pb-4">
            <p class="text-xs" style="color: var(--secondary-text-color);">Headline</p>
            <p class="text-lg" style="color: var(--text-color);">{{ $studentProfile->headline ?? 'Not added yet.' }}</p>
            <p class="text-sm mt-2" style="color: var(--secondary-text-color);"><i class="fas fa-map-marker-alt text-xs -ml-0.5"></i> {{ $studentProfile->city ?? 'Not Updated' }} {{ $studentProfile->state ?? '' }} <i class="fas fa-calendar-alt text-xs ml-2"></i> {{ $studentProfile->created_at->format('F j, Y') }}</p>
            <p class="text-sm mt-2" style="color: var(--text-color);"><i class="fas fa-chalkboard text-xs -ml-0.5"></i> {{ $studentProfile->class->name ?? 'Not Assigned' }} <i class="fas fa-video text-xs ml-2"></i> {{ $studentProfile->subject->name ?? 'Not Assigned' }}</p>
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

        <form id="profileForm" action="{{ route('admin-student-profile-info-update',$studentProfile->id) }}" method="POST" class="space-y-8">
            @csrf

            <!-- BASIC INFORMATION -->
            <div class="px-4">
                <h4 class="text-lg font-semibold mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fname" class="block text-sm font-medium" style="color: var(--secondary-text-color);">First Name *</label>
                        <input type="text" name="fname" id="fname" value="{{ $studentProfile->fname }}" maxlength="255" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                    </div>
                    <div>
                        <label for="lname" class="block text-sm font-medium" style="color: var(--secondary-text-color);">Last Name *</label>
                        <input type="text" name="lname" id="lname" value="{{ $studentProfile->lname }}" maxlength="255" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Username *</label>
                        <input type="text" name="username" id="username" value="{{ $studentProfile->username }}" maxlength="100" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" required>
                        <p id="usernameError" class="text-red-500 text-sm mt-1 hidden">Username cannot exceed 100 characters.</p>
                        <p class="text-xs mt-1" style="color: var(--secondary-text-color);">Max 100 characters</p>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium" style="color: var(--secondary-text-color);">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="{{ $studentProfile->dob }}" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div class="md:col-span-2">
                        <label for="headline" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Headline</label>
                        <input type="text" name="headline" id="headline" value="{{ $studentProfile->headline }}" maxlength="100" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" placeholder="e.g., Creative Designer | Marketing Specialist">
                        <p id="headlineError" class="text-red-500 text-sm mt-1 hidden">Headline cannot exceed 100 characters.</p>
                        <p class="text-xs mt-1" style="color: var(--secondary-text-color);">Max 100 characters</p>
                    </div>
                    <div class="md:col-span-2">
                        <label for="about" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">About</label>
                        <textarea name="about" id="about" maxlength="300" class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);" rows="4" placeholder="Write about yourself (max 300 characters)">{{ $studentProfile->about }}</textarea>
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
                        <select name="class_id" id="class" value="{{ $studentProfile->class }}"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ $studentProfile->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Subject</label>
                        <select name="subject_id" id="subject" value="{{ $studentProfile->subject }}"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $studentProfile->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
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
                        <input type="email" name="email" id="email" value="{{ $studentProfile->email }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);"
                            required>
                    </div>
                    <div>
                        <label for="number" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Phone Number *</label>
                        <input type="text" name="number" id="number" value="{{ $studentProfile->number }}"
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
                        <input type="text" name="address" id="address" value="{{ $studentProfile->address }}"
                            maxlength="500"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);"
                            placeholder="Enter full address">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">City</label>
                        <input type="text" name="city" id="city" value="{{ $studentProfile->city }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">State</label>
                        <input type="text" name="state" id="state" value="{{ $studentProfile->state }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Country</label>
                        <input type="text" name="country" id="country" value="{{ $studentProfile->country }}"
                            maxlength="255"
                            class="w-full rounded-md p-2" style="border: 1px solid var(--border-color);">
                    </div>
                    <div>
                        <label for="pincode" class="block text-sm font-medium mb-1" style="color: var(--secondary-text-color);">Pincode</label>
                        <input type="text" name="pincode" id="pincode" value="{{ $studentProfile->pincode }}"
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
        <div class="md:col-span-2 p-4 rounded-md" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold mb-4" style="color: var(--primary-color);">Overview</h3>
                <button class="default-button p-2! mb-4 text-sm" onclick="document.getElementById('feePlanForm').classList.toggle('hidden')">Add/Edit Fee</button>
                <!-- Fee Plan Form (Hidden by default) -->
                <div id="feePlanForm" class="hidden fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 h-full md:h-auto w-full md:w-2/4 z-10000 px-4 rounded-md shadow-2xl overflow-y-auto scrollbar"
                    style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">

                    <div class="flex justify-between items-start sticky top-0 p-4 pt-6" style="background-color: var(--secondary-background);">
                        <h3 class="text-2xl font-bold" style="color: var(--primary-color);">Add/Edit Fee Plan</h3>
                        <button type="button" class="close-btn  text-xl" onclick="document.getElementById('feePlanForm').classList.add('hidden')"><i class="fas fa-times"></i></button>
                    </div>

                    <form id="feeForm" action="{{ route('admin-student-fee-update',$studentProfile->id) }}" method="POST" class="space-y-2 p-4">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $studentProfile->id }}">
                        <div class="plan-type flex flex-col">
                            <label for="planType" class="text-sm" style="color: var(--secondary-text-color); ">Plan Type</label>
                            <select id="planType" name="plan_type" class="form-select p-2" style="border: 1px solid var(--border-color);">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="onetime">OneTime</option>
                            </select>
                        </div>
                        <div class="amount flex flex-col">
                            <label for="amount" class="text-sm" style="color: var(--secondary-text-color); ">Amount (in ₹)</label>
                            <input type="number" id="amount" name="amount" class="form-input p-2" placeholder="Enter amount" style="border: 1px solid var(--border-color);" required>
                        </div>
                        <div class="start-date flex flex-col">
                            <label for="startDate" class="text-sm" style="color: var(--secondary-text-color); ">Start Date</label>
                            <input type="date" id="startDate" name="start_date" class="form-input p-2" style="border: 1px solid var(--border-color);" required>
                        </div>
                        <div class="due-day flex flex-col">
                            <label for="dueDay" class="text-sm" style="color: var(--secondary-text-color); ">Due Day (for Monthly/Yearly plans)</label>
                            <input type="number" id="dueDay" name="due_day" class="form-input p-2" min="1" max="31" placeholder="Enter due day" style="border: 1px solid var(--border-color);">
                        </div>
                        <div class="end-date flex flex-col">
                            <label for="endDate" class="text-sm" style="color: var(--secondary-text-color); ">End Date (Optional)</label>
                            <input type="date" id="endDate" name="end_date" class="form-input p-2" style="border: 1px solid var(--border-color);">
                        </div>
                        <div class="button flex justify-between items-center sticky bottom-0 py-4" style="background-color: var(--secondary-background);">
                            <p class="text-sm" style="color: var(--secondary-text-color);">Note: Please ensure all details are correct before saving.</p>
                            <button type="submit" class="default-button mt-4">Save Fee Plan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="education grid grid-cols-2 gap-4">
                <div class="p-4 rounded-md" style="border: 1px solid var(--border-color);">
                    <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Upcoming Fee ({{ $feePlan->plan_type ?? '' }})</p>
                    <p class="text-3xl font-bold" style="color: var(--primary-color);">{{ $feePlan->amount ?? 'Not Assigned' }}</p>
                </div>
                <div class="p-4 rounded-md" style="border: 1px solid var(--border-color);">
                    <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Due Date</p>
                    <p class="text-3xl font-bold" style="color: var(--primary-color);">Every {{ $feePlan->due_day ?? 'Not Assigned' }}th</p>
                </div>
                <div class="p-4 rounded-md" style="border: 1px solid var(--border-color);">
                    <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Due Amount</p>
                    <p class="text-3xl font-bold" style="color: var(--primary-color);">₹95,622</p>
                </div>
                <div class="p-4 rounded-md" style="border: 1px solid var(--border-color);">
                    <p class="text-sm font-semibold mb-2" style="color: var(--secondry-text-color);">Amount Spend</p>
                    <p class="text-3xl font-bold" style="color: var(--primary-color);">₹12,569</p>
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

<!-- JS for Tabs -->
<!-- <script>
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active-li'));
            this.classList.add('active-li');

            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelector(this.getAttribute('href')).classList.remove('hidden');
        });
    });
</script> -->
@endsection