@extends('layouts.admin')
@section('title', 'Admin - Student Feedbacks & Inquiries')

@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block Card -->
    <div class="mb-4 p-4 sm:p-5 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-comments text-indigo-500"></i> Student Feedbacks & Reviews
            </h1>
            <p class="text-xs text-secondary mt-0.5">Review student reviews, course ratings, and direct learning inquiries.</p>
        </div>
    </div>

    <!-- FEEDBACKS & REVIEWS TABLE -->
    <div class="bg-primary border-primary border-rounded p-5 sm:p-6 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-primary border-bottom pb-3">Course Reviews & Feedback Submissions</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-bottom text-tertiary text-[10px] uppercase font-extrabold tracking-wider">
                        <th class="py-3 px-3">Student</th>
                        <th class="py-3 px-3">Review Item</th>
                        <th class="py-3 px-3">Rating</th>
                        <th class="py-3 px-3">Comment / Feedback</th>
                        <th class="py-3 px-3">Submitted Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @forelse($feedbacks as $review)
                        <tr class="hover:bg-hover-secondary transition">
                            <td class="py-3 px-3 font-bold text-primary">
                                {{ $review->user->fname ?? 'Student' }} {{ $review->user->lname ?? '' }}
                            </td>
                            <td class="py-3 px-3 text-secondary">
                                {{ $review->reviewable->title ?? ($review->reviewable->name ?? 'Course Material') }}
                            </td>
                            <td class="py-3 px-3 font-bold text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($review->rating ?? 5))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </td>
                            <td class="py-3 px-3 text-secondary">
                                {{ $review->comment ?: 'No written comment.' }}
                            </td>
                            <td class="py-3 px-3 text-tertiary">
                                {{ $review->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-tertiary text-xs">
                                <i class="fa-solid fa-comments text-2xl mb-1.5 block opacity-40"></i>
                                No student reviews or course feedbacks logged in database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feedbacks->hasPages())
            <div class="pt-2">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>

    <!-- STUDENT INQUIRIES -->
    @if($inquiries->count() > 0)
        <div class="bg-primary border-primary border-rounded p-5 sm:p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-primary border-bottom pb-3">Recent Student Support Inquiries</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="border-bottom text-tertiary text-[10px] uppercase font-extrabold tracking-wider">
                            <th class="py-3 px-3">Sender Name</th>
                            <th class="py-3 px-3">Contact</th>
                            <th class="py-3 px-3">Subject / Message</th>
                            <th class="py-3 px-3 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($inquiries as $inq)
                            <tr class="hover:bg-hover-secondary transition">
                                <td class="py-3 px-3 font-bold text-primary">{{ $inq->name }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $inq->email ?: $inq->phone }}</td>
                                <td class="py-3 px-3 text-secondary">{{ $inq->subject ? $inq->subject . ': ' : '' }}{{ $inq->message }}</td>
                                <td class="py-3 px-3 text-right text-tertiary">{{ $inq->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
