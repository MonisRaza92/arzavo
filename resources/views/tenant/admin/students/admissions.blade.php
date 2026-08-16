@extends('layouts.admin')
@section('title', 'Student Admissions & Verification Applications')

@section('content')
<!-- TOP STATS CARDS -->
<div class="statics grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Applications</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-file-signature text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Pending Approvals</h2>
                <p class="text-2xl font-bold mt-1 text-amber-600">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-amber-500/10 border-rounded p-3"><i class="fas fa-clock text-lg text-amber-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Approved Students</h2>
                <p class="text-2xl font-bold mt-1 text-emerald-600">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-emerald-500/10 border-rounded p-3"><i class="fas fa-user-check text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Rejected</h2>
                <p class="text-2xl font-bold mt-1 text-rose-600">{{ $stats['rejected'] }}</p>
            </div>
            <div class="bg-rose-500/10 border-rounded p-3"><i class="fas fa-user-xmark text-lg text-rose-600"></i></div>
        </div>
    </div>
</div>

<!-- ACTIONS & FILTERS BAR -->
<div class="bg-primary border-rounded border-primary p-4 mb-4">
    <form method="GET" action="{{ route('admin.students.admissions') }}" class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2 grow max-w-3xl">
            <!-- Search -->
            <div class="relative min-w-[220px] grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name, email, or Aadhaar..." 
                       class="border text-xs py-2 px-3 pl-8 border-primary border-rounded bg-primary text-primary w-full input-focus">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-tertiary text-xs"></i>
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="border text-xs py-2 px-3 border-primary border-rounded bg-primary text-primary input-focus">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <!-- Category Filter -->
            <select name="category_id" onchange="this.form.submit()" class="border text-xs py-2 px-3 border-primary border-rounded bg-primary text-primary input-focus">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            @if(request()->anyFilled(['search', 'status', 'category_id']))
                <a href="{{ route('admin.students.admissions') }}" class="px-3 py-2 text-xs font-bold text-rose-600 bg-rose-500/10 border border-rose-500/20 border-rounded hover:bg-rose-500/20 transition">
                    Clear
                </a>
            @endif
        </div>

        <div>
            <button type="button" onclick="openManualAdmissionModal()" class="px-4 py-2 bg-invert text-invert border-rounded text-xs font-bold hover-invert flex items-center gap-1.5 transition">
                <i class="fa-solid fa-user-plus"></i> + Add Student Manually
            </button>
        </div>
    </form>
</div>

<!-- ADMISSIONS TABLE -->
<div class="bg-primary border-rounded border-primary overflow-hidden">
    <div class="px-4 py-3 border-bottom flex items-center justify-between">
        <h3 class="text-primary text-base font-bold flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-primary"></i> Admissions & Verification Applications Ledger
        </h3>
        <span class="text-xs text-tertiary">Showing {{ $admissions->total() }} applications</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3.5 pl-4 text-left">Applicant</th>
                    <th class="p-3.5 text-left">Academic Program</th>
                    <th class="p-3.5 text-left">Aadhaar & Marksheet</th>
                    <th class="p-3.5 text-left">Previous Institute</th>
                    <th class="p-3.5 text-left">Applied On</th>
                    <th class="p-3.5 text-center">Status</th>
                    <th class="p-3.5 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $adm)
                    <tr class="border-bottom hover-primary transition text-xs">
                        <!-- Applicant -->
                        <td class="p-3.5 pl-4 text-left">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-secondary text-primary border border-primary">
                                    {{ substr($adm->user->fname ?? 'U', 0, 1) }}{{ substr($adm->user->lname ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-primary leading-tight">
                                        {{ $adm->user->fname ?? 'Unknown' }} {{ $adm->user->lname ?? '' }}
                                    </div>
                                    <p class="text-[10px] text-tertiary font-mono">{{ $adm->user->email ?? 'N/A' }} • {{ $adm->user->number ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Academics (Category -> Class -> Subject) -->
                        <td class="p-3.5 text-left">
                            <div class="space-y-0.5">
                                <div class="font-bold text-primary">
                                    {{ $adm->classCourse->name ?? 'No Class Specified' }}
                                </div>
                                <div class="text-[10px] text-secondary flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded bg-secondary text-primary border border-primary text-[9px] font-bold">
                                        {{ $adm->academicCategory->name ?? 'General' }}
                                    </span>
                                    <span>{{ $adm->subject->name ?? 'All Subjects' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Aadhaar & Marksheet -->
                        <td class="p-3.5 text-left">
                            <div class="space-y-1">
                                <div class="font-mono text-primary font-bold text-[11px]">
                                    <i class="fa-solid fa-id-card text-tertiary mr-1"></i> {{ $adm->aadhaar_number ?: 'Not provided' }}
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    @if($adm->aadhaar_front)
                                        <a href="{{ asset('storage/' . $adm->aadhaar_front) }}" target="_blank" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                            Aadhaar Front <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                        </a>
                                    @endif
                                    @if($adm->aadhaar_back)
                                        <a href="{{ asset('storage/' . $adm->aadhaar_back) }}" target="_blank" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 hover:bg-indigo-500/20 transition">
                                            Aadhaar Back <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                        </a>
                                    @endif
                                    @if($adm->previous_marksheet)
                                        <a href="{{ asset('storage/' . $adm->previous_marksheet) }}" target="_blank" class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-500/10 text-purple-600 border border-purple-500/20 hover:bg-purple-500/20 transition">
                                            Marksheet <i class="fa-solid fa-file-pdf text-[8px]"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Previous Institute -->
                        <td class="p-3.5 text-left text-secondary">
                            {{ $adm->previous_school ?: 'N/A' }}
                        </td>

                        <!-- Date -->
                        <td class="p-3.5 text-secondary font-mono">
                            {{ $adm->applied_at ? $adm->applied_at->format('M d, Y') : $adm->created_at->format('M d, Y') }}
                        </td>

                        <!-- Status -->
                        <td class="p-3.5 text-center">
                            @if($adm->status === 'approved')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                    Approved
                                </span>
                            @elseif($adm->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 text-amber-600 border border-amber-500/20 animate-pulse">
                                    Pending Verification
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/10 text-rose-600 border border-rose-500/20">
                                    Rejected
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right pr-4">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($adm->status === 'pending')
                                    <form action="{{ route('admin.students.admissions.approve', $adm->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve admission and promote {{ $adm->user->fname ?? 'this applicant' }} to active Student?');">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-[11px] font-bold hover:bg-emerald-700 shadow-xs transition">
                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal({{ $adm->id }}, '{{ addslashes($adm->user->fname ?? 'Student') }}')" class="px-2.5 py-1 bg-secondary text-rose-600 border border-primary rounded-lg text-[11px] font-bold hover:bg-rose-500/10 transition">
                                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                                    </button>
                                @elseif($adm->status === 'approved' && $adm->user)
                                    <a href="{{ route('admin.admin-student-profile', $adm->user->username) }}" class="px-2.5 py-1 bg-secondary text-primary border border-primary rounded-lg text-[11px] font-bold hover:bg-hover-secondary transition">
                                        View Profile &rarr;
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-tertiary text-xs">
                            <i class="fa-solid fa-user-graduate text-2xl mb-2 block opacity-50"></i>
                            No admission applications found matching criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($admissions->hasPages())
        <div class="p-4 border-top bg-primary">
            {{ $admissions->links() }}
        </div>
    @endif
</div>

<!-- ============================================================ -->
<!-- MODAL: MANUAL ADMISSION / ADD STUDENT -->
<!-- ============================================================ -->
<div id="manualAdmissionModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-indigo-600"></i> Direct Student Admission & Registration
            </h3>
            <button type="button" onclick="closeManualAdmissionModal()" class="text-secondary hover:text-primary transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.students.admissions.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 text-xs">
            @csrf

            <!-- 1. ACADEMIC PROGRAM SELECTION -->
            <div>
                <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">1. Academic Program & Batch</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-secondary mb-1">Academic Category *</label>
                        <select id="adminCategorySelect" name="academic_category_id" required onchange="loadAdminClasses(this.value)" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Class / Course *</label>
                        <select id="adminClassSelect" name="class_id" required onchange="loadAdminSubjects(this.value)" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                            <option value="">-- Choose Class --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Subject / Stream</label>
                        <select id="adminSubjectSelect" name="subject_id" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                            <option value="">-- Choose Subject --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. STUDENT CREDENTIALS & INFO -->
            <div>
                <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">2. Student Profile & Credentials</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-secondary mb-1">First Name *</label>
                        <input type="text" name="fname" required placeholder="First Name" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Last Name *</label>
                        <input type="text" name="lname" required placeholder="Last Name" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Username *</label>
                        <input type="text" name="username" required placeholder="e.g. rahul_sharma" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Email Address *</label>
                        <input type="email" name="email" required placeholder="student@example.com" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Phone Number *</label>
                        <input type="text" name="number" required placeholder="10-digit mobile number" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Login Password *</label>
                        <input type="password" name="password" required placeholder="Min 6 characters" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Date of Birth</label>
                        <input type="date" name="dob" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Previous School / College</label>
                        <input type="text" name="previous_school" placeholder="Institute name" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                </div>
            </div>

            <!-- 3. KYC DOCUMENTS -->
            <div>
                <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">3. Aadhaar & Marksheet Uploads</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-secondary mb-1">Aadhaar Card Number</label>
                        <input type="text" name="aadhaar_number" placeholder="12-digit Aadhaar number" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Aadhaar Front (Image/PDF)</label>
                        <input type="file" name="aadhaar_front" accept="image/*,application/pdf" class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Aadhaar Back (Image/PDF)</label>
                        <input type="file" name="aadhaar_back" accept="image/*,application/pdf" class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-secondary mb-1">Previous Marksheet / Certificate (Image/PDF)</label>
                        <input type="file" name="previous_marksheet" accept="image/*,application/pdf" class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs">
                    </div>
                </div>
            </div>

            <!-- 4. OPTIONAL FEE PLAN SETUP -->
            <div>
                <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">4. Initial Fee Plan (Optional)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-secondary mb-1">Plan Fee Amount (₹)</label>
                        <input type="number" step="0.01" name="fee_amount" placeholder="e.g. 5000.00" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Billing Interval</label>
                        <select name="fee_plan_type" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                            <option value="monthly">Monthly Recurring</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="yearly">Yearly</option>
                            <option value="onetime">One-Time</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-secondary mb-1">Monthly Due Day (1-31)</label>
                        <input type="number" name="fee_due_day" value="10" min="1" max="31" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                    </div>
                </div>
            </div>

            <div class="pt-3 border-top flex justify-end gap-2 sticky bottom-0 bg-primary z-10 py-2">
                <button type="button" onclick="closeManualAdmissionModal()" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-invert text-invert border-rounded font-bold hover-invert shadow-sm transition">
                    Register & Admit Student
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: REJECT ADMISSION APPLICATION -->
<!-- ============================================================ -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-primary border border-primary border-rounded w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
            <h3 class="text-base font-bold text-rose-600 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Reject Admission Application
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-secondary hover:text-primary transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form id="rejectForm" action="" method="POST" class="p-5 space-y-4 text-xs">
            @csrf
            <p class="text-secondary">Rejecting admission for <strong class="text-primary" id="rejectStudentName">Student</strong>. You can provide feedback remarks for the student.</p>

            <div>
                <label class="block font-bold text-secondary mb-1">Rejection Remarks / Reason *</label>
                <textarea name="remarks" required rows="3" placeholder="e.g. Uploaded Aadhaar card is blurry. Please re-upload clear photos." class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2 border-top">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 text-white border-rounded font-bold hover:bg-rose-700 transition">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openManualAdmissionModal() {
    document.getElementById('manualAdmissionModal').classList.remove('hidden');
}

function closeManualAdmissionModal() {
    document.getElementById('manualAdmissionModal').classList.add('hidden');
}

function openRejectModal(id, studentName) {
    document.getElementById('rejectForm').action = "{{ url('admin/students/admissions') }}/" + id + "/reject";
    document.getElementById('rejectStudentName').textContent = studentName;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function loadAdminClasses(categoryId) {
    const classSelect = document.getElementById('adminClassSelect');
    const subjectSelect = document.getElementById('adminSubjectSelect');
    classSelect.innerHTML = '<option value="">Loading classes...</option>';
    subjectSelect.innerHTML = '<option value="">-- Choose Subject --</option>';

    if (!categoryId) {
        classSelect.innerHTML = '<option value="">-- Choose Class --</option>';
        return;
    }

    fetch('/academic/classes-by-category/' + categoryId)
        .then(res => res.json())
        .then(data => {
            classSelect.innerHTML = '<option value="">-- Choose Class --</option>';
            data.forEach(cls => {
                const opt = document.createElement('option');
                opt.value = cls.id;
                opt.textContent = cls.name;
                classSelect.appendChild(opt);
            });
        })
        .catch(err => {
            classSelect.innerHTML = '<option value="">-- Choose Class --</option>';
        });
}

function loadAdminSubjects(classId) {
    const subjectSelect = document.getElementById('adminSubjectSelect');
    subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';

    if (!classId) {
        subjectSelect.innerHTML = '<option value="">-- Choose Subject --</option>';
        return;
    }

    fetch('/academic/subjects-by-class/' + classId)
        .then(res => res.json())
        .then(data => {
            subjectSelect.innerHTML = '<option value="">-- Choose Subject --</option>';
            data.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                subjectSelect.appendChild(opt);
            });
        })
        .catch(err => {
            subjectSelect.innerHTML = '<option value="">-- Choose Subject --</option>';
        });
}
</script>
@endsection
