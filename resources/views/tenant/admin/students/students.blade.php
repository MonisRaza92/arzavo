@extends('layouts.admin')
@section('title', 'Admin Students')
@section('content')

<!-- STATS CARDS -->
<div class="statics grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Students</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-graduation-cap text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Active Students</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-check text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Fees Collected</h2>
                <p class="text-2xl font-bold mt-1 text-emerald-600">₹{{ number_format($collectedFees ?? 0, 2) }}</p>
            </div>
            <div class="bg-emerald-500/10 border-rounded p-3"><i class="fas fa-wallet text-lg text-emerald-600"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Pending Dues</h2>
                <p class="text-2xl font-bold mt-1 text-amber-600">₹{{ number_format($pendingFees ?? 0, 2) }}</p>
            </div>
            <div class="bg-amber-500/10 border-rounded p-3"><i class="fas fa-clock text-lg text-amber-600"></i></div>
        </div>
    </div>
</div>

<!-- STUDENTS LIST CONTAINER -->
<div class="bg-primary border-rounded border-primary mt-4 overflow-hidden">
    <div class="px-4 py-3 border-bottom flex flex-wrap justify-between items-center gap-3 bg-primary">
        <h3 class="text-primary text-base font-bold flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-primary"></i> Student Profiles
        </h3>
        <div class="relative w-full sm:w-72">
            <input type="text" id="studentSearchInput" onkeyup="filterStudentsTable()" placeholder="Search student name, email, class..." 
                   class="border text-xs py-2 px-3 pl-8 border-primary border-rounded bg-primary text-primary w-full input-focus">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-tertiary text-xs"></i>
        </div>
    </div>

    <!-- TABLE LAYOUT -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3.5 pl-4 text-left">Student</th>
                    <th class="p-3.5 text-left">Academics</th>
                    <th class="p-3.5 text-left">Purchases & Orders</th>
                    <th class="p-3.5 text-left">Fee Plan & Dues</th>
                    <th class="p-3.5 text-center">Role</th>
                    <th class="p-3.5 text-center">Status</th>
                    <th class="p-3.5 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students->sortByDesc('id') as $student)
                    @php
                        $paidOrders = $student->orders ? $student->orders->where('payment_status', 'paid') : collect();
                        $paidOrdersCount = $paidOrders->count();
                        $totalSpent = $paidOrders->sum('grand_total');
                        $entitlementsCount = $student->entitlements ? $student->entitlements->count() : 0;
                        $enrolledCoursesCount = $student->enrolledCourses ? $student->enrolledCourses->count() : 0;
                        $latestFeePlan = $student->feePlans ? $student->feePlans->first() : null;
                        $totalFeePaid = $student->feePayments ? $student->feePayments->where('status', 'paid')->sum('amount_paid') : 0;
                    @endphp
                    <tr class="border-bottom student-row hover:bg-hover-secondary transition">
                        <!-- Student Profile/Name -->
                        <td class="p-3.5 pl-4 text-left">
                            <div class="flex items-center gap-3">
                                @if ($student->profile_picture)
                                    <img src="{{ asset($student->profile_picture) }}" alt="{{ $student->fname }}" class="w-9 h-9 rounded-full object-cover border border-primary shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-secondary text-primary border border-primary">
                                        {{ substr($student->fname ?? 'S', 0, 1) }}{{ substr($student->lname ?? '', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="font-bold text-primary text-xs student-fullname hover:underline block leading-tight">
                                        {{ $student->fname }} {{ $student->lname }}
                                    </a>
                                    <p class="text-[10px] text-tertiary font-mono student-email mt-0.5">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Academics -->
                        <td class="p-3.5 text-left text-xs student-academics">
                            <div class="space-y-0.5">
                                <div class="font-bold text-primary flex items-center gap-1.5">
                                    {{ $student->class->name ?? 'No Class Assigned' }}
                                </div>
                                <div class="text-[10px] text-secondary flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.2 rounded bg-secondary text-primary border border-primary text-[9px] font-bold">
                                        {{ $student->academicCategory->name ?? 'General' }}
                                    </span>
                                    <span>{{ $student->subject->name ?? 'All Subjects' }}</span>
                                </div>
                                @if($student->aadhaar_number)
                                    <div class="text-[9px] text-tertiary font-mono">
                                        <i class="fa-solid fa-id-card text-tertiary mr-0.5"></i> {{ $student->aadhaar_number }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Purchases & Digital Orders -->
                        <td class="p-3.5 text-left">
                            @if($paidOrdersCount > 0 || $entitlementsCount > 0 || $enrolledCoursesCount > 0)
                                <div class="p-2 rounded-xl bg-secondary/40 border border-primary space-y-1 min-w-[200px]">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-500/15 text-emerald-600 border border-emerald-500/20 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-bag-shopping text-[9px]"></i>
                                            {{ $paidOrdersCount }} Order{{ $paidOrdersCount > 1 ? 's' : '' }}
                                        </span>
                                        <span class="text-xs font-mono font-bold text-primary">₹{{ number_format($totalSpent, 2) }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-secondary border-t border-primary/50 pt-1">
                                        <span>
                                            <i class="fa-solid fa-graduation-cap text-indigo-500 mr-0.5"></i>
                                            {{ $enrolledCoursesCount }} Course(s) • {{ $entitlementsCount }} Book(s)
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="px-2 py-0.5 text-[11px] font-medium text-tertiary bg-secondary/30 rounded-lg border border-primary/50 inline-flex items-center gap-1">
                                    No Digital Purchases
                                </span>
                            @endif
                        </td>

                        <!-- Fee Plan & Dues -->
                        <td class="p-3.5 text-left text-xs">
                            @if($latestFeePlan)
                                <div class="space-y-0.5">
                                    <div class="font-bold text-primary">
                                        ₹{{ number_format($latestFeePlan->amount, 2) }} <span class="text-[10px] text-secondary font-normal">({{ ucfirst($latestFeePlan->plan_type) }})</span>
                                    </div>
                                    <div class="text-[10px] text-secondary">
                                        Paid: ₹{{ number_format($totalFeePaid, 2) }} • Due: {{ $latestFeePlan->due_day ? $latestFeePlan->due_day.'th' : 'N/A' }}
                                    </div>
                                </div>
                            @else
                                <span class="text-[11px] text-tertiary font-mono">No Fee Plan</span>
                            @endif
                        </td>

                        <!-- Role -->
                        <td class="p-3.5 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-1 rounded-lg text-[10px] font-bold text-primary bg-secondary hover:bg-hover-secondary uppercase transition"
                                  title="Click to convert to standard User"
                                  onclick="if(confirm('Convert student {{ $student->fname }} to standard User account?')) { event.preventDefault(); document.getElementById('studentRoleForm{{ $student->id }}').submit(); }">
                                {{ $student->role }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="p-3.5 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase transition
                                  {{ $student->status === 'active' ? 'bg-invert text-invert' : 'bg-secondary text-primary' }}"
                                  title="Click to toggle status"
                                  onclick="if(confirm('Toggle status of student {{ $student->fname }}?')) { event.preventDefault(); document.getElementById('studentStatusForm{{ $student->id }}').submit(); }">
                                {{ $student->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right pr-4">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}" 
                                   class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                   title="View Full Student Profile & Academic Ledger">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.finance.orders', ['search' => $student->email]) }}" 
                                   class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                   title="View Purchase Orders">
                                    <i class="fa-solid fa-receipt"></i>
                                </a>
                                <button onclick="if(confirm('Convert {{ $student->fname }} to User?')) { document.getElementById('studentRoleForm{{ $student->id }}').submit(); }" 
                                        class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                        title="Demote to User">
                                    <i class="fa-solid fa-user"></i>
                                </button>
                                <button onclick="if(confirm('Toggle status of {{ $student->fname }}?')) { document.getElementById('studentStatusForm{{ $student->id }}').submit(); }" 
                                        class="w-8 h-8 rounded-lg bg-secondary text-primary border border-primary hover:bg-hover-secondary flex items-center justify-center text-xs transition" 
                                        title="Toggle Status">
                                    <i class="fa-solid fa-user-slash"></i>
                                </button>
                            </div>

                            <form id="studentRoleForm{{ $student->id }}" action="{{ route('admin.update-student-role') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ $student->id }}">
                            </form>
                            <form id="studentStatusForm{{ $student->id }}" action="{{ route('admin.update-student-status') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="id" value="{{ $student->id }}">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-tertiary text-xs">
                            <i class="fa-solid fa-graduation-cap text-2xl mb-2 text-tertiary block opacity-50"></i>
                            No student profiles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterStudentsTable() {
    let input = document.getElementById('studentSearchInput');
    let filter = input.value.toLowerCase();
    let rows = document.querySelectorAll('.student-row');

    rows.forEach(row => {
        let name = row.querySelector('.student-fullname') ? row.querySelector('.student-fullname').innerText.toLowerCase() : '';
        let email = row.querySelector('.student-email') ? row.querySelector('.student-email').innerText.toLowerCase() : '';
        let academics = row.querySelector('.student-academics') ? row.querySelector('.student-academics').innerText.toLowerCase() : '';

        if (name.includes(filter) || email.includes(filter) || academics.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

@endsection
