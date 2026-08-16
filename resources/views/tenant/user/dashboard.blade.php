@extends('layouts.user')
@section('title', 'User Dashboard - Customer Portal')

@section('content')
    <!-- WELCOME BANNER CARD -->
    <div class="mb-4 p-4 sm:p-6 border-rounded bg-primary border-primary shadow-xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 rounded text-[10px] bg-blue-500/10 text-blue-600 font-bold border border-blue-500/20 uppercase tracking-wider">
                    Customer Account
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight mt-1.5 flex items-center gap-2">
                    Welcome back, {{ $user->fname ?? 'Customer' }}! 👋
                </h1>
                <p class="text-xs text-secondary mt-1">
                    Manage your purchases, downloadable study materials, and academic admission applications.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if($user->admission_status === 'pending_approval')
                    <span class="px-3 py-2 bg-amber-500/10 text-amber-600 border border-amber-500/20 border-rounded font-bold text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i> Admission Pending Approval
                    </span>
                @else
                    <button onclick="openAdmissionModal()" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white border-rounded font-bold text-xs hover:opacity-90 shadow-sm flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-graduation-cap"></i> Apply for Student Admission
                    </button>
                @endif
                <a href="{{ route('user.orders') }}" class="px-4 py-2.5 bg-secondary text-primary border border-primary border-rounded font-bold text-xs hover:bg-hover-secondary transition">
                    View Orders
                </a>
            </div>
        </div>
    </div>

    <!-- ADMISSION APPLICATION STATUS BANNER -->
    @if($user->admission_status === 'pending_approval')
        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-start gap-3.5 text-xs">
            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-600 flex items-center justify-center text-base shrink-0 mt-0.5">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div class="space-y-1 grow">
                <h4 class="font-bold text-amber-700 text-sm">Admission Application Submitted (Verification Pending)</h4>
                <p class="text-amber-800 dark:text-amber-300 leading-relaxed">
                    Your student enrollment application has been submitted to the academic administration. Our team is verifying your Aadhaar card and previous academic marksheet. Once approved, your student dashboard and enrolled live batches will be activated automatically!
                </p>
                <div class="pt-1 flex items-center gap-3 text-[11px] font-mono text-amber-700">
                    <span>Applied on: {{ $latestAdmission && $latestAdmission->applied_at ? $latestAdmission->applied_at->format('M d, Y') : date('M d, Y') }}</span>
                    <span>•</span>
                    <span>Target Class: {{ $latestAdmission->classCourse->name ?? 'Standard' }}</span>
                </div>
            </div>
        </div>
    @elseif($user->admission_status === 'rejected')
        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-start justify-between gap-3.5 text-xs">
            <div class="flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-600 flex items-center justify-center text-base shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-rose-700 text-sm">Admission Application Requires Revision</h4>
                    <p class="text-rose-800 dark:text-rose-300 leading-relaxed">
                        {{ $latestAdmission->admin_remarks ?? 'Your submitted documents could not be verified. Please re-upload clear Aadhaar card images and academic marksheets.' }}
                    </p>
                </div>
            </div>
            <button onclick="openAdmissionModal()" class="px-3.5 py-1.5 bg-rose-600 text-white rounded-lg font-bold text-xs hover:bg-rose-700 shrink-0 transition">
                Re-apply
            </button>
        </div>
    @endif

    <!-- STATS CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL PURCHASES</span>
                <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-600 flex items-center justify-center text-sm border border-blue-500/20">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $totalOrdersCount }}</div>
            <p class="text-[11px] text-secondary">Completed order transactions</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">TOTAL SPENT</span>
                <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm border border-emerald-500/20">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">₹{{ number_format($totalSpent, 2) }}</div>
            <p class="text-[11px] text-secondary">Total amount paid</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">DIGITAL BOOKS</span>
                <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-600 flex items-center justify-center text-sm border border-purple-500/20">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $orders->where('payment_status', 'paid')->count() }}</div>
            <p class="text-[11px] text-secondary">Accessible E-books & PDFs</p>
        </div>

        <div class="p-4 border-rounded bg-primary border-primary space-y-2 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-wider">MY INQUIRIES</span>
                <div class="w-8 h-8 rounded bg-amber-500/10 text-amber-600 flex items-center justify-center text-sm border border-amber-500/20">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary font-mono">{{ $inquiriesCount }}</div>
            <p class="text-[11px] text-secondary">Support messages sent</p>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Recent Purchases
            </h3>
            <a href="{{ route('user.orders') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All &rarr;</a>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase tracking-wider">
                            <th class="py-2.5 px-3">Order ID</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Total Amount</th>
                            <th class="py-2.5 px-3">Payment Status</th>
                            <th class="py-2.5 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($orders->take(5) as $order)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-mono text-primary font-bold">#{{ $order->order_number ?? 'ORD-' . $order->id }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-primary">₹{{ number_format($order->grand_total, 2) }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-600 border border-amber-500/20' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('user.downloads') }}" class="px-2.5 py-1 bg-secondary text-primary border border-primary border-rounded font-semibold text-[11px] hover:bg-hover-secondary transition">
                                        View Library
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-bag-shopping text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No purchase orders found.</p>
                <p>Browse our course store to find study material.</p>
            </div>
        @endif
    </div>

    <!-- ============================================================ -->
    <!-- ADMISSION ENROLLMENT APPLICATION MODAL -->
    <!-- ============================================================ -->
    <div id="admissionModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-primary border border-primary border-rounded w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-4 border-bottom sticky top-0 bg-primary z-10">
                <h3 class="text-base font-bold text-primary flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Student Admission & Enrollment Form
                </h3>
                <button type="button" onclick="closeAdmissionModal()" class="text-secondary hover:text-primary transition">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form action="{{ route('user.apply-admission') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 text-xs">
                @csrf

                <!-- ACADEMIC SELECTION (CATEGORY -> CLASS -> SUBJECT) -->
                <div>
                    <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">1. Academic Selection</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-secondary mb-1">Academic Category *</label>
                            <select id="admCategorySelect" name="academic_category_id" required onchange="loadClasses(this.value)" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $user->academic_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Class / Course *</label>
                            <select id="admClassSelect" name="class_id" required onchange="loadSubjects(this.value)" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                                <option value="">-- Choose Class --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Subject / Stream</label>
                            <select id="admSubjectSelect" name="subject_id" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                                <option value="">-- Choose Subject --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- KYC & IDENTIFICATION -->
                <div>
                    <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">2. Identification & Documents</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block font-bold text-secondary mb-1">Aadhaar Card Number *</label>
                            <input type="text" name="aadhaar_number" value="{{ $user->aadhaar_number }}" required placeholder="12-digit Aadhaar Number (e.g. 1234 5678 9012)" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Aadhaar Card (Front Photo) *</label>
                            <input type="file" name="aadhaar_front" accept="image/*,application/pdf" required class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Aadhaar Card (Back Photo) *</label>
                            <input type="file" name="aadhaar_back" accept="image/*,application/pdf" required class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Previous Marksheet / Certificate *</label>
                            <input type="file" name="previous_marksheet" accept="image/*,application/pdf" required class="w-full p-1.5 border-primary border-rounded bg-primary text-primary text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-secondary file:text-primary">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Previous School / College Name</label>
                            <input type="text" name="previous_school" value="{{ $user->previous_school }}" placeholder="Name of previous institute" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                    </div>
                </div>

                <!-- PERSONAL & CONTACT DETAILS -->
                <div>
                    <h4 class="font-bold text-primary uppercase tracking-wider text-[11px] mb-2 pb-1 border-bottom">3. Personal & Contact Details</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-secondary mb-1">Phone Number *</label>
                            <input type="text" name="number" value="{{ $user->number }}" required class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ $user->dob }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ $user->pincode }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block font-bold text-secondary mb-1">Residential Address</label>
                            <input type="text" name="address" value="{{ $user->address }}" placeholder="Street address, flat / house no." class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">City</label>
                            <input type="text" name="city" value="{{ $user->city }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                        <div>
                            <label class="block font-bold text-secondary mb-1">State</label>
                            <input type="text" name="state" value="{{ $user->state }}" class="w-full p-2 border-primary border-rounded bg-primary text-primary text-xs input-focus">
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top flex justify-end gap-2 sticky bottom-0 bg-primary z-10 py-2">
                    <button type="button" onclick="closeAdmissionModal()" class="px-4 py-2 bg-secondary text-primary border border-primary border-rounded font-bold hover:bg-hover-secondary transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white border-rounded font-bold hover:opacity-90 shadow-sm transition">
                        Submit Admission Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openAdmissionModal() {
        document.getElementById('admissionModal').classList.remove('hidden');
        const catSelect = document.getElementById('admCategorySelect');
        if (catSelect && catSelect.value) {
            loadClasses(catSelect.value);
        }
    }

    function closeAdmissionModal() {
        document.getElementById('admissionModal').classList.add('hidden');
    }

    function loadClasses(categoryId) {
        const classSelect = document.getElementById('admClassSelect');
        const subjectSelect = document.getElementById('admSubjectSelect');
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

    function loadSubjects(classId) {
        const subjectSelect = document.getElementById('admSubjectSelect');
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
