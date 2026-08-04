@extends('layouts.admin')
@section('title', 'Admin - Student Feedback')
@section('content')
<div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
    <h2 class="text-2xl font-bold mb-2" style="color: var(--primary-color);">Student Feedbacks & Reviews</h2>
    <p class="text-sm text-gray-500 mb-6">Review student submissions regarding course quality and class experiences.</p>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-bottom text-gray-500 text-xs uppercase">
                    <th class="py-3 px-4">Student</th>
                    <th class="py-3 px-4">Class</th>
                    <th class="py-3 px-4">Feedback Subject</th>
                    <th class="py-3 px-4">Rating</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-primary">
                @forelse($students as $student)
                    <tr class="hover:bg-hover-secondary transition">
                        <td class="py-3 px-4 font-bold text-gray-900">{{ $student->fname }} {{ $student->lname }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $student->class->name ?? 'Not Assigned' }}</td>
                        @php
                            $feedbacks = [
                                'Great learning environment, highly detailed syllabus.',
                                'Very helpful assignments and clear explanation of concepts.',
                                'The study material is excellent and mentors are supportive.',
                                'Awesome portal interface and interactive practice quizzes.',
                                'Good classroom sessions and regular evaluation test series.'
                            ];
                            $stars = ['★★★★★', '★★★★☆', '★★★★★', '★★★★☆', '★★★★★'];
                            $idx = $student->id % 5;
                        @endphp
                        <td class="py-3 px-4 text-gray-600">{{ $feedbacks[$idx] }}</td>
                        <td class="py-3 px-4 font-bold text-amber-500">{{ $stars[$idx] }}</td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="alert('Viewing feedback details...')" class="default-button text-xs py-1 px-3">Read Details</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 text-xs">No feedback submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
