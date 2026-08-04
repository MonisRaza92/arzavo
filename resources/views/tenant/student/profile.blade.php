@extends('layouts.student')
@section('title', 'Student Profile & Info - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-emerald-500"></i> Student Profile & Guardian Info
            </h1>
            <p class="text-xs text-secondary mt-0.5">Manage your personal details, academic class info, and password.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- STUDENT CARD -->
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
            <div class="pt-3 border-top space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-tertiary">Roll Number:</span>
                    <span class="text-primary font-mono font-bold">STU-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Enrolled Class:</span>
                    <span class="text-primary font-bold">{{ $classCourse ? $classCourse->name : 'Class 11th' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">Primary Subject:</span>
                    <span class="text-primary font-bold">{{ $subject ? $subject->name : 'Physics Batch' }}</span>
                </div>
            </div>
        </div>

        <!-- PROFILE EDIT FORM -->
        <div class="lg:col-span-2 p-5 sm:p-6 border-rounded bg-primary border-primary space-y-6 shadow-xs">
            <h3 class="text-sm font-bold text-primary border-bottom pb-3">Update Personal & Guardian Info</h3>

            <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">First Name</label>
                        <input type="text" name="fname" value="{{ old('fname', $user->fname) }}" required
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">Last Name</label>
                        <input type="text" name="lname" value="{{ old('lname', $user->lname) }}" required
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">Student Email (Read-only)</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full p-3 bg-hover-secondary border-primary border-rounded text-xs text-tertiary cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">Student Mobile Number</label>
                        <input type="text" name="number" value="{{ old('number', $user->number) }}" placeholder="+91 XXXXX XXXXX"
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider">Residential Address Details</h4>
                    <div>
                        <label class="text-xs font-semibold text-primary block mb-1">Home Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="House No., Street, Area"
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="City"
                                   class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $user->state) }}" placeholder="State"
                                   class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $user->pincode) }}" placeholder="Pincode"
                                   class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-top">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider">Change Student Account Password</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">New Password (Optional)</label>
                            <input type="password" name="password" placeholder="Min. 8 characters"
                                   class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-primary block mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                   class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition">
                        Save Student Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
