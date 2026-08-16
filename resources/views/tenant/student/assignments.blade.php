@extends('layouts.student')
@section('title', 'Assignments & Quizzes - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-pen-ruler text-purple-500"></i> Assignments & Online Quizzes
            </h1>
            <p class="text-xs text-secondary mt-0.5">Submit homework assignments and attempt online tests for your enrolled batch.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-secondary text-primary border border-primary">
                {{ $user->class->name ?? 'Standard Class' }} {{ $user->subject ? '• ' . $user->subject->name : '' }}
            </span>
        </div>
    </div>

    <!-- REAL ASSIGNMENTS CONTAINER -->
    <div class="p-12 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-3 bg-primary mb-6">
        <div class="w-14 h-14 rounded-full bg-purple-500/10 text-purple-600 flex items-center justify-center mx-auto text-2xl">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div class="space-y-1">
            <h3 class="font-bold text-primary text-sm">No pending assignments or tests</h3>
            <p class="text-secondary max-w-md mx-auto">All assignments are up to date! When your faculty or batch instructor assigns homework, numerical problem sets, or online MCQ tests, they will be listed here with submission guidelines.</p>
        </div>
    </div>
@endsection
