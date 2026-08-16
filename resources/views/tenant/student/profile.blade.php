@extends('layouts.student')
@section('title', 'Student Profile & Info - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-emerald-600"></i> Student Profile & KYC Documents
            </h1>
            <p class="text-xs text-secondary mt-0.5">Manage your personal details, academic batch info, and verification documents.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: STUDENT PROFILE CARD -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs h-fit">
            <div class="flex flex-col items-center text-center space-y-3">
                <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-600 border-2 border-emerald-500/20 flex items-center justify-center font-bold text-2xl">
                    {{ substr($user->fname ?? 'S', 0, 1) }}{{ substr($user->lname ?? 'T', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-primary">{{ $user->fname }} {{ $user->lname }}</h3>
                    <p class="text-xs text-secondary font-mono">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase tracking-wider">
                        Enrolled Student
                    </span>
                </div>
            </div>

            <!-- ACADEMIC & KYC LEDGER -->
            <div class="pt-3 border-top space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-tertiary">Roll Number:</span>
                    <span class="text-primary font-mono font-bold">{{ $user->username }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Academic Category:</span>
                    <span class="text-primary font-bold">{{ $category->name ?? 'Null' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Enrolled Class:</span>
                    <span class="text-primary font-bold">{{ $classCourse->name ?? 'Null' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Subject / Stream:</span>
                    <span class="text-primary font-bold">{{ $subject->name ?? 'Null' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Aadhaar Number:</span>
                    <span class="text-primary font-mono font-bold">{{ $user->aadhaar_number ?: 'Null' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Previous School:</span>
                    <span class="text-primary font-medium">{{ $user->previous_school ?: 'Null' }}</span>
                </div>
            </div>

            <!-- KYC ATTACHMENTS PREVIEW -->
            <div class="pt-3 border-top space-y-2">
                <h4 class="text-[11px] font-bold text-primary uppercase tracking-wider">Uploaded Documents</h4>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center justify-between p-2 rounded bg-secondary/30 border border-primary">
                        <span class="text-secondary font-medium">Aadhaar Front:</span>
                        @if($user->aadhaar_front)
                            <a href="{{ asset('storage/' . $user->aadhaar_front) }}" target="_blank" class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                View File <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </a>
                        @else
                            <span class="text-tertiary font-mono text-[10px]">Null</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between p-2 rounded bg-secondary/30 border border-primary">
                        <span class="text-secondary font-medium">Aadhaar Back:</span>
                        @if($user->aadhaar_back)
                            <a href="{{ asset('storage/' . $user->aadhaar_back) }}" target="_blank" class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                View File <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </a>
                        @else
                            <span class="text-tertiary font-mono text-[10px]">Null</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between p-2 rounded bg-secondary/30 border border-primary">
                        <span class="text-secondary font-medium">Marksheet:</span>
                        @if($user->previous_marksheet)
                            <a href="{{ asset('storage/' . $user->previous_marksheet) }}" target="_blank" class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/10 text-purple-600 border border-purple-500/20 hover:bg-purple-500/20 transition">
                                View File <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </a>
                        @else
                            <span class="text-tertiary font-mono text-[10px]">Null</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: PROFILE & KYC EDIT FORM -->
        <div class="lg:col-span-2 p-5 sm:p-6 border-rounded bg-primary border-primary space-y-6 shadow-xs">
            <h3 class="text-sm font-bold text-primary border-bottom pb-3">Update Personal Details & KYC Documents</h3>

            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- PERSONAL DETAILS -->
                <div>
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-2">1. Personal Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">First Name *</label>
                            <input type="text" name="fname" value="{{ old('fname', $user->fname) }}" required
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Last Name *</label>
                            <input type="text" name="lname" value="{{ old('lname', $user->lname) }}" required
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Student Email (Read-only)</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full p-2.5 bg-secondary border-primary border-rounded text-xs text-tertiary cursor-not-allowed">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Mobile Number</label>
                            <input type="text" name="number" value="{{ old('number', $user->number) }}" placeholder="+91 XXXXX XXXXX"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', $user->dob) }}"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Previous School / College</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school', $user->previous_school) }}" placeholder="Previous Institute Name"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                    </div>
                </div>

                <!-- KYC & AADHAAR DOCUMENTS UPLOAD -->
                <div class="pt-3 border-top">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider mb-2">2. Aadhaar & Marksheet Documents</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-primary block mb-1">Aadhaar Card Number</label>
                            <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number', $user->aadhaar_number) }}" placeholder="12-digit Aadhaar Number"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Upload / Update Aadhaar Front (Image/PDF)</label>
                            <input type="file" name="aadhaar_front" accept="image/*,application/pdf"
                                   class="w-full p-1.5 bg-primary border-primary border-rounded text-xs text-primary file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Upload / Update Aadhaar Back (Image/PDF)</label>
                            <input type="file" name="aadhaar_back" accept="image/*,application/pdf"
                                   class="w-full p-1.5 bg-primary border-primary border-rounded text-xs text-primary file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs font-semibold text-primary block mb-1">Upload / Update Previous Marksheet (Image/PDF)</label>
                            <input type="file" name="previous_marksheet" accept="image/*,application/pdf"
                                   class="w-full p-1.5 bg-primary border-primary border-rounded text-xs text-primary file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                    </div>
                </div>

                <!-- RESIDENTIAL ADDRESS -->
                <div class="pt-3 border-top space-y-3">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider">3. Residential Address Details</h4>
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">Home Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="House No., Street, Area"
                               class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="City"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $user->state) }}" placeholder="State"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $user->pincode) }}" placeholder="Pincode"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                    </div>
                </div>

                <!-- PASSWORD UPDATE -->
                <div class="space-y-3 pt-3 border-top">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider">4. Change Account Password (Optional)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Min. 8 characters"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                   class="w-full p-2.5 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition">
                        Save Profile & Verification Details
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
