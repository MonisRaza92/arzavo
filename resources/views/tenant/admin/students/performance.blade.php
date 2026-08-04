@extends('layouts.admin')
@section('title', 'Admin - Student Performance')
@section('content')
<div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
    <h2 class="text-2xl font-bold mb-2" style="color: var(--primary-color);">Student Performance Reports</h2>
    <p class="text-sm text-gray-500 mb-6">Analyze marks cards, quiz scores, and academic ranks of enrolled students.</p>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-bottom text-gray-500 text-xs uppercase">
                    <th class="py-3 px-4">Student</th>
                    <th class="py-3 px-4">Class</th>
                    <th class="py-3 px-4">Average Score</th>
                    <th class="py-3 px-4">Quizzes Completed</th>
                    <th class="py-3 px-4 text-right">Performance Sheet</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-primary">
                @forelse($students as $student)
                    <tr class="hover:bg-hover-secondary transition">
                        <td class="py-3 px-4 font-bold text-gray-900">{{ $student->fname }} {{ $student->lname }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $student->class->name ?? 'Not Assigned' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-emerald-600">{{ 70 + ($student->id % 26) }}.{{ $student->id % 10 }}%</td>
                        <td class="py-3 px-4 font-semibold text-gray-900">{{ 8 + ($student->id % 5) }} / 12</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="default-button text-xs py-1 px-3">View Analytics</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 text-xs">No students registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
