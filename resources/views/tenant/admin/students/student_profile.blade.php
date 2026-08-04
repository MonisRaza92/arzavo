@extends('layouts.admin')
@section('title', 'Student Profile - '.$studentProfile->fname.' '.$studentProfile->lname)
@section('content')
<div class="my-4">
    <!-- Profile Overview Card -->
    <div class="relative bg-primary border-primary border-rounded overflow-hidden mb-4">
        <!-- Banner -->
        <div class="w-full bg-tertiary relative">
            <img src="{{ asset($studentProfile->banner ?? 'images/tenant/background.jpg') }}" class="w-full h-full object-cover" style="aspect-ratio: 16 / 4;">
            <form action="{{ route('profile-banner-update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="banner" class="hidden" id="bannerInput" onchange="this.form.submit()">
                <label for="bannerInput" class="absolute top-2 right-2 text-xs border-rounded px-3 py-1.5 cursor-pointer bg-primary text-primary border-primary hover:bg-hover-secondary transition">
                    <i class="fas fa-camera"></i> Update Banner
                </label>
            </form>
        </div>

        <!-- Profile Image + Basic Info -->
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center">
            <div class="relative -mt-18 w-32 flex-shrink-0">
                @if ($studentProfile->profile_picture)
                    <img src="{{ asset($studentProfile->profile_picture) }}" class="w-32 h-32 border-rounded object-cover border border-primary">
                @else
                    <div class="font-bold text-6xl flex justify-center items-center w-32 h-32 border-rounded bg-tertiary text-primary border border-primary">
                        {{ strtoupper(substr($studentProfile->fname, 0, 1)) }}
                    </div>
                @endif
                <form action="{{ route('profile-picture-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_picture" class="hidden" id="profilePictureInput" onchange="this.form.submit()">
                    <label for="profilePictureInput" class="absolute bottom-1 right-1 text-xs border-rounded p-1.5 cursor-pointer bg-primary text-primary border-primary hover:bg-hover-secondary transition shadow">
                        <i class="fas fa-camera"></i>
                    </label>
                </form>
            </div>
            <div class="md:ml-6 mt-6 md:mt-0 flex justify-between items-center w-full relative">
                <div>
                    <h2 class="text-2xl font-bold text-primary">{{ $studentProfile->fname }} {{ $studentProfile->lname }}</h2>
                    <p class="text-sm text-tertiary font-mono">{{ $studentProfile->username }}</p>
                </div>
                <button onclick="document.getElementById('profileEditForm').classList.remove('hidden')" 
                        class="absolute -top-19 md:top-0 md:relative right-0 border-rounded border-primary bg-primary text-primary hover:bg-hover-secondary px-4 py-2 text-xs font-semibold flex justify-center items-center gap-1.5 transition">
                    Edit Profile <i class="fas fa-pencil-alt text-xs"></i>
                </button>
            </div>
        </div>
        <div class="info px-6 pb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Headline</span>
                    <p class="text-sm text-primary mt-0.5 font-medium">{{ $studentProfile->headline ?: 'Not added yet.' }}</p>
                </div>
                <div>
                    <span class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Personal Details</span>
                    <p class="text-xs text-secondary mt-1 flex items-center gap-2">
                        <span><i class="fas fa-map-marker-alt text-tertiary"></i> {{ $studentProfile->city ?: 'No City' }}</span>
                        <span>•</span>
                        <span><i class="fas fa-calendar-alt text-tertiary"></i> Joined {{ $studentProfile->created_at->format('F j, Y') }}</span>
                    </p>
                    <p class="text-xs text-secondary mt-1 flex items-center gap-2">
                        <span><i class="fas fa-chalkboard text-tertiary"></i> {{ $studentProfile->class->name ?? 'No Class' }}</span>
                        <span>•</span>
                        <span><i class="fas fa-video text-tertiary"></i> {{ $studentProfile->subject->name ?? 'No Subject' }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="tabs mt-2 px-6 mb-4 border-top">
            <ul class="flex whitespace-nowrap overflow-x-auto scrollbar py-3 gap-2">
                <li><a href="#overview" class="tab-link px-4 py-2 border-rounded text-xs font-bold bg-invert text-invert">Overview</a></li>
                <li><a href="#" class="tab-link px-4 py-2 border-rounded text-xs font-bold bg-primary text-primary border-primary hover:bg-hover-secondary transition">Purchases</a></li>
                <li><a href="#" class="tab-link px-4 py-2 border-rounded text-xs font-bold bg-primary text-primary border-primary hover:bg-hover-secondary transition">Posts</a></li>
                <li><a href="#" class="tab-link px-4 py-2 border-rounded text-xs font-bold bg-primary text-primary border-primary hover:bg-hover-secondary transition">Settings</a></li>
                <li><a href="#" class="tab-link px-4 py-2 border-rounded text-xs font-bold bg-primary text-primary border-primary hover:bg-hover-secondary transition">Payments</a></li>
            </ul>
        </div>
    </div>

    <!-- Profile Edit Modal Form -->
    <div id="profileEditForm" class="hidden fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 h-full md:h-5/6 w-full md:w-2/3 z-999 px-6 pb-6 border-rounded shadow-lg bg-primary border-primary overflow-y-auto scrollbar">
        <div class="flex justify-between items-center sticky top-0 py-4 bg-primary border-bottom z-10">
            <h3 class="text-lg font-bold text-primary">Edit Profile Info</h3>
            <button type="button" class="text-tertiary hover:text-primary transition text-lg" onclick="document.getElementById('profileEditForm').classList.add('hidden')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="profileForm" action="{{ route('admin.admin-student-profile-info-update', $studentProfile->id) }}" method="POST" class="space-y-6 mt-4">
            @csrf

            <!-- BASIC INFORMATION -->
            <div>
                <h4 class="text-xs uppercase font-extrabold text-tertiary tracking-wider mb-3">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fname" class="block text-xs font-semibold text-secondary mb-1">First Name *</label>
                        <input type="text" name="fname" id="fname" value="{{ $studentProfile->fname }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" required>
                    </div>
                    <div>
                        <label for="lname" class="block text-xs font-semibold text-secondary mb-1">Last Name *</label>
                        <input type="text" name="lname" id="lname" value="{{ $studentProfile->lname }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" required>
                    </div>
                    <div>
                        <label for="username" class="block text-xs font-semibold text-secondary mb-1">Username *</label>
                        <input type="text" name="username" id="username" value="{{ $studentProfile->username }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" required>
                    </div>
                    <div>
                        <label for="dob" class="block text-xs font-semibold text-secondary mb-1">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="{{ $studentProfile->dob }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                    <div class="md:col-span-2">
                        <label for="headline" class="block text-xs font-semibold text-secondary mb-1">Headline</label>
                        <input type="text" name="headline" id="headline" value="{{ $studentProfile->headline }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" placeholder="e.g., Science Stream Student">
                    </div>
                    <div class="md:col-span-2">
                        <label for="about" class="block text-xs font-semibold text-secondary mb-1">About Student</label>
                        <textarea name="about" id="about" class="w-full border-rounded border-primary bg-primary text-primary p-3 text-xs" rows="3" placeholder="A brief description...">{{ $studentProfile->about }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ACADEMICS -->
            <div class="border-top pt-4">
                <h4 class="text-xs uppercase font-extrabold text-tertiary tracking-wider mb-3">Academic Mapping</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="class" class="block text-xs font-semibold text-secondary mb-1">Assigned Class</label>
                        <select name="class_id" id="class" class="w-full border-rounded border-primary bg-primary text-primary px-2.5 py-2 text-xs">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ $studentProfile->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subject" class="block text-xs font-semibold text-secondary mb-1">Assigned Subject</label>
                        <select name="subject_id" id="subject" class="w-full border-rounded border-primary bg-primary text-primary px-2.5 py-2 text-xs">
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $studentProfile->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- CONTACT -->
            <div class="border-top pt-4">
                <h4 class="text-xs uppercase font-extrabold text-tertiary tracking-wider mb-3">Contact Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-secondary mb-1">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ $studentProfile->email }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" required>
                    </div>
                    <div>
                        <label for="number" class="block text-xs font-semibold text-secondary mb-1">Phone Number *</label>
                        <input type="text" name="number" id="number" value="{{ $studentProfile->number }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" required>
                    </div>
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="border-top pt-4">
                <h4 class="text-xs uppercase font-extrabold text-tertiary tracking-wider mb-3">Postal Address</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-secondary mb-1">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ $studentProfile->address }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label for="city" class="block text-xs font-semibold text-secondary mb-1">City</label>
                        <input type="text" name="city" id="city" value="{{ $studentProfile->city }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label for="state" class="block text-xs font-semibold text-secondary mb-1">State</label>
                        <input type="text" name="state" id="state" value="{{ $studentProfile->state }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label for="country" class="block text-xs font-semibold text-secondary mb-1">Country</label>
                        <input type="text" name="country" id="country" value="{{ $studentProfile->country }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label for="pincode" class="block text-xs font-semibold text-secondary mb-1">Pincode</label>
                        <input type="text" name="pincode" id="pincode" value="{{ $studentProfile->pincode }}" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="flex justify-between items-center border-top pt-4 sticky bottom-0 bg-primary z-10 py-3">
                <span class="text-xs text-tertiary">* Mandatory fields</span>
                <button type="submit" class="bg-invert text-invert font-bold px-4 py-2 border-rounded text-xs uppercase transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Page Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" id="overview">
        <!-- Overview & Fees Info -->
        <div class="lg:col-span-2 p-6 bg-primary border-primary border-rounded">
            <div class="flex justify-between items-center border-bottom pb-3 mb-4">
                <h3 class="text-lg font-bold text-primary">Academic Overview</h3>
                <button onclick="document.getElementById('feePlanForm').classList.remove('hidden')" 
                        class="border-rounded border-primary bg-primary text-primary hover:bg-hover-secondary px-3 py-1.5 text-xs font-bold transition">
                    Setup Fee Plan
                </button>
                
                <!-- Fee Plan Modal (Hidden by default) -->
                <div id="feePlanForm" class="hidden fixed left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 h-auto w-full md:w-1/2 z-999 px-6 pb-6 border-rounded shadow-lg bg-primary border-primary">
                    <div class="flex justify-between items-center py-4 border-bottom">
                        <h3 class="text-lg font-bold text-primary">Setup Fee Plan Structure</h3>
                        <button type="button" class="text-tertiary hover:text-primary transition text-lg" onclick="document.getElementById('feePlanForm').classList.add('hidden')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="feeForm" action="{{ route('admin.admin-student-fee-update', $studentProfile->id) }}" method="POST" class="space-y-4 mt-4 text-left">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $studentProfile->id }}">
                        
                        <div>
                            <label for="planType" class="block text-xs font-semibold text-secondary mb-1">Billing Interval</label>
                            <select id="planType" name="plan_type" class="w-full border-rounded border-primary bg-primary text-primary px-2.5 py-2 text-xs">
                                <option value="monthly">Monthly Recurring</option>
                                <option value="yearly">Yearly Recurring</option>
                                <option value="onetime">One-Time Fee Payment</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount" class="block text-xs font-semibold text-secondary mb-1">Amount (in ₹) *</label>
                            <input type="number" id="amount" name="amount" required class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" placeholder="e.g. 5000">
                        </div>
                        <div>
                            <label for="startDate" class="block text-xs font-semibold text-secondary mb-1">Start Date *</label>
                            <input type="date" id="startDate" name="start_date" required class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                        </div>
                        <div>
                            <label for="dueDay" class="block text-xs font-semibold text-secondary mb-1">Due Day of Month (1 - 31)</label>
                            <input type="number" id="dueDay" name="due_day" min="1" max="31" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs" placeholder="e.g. 10">
                        </div>
                        <div>
                            <label for="endDate" class="block text-xs font-semibold text-secondary mb-1">End Date (Optional)</label>
                            <input type="date" id="endDate" name="end_date" class="w-full border-rounded border-primary bg-primary text-primary px-3 py-2 text-xs">
                        </div>

                        <div class="flex justify-between items-center border-top pt-4 mt-6">
                            <span class="text-xs text-tertiary">Review info before saving.</span>
                            <button type="submit" class="bg-invert text-invert font-bold px-4 py-2 border-rounded text-xs uppercase transition">
                                Save Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 border-rounded bg-tertiary border-primary">
                    <p class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Upcoming Fee ({{ ucfirst($feePlan->plan_type ?? 'none') }})</p>
                    <p class="text-2xl font-bold mt-1 text-primary">₹{{ number_format($feePlan->amount ?? 0, 2) }}</p>
                </div>
                <div class="p-4 border-rounded bg-tertiary border-primary">
                    <p class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Due Date of Cycle</p>
                    <p class="text-2xl font-bold mt-1 text-primary">
                        {{ $feePlan && $feePlan->due_day ? 'Every '.$feePlan->due_day.'th' : 'Not Set' }}
                    </p>
                </div>
                <div class="p-4 border-rounded bg-tertiary border-primary">
                    <p class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Total Dues Invoiced</p>
                    <p class="text-2xl font-bold mt-1 text-primary">
                        ₹{{ number_format($studentProfile->feePlans->sum('amount'), 2) }}
                    </p>
                </div>
                <div class="p-4 border-rounded bg-tertiary border-primary">
                    <p class="text-[10px] text-tertiary uppercase tracking-wider font-bold">Total Amount Paid</p>
                    <p class="text-2xl font-bold mt-1 text-primary">
                        ₹{{ number_format($studentProfile->feePayments->where('status', 'paid')->sum('amount_paid'), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Histories -->
        <div class="p-6 bg-primary border-primary border-rounded flex flex-col">
            <h3 class="text-md font-bold text-primary border-bottom pb-3 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-tertiary"></i> Payment History Log
            </h3>
            <ul class="space-y-3.5 flex-grow overflow-y-auto max-h-[300px] scrollbar">
                @forelse($studentProfile->feePayments as $payment)
                    <li class="flex items-start gap-3 pb-3 border-bottom text-xs">
                        <i class="fa-solid fa-receipt text-tertiary mt-0.5"></i>
                        <div class="space-y-0.5 grow">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-primary">₹{{ number_format($payment->amount_paid, 2) }}</span>
                                <span class="px-2 py-0.5 rounded-[3px] text-[9px] font-bold border uppercase
                                      {{ $payment->status === 'paid' ? 'bg-invert text-invert border-primary' : 'bg-tertiary text-primary border-primary' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <p class="text-secondary text-[10px] font-mono">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'Pending' }} via {{ strtoupper($payment->payment_method ?: 'N/A') }}</p>
                        </div>
                    </li>
                @empty
                    <li class="py-6 text-center text-tertiary text-xs">
                        No previous payment invoices found.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
