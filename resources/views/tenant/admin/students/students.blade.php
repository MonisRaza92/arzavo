@extends('layouts.admin')
@section('title', 'Admin Students')
@section('content')

<!-- STATS CARDS ROW 1: ENROLLMENTS -->
<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Students</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-graduate text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Active Students</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-check text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Inactive Students</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->where('status', 'inactive')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-times text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Suspended</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $students->where('status', 'suspended')->count() }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-user-slash text-lg text-primary"></i></div>
        </div>
    </div>
</div>

<!-- STATS CARDS ROW 2: FINANCIALS -->
<div class="statics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Total Fees</h2>
                <p class="text-2xl font-bold mt-1 text-primary">₹{{ number_format($totalFees, 2) }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-wallet text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Collected Fees</h2>
                <p class="text-2xl font-bold mt-1 text-primary">₹{{ number_format($collectedFees, 2) }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-circle-check text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Pending Fees</h2>
                <p class="text-2xl font-bold mt-1 text-primary">₹{{ number_format($pendingFees, 2) }}</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-clock text-lg text-primary"></i></div>
        </div>
    </div>
    <div class="stat border-rounded bg-primary border-primary flex flex-col justify-between">
        <div class="stat-content p-4 flex flex-row justify-between items-center">
            <div class="data">
                <h2 class="text-tertiary text-xs uppercase tracking-wider font-semibold">Collection Ratio</h2>
                <p class="text-2xl font-bold mt-1 text-primary">{{ $collectionRatio }}%</p>
            </div>
            <div class="bg-tertiary border-rounded p-3"><i class="fas fa-chart-pie text-lg text-primary"></i></div>
        </div>
    </div>
</div>

<!-- STUDENTS LIST CONTAINER -->
<div class="bg-primary border-rounded border-primary mt-4">
    <div class="px-4 py-3 flex flex-wrap justify-between items-center gap-3">
        <h3 class="text-primary text-lg font-bold flex items-center gap-2">
            <span><i class="fa-solid fa-user-graduate"></i> Student Profiles</span>
        </h3>
        <div class="relative">
            <input type="text" id="studentSearchInput" onkeyup="filterStudentsTable()" placeholder="Search students..." 
                   class="border text-xs p-2 border-primary border-rounded bg-primary text-primary w-64 pr-8">
            <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-tertiary text-xs"></i>
        </div>
    </div>

    <!-- TABLE LAYOUT -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-tertiary">
                    <th class="p-3 pl-4 text-left">Student</th>
                    <th class="p-3 text-left">Academics (Class / Subject)</th>
                    <th class="p-3 text-left">Last Payment</th>
                    <th class="p-3 text-left">Remaining Dues</th>
                    <th class="p-3 text-center">Role</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students->sortByDesc('id') as $student)
                    <tr class="border-top student-row hover:bg-hover-secondary transition">
                        <!-- Student Profile -->
                        <td class="p-3 pl-4 text-left">
                            <div class="flex items-center gap-3">
                                @if ($student->profile_picture)
                                    <img src="{{ asset($student->profile_picture) }}" alt="{{ $student->fname }}" class="w-8 h-8 rounded-full object-cover border border-primary shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs uppercase shrink-0 bg-tertiary text-primary border border-primary">
                                        {{ substr($student->fname, 0, 1) }}{{ substr($student->lname, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-primary text-xs student-fullname">{{ $student->fname }} {{ $student->lname }}</h4>
                                    <p class="text-[10px] text-tertiary font-mono student-email">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Academics Info -->
                        <td class="p-3 text-left text-xs text-secondary">
                            <div class="space-y-0.5">
                                <p class="font-semibold">{{ $student->class->name ?? 'No Class' }}</p>
                                <p class="text-[10px] text-tertiary">{{ $student->subject->name ?? 'No Subject' }}</p>
                            </div>
                        </td>

                        <!-- Last Payment Details -->
                        <td class="p-3 text-left text-xs font-mono text-secondary">
                            <div class="space-y-0.5">
                                <p class="uppercase font-bold text-[10px]">
                                    {{ optional($student->feePayments->last())->status ?? 'No Payments' }}
                                </p>
                                <p class="text-[10px] text-tertiary">
                                    Plan: {{ optional($student->feePlans->first())->plan_type ?? 'N/A' }}
                                </p>
                            </div>
                        </td>

                        <!-- Fee Dues Info -->
                        <td class="p-3 text-left text-xs text-secondary font-mono">
                            <div class="space-y-0.5">
                                <p class="font-semibold text-primary">₹{{ number_format($student->feePlans->sum('amount'), 2) }}</p>
                                <p class="text-[10px] text-tertiary">Due Day: {{ optional($student->feePlans->first())->due_day ?? 'N/A' }}</p>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="p-3 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-0.5 rounded text-[10px] font-bold text-primary bg-primary hover:bg-hover-secondary uppercase"
                                  onclick="if(confirm('Change student role of {{ $student->fname }} to user?')) { event.preventDefault(); document.getElementById('studentRoleForm{{ $student->id }}').submit(); }">
                                {{ $student->role }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="p-3 text-center">
                            <span class="cursor-pointer border border-primary px-2.5 py-0.5 rounded text-[10px] font-bold uppercase transition
                                  {{ $student->status === 'active' ? 'bg-invert text-invert' : 'bg-tertiary text-primary' }}"
                                  onclick="if(confirm('Change status of {{ $student->fname }}?')) { event.preventDefault(); document.getElementById('studentStatusForm{{ $student->id }}').submit(); }">
                                {{ $student->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="p-3 text-right pr-4">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.admin-student-profile', $student->username) }}" 
                                   class="text-tertiary hover:text-primary transition text-sm" title="View Student Cockpit Profile">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <button onclick="if(confirm('Demote {{ $student->fname }} to User?')) { document.getElementById('studentRoleForm{{ $student->id }}').submit(); }" 
                                        class="text-tertiary hover:text-primary transition text-sm" title="Demote to User">
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
                        <td colspan="7" class="p-4 text-center text-tertiary text-xs">
                            No registered students found in the database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterStudentsTable() {
    let input = document.getElementById("studentSearchInput");
    let filter = input.value.toLowerCase();
    let rows = document.getElementsByClassName("student-row");

    for (let i = 0; i < rows.length; i++) {
        let fullname = rows[i].querySelector(".student-fullname").innerText.toLowerCase();
        let email = rows[i].querySelector(".student-email").innerText.toLowerCase();

        if (fullname.includes(filter) || email.includes(filter)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>

@endsection
