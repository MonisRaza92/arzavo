@extends('layouts.admin')
@section('title', 'Admin Students')
@section('content')
@include('admin.partials.students_stats')
<div class="students">
    <div class="rounded-md p-4 pb-0 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--primary-color);">All Students</h2>

        <div class="">
            @foreach($students->sortByDesc('id') as $student)
            <div class="grid grid-cols-3 items-center justify-between p-2 rounded-sm" style="border-top: 1px solid var(--border-color);">

                <!-- Profile -->
                <div class="flex items-center gap-4">
                    @if ($student->profile_picture)
                    <img src="{{ asset($student->profile_picture) }}"
                        alt="{{ $student->fname }} {{ $student->lname }}"
                        class="w-14 h-14 rounded-md object-cover border">
                    @else
                    <h2 class="font-bold text-2xl flex justify-center items-center w-14 h-14 rounded-md"
                        style="background-color: var(--primary-color); color: var(--text-light);">
                        {{ strtoupper(substr($student->fname, 0, 1)) }}
                    </h2>
                    @endif

                    <!-- Basic Info -->
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $student->fname }} {{ $student->lname }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $student->email }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $student->number }}
                        </p>
                    </div>
                </div>

                <!-- Extra Info -->
                <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm px-2">
                    <p><span class="font-medium">City:</span> {{ $student->city ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Course:</span> {{ $student->course->name ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Class:</span> {{ $student->class->name ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Subject:</span> {{ $student->subject->name ?? 'Not Updated' }}</p>
                </div>

                <!-- Role / Status / Joined -->
                <div class="text-right">
                    <span onclick="if(confirm('Are you sure you want to update the role?')) { event.preventDefault(); document.getElementById('studentRoleForm{{ $student->id }}').submit(); }" class="px-2 cursor-pointer! py-1 text-xs rounded 
                            {{ $student->role === 'student' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($student->role) }}
                    </span>
                    <span onclick="if(confirm('Are you sure you want to update the status?')) { event.preventDefault(); document.getElementById('studentStatusForm{{ $student->id }}').submit(); }" class="ml-2 cursor-pointer! px-2 py-1 text-xs rounded 
                    {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">
                        Joined {{ $student->created_at->format('d M Y') }}
                    </p>
                </div>
                <form class="hidden" id="studentRoleForm{{ $student->id }}" action="{{ route('update-student-role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $student->id }}">
                </form>
                <form class="hidden" id="studentStatusForm{{ $student->id }}" action="{{ route('update-student-status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $student->id }}">
                </form>
                <div class="fee-info flex flex-row justify-between col-span-3 gap-4 mt-2 pt-2" style="border-top: 1px solid var(--border-color);">
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Last Payment:</span> {{ optional($student->feePayments->last())->status ?? 'No Payments' }}
                    </p>
                    <div class="h-5 w-0.5" style="background-color: var(--border-color);"></div>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Fee to Paid:</span> {{ $student->feePlans->sum('amount') ?? 'No Plans' }}
                    </p>
                    <div class="h-5 w-0.5" style="background-color: var(--border-color);"></div>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Due Date:</span> {{ optional($student->feePlans->first())->due_day ?? 'No Payments' }}
                    </p>
                    <div class="h-5 w-0.5" style="background-color: var(--border-color);"></div>
                    <p class="text-sm text-gray-500">
                        <span class="font-medium">Payment Plan:</span> {{ optional($student->feePlans->first())->plan_type ?? 'No Plans' }}
                    </p>
                    <div class="h-5 w-0.5" style="background-color: var(--border-color);"></div>
                    <a href="{{ route('admin-student-profile', $student->username) }}" class="uppercase text-sm font-bold" style="color: var(--primary-color);">View More <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection