@extends('layouts.student')
@section('title', 'Assignments & Quizzes - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-pen-ruler text-purple-500"></i> Assignments & Online Quizzes
            </h1>
            <p class="text-xs text-secondary mt-0.5">Submit homework assignments and attempt online tests.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- ASSIGNMENTS TASK -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 rounded text-[10px] bg-purple-500/10 text-purple-600 font-bold border border-purple-500/20 uppercase">
                    Pending Homework
                </span>
                <span class="text-[10px] font-mono text-rose-600 font-bold">Due: Tomorrow 5 PM</span>
            </div>
            <div>
                <h3 class="text-sm font-bold text-primary">Physics Friction & Circular Motion Numerical Set 4</h3>
                <p class="text-xs text-secondary mt-1">Solve 15 numerical problems from Chapter 3 and upload solution PDF.</p>
            </div>
            <div class="pt-2 border-top flex justify-between items-center">
                <span class="text-xs text-tertiary">Max Marks: 50</span>
                <button onclick="alert('Submission modal is opening...');" class="px-3 py-1.5 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition">
                    Upload Solution PDF
                </button>
            </div>
        </div>

        <!-- ONLINE TEST QUIZ CARD -->
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20 uppercase">
                    Online Test Ready
                </span>
                <span class="text-[10px] font-mono text-tertiary font-bold">30 Mins · 25 MCQs</span>
            </div>
            <div>
                <h3 class="text-sm font-bold text-primary">NEET Practice Mock Quiz #2</h3>
                <p class="text-xs text-secondary mt-1">Class 11 Physics & Chemistry practice test with instant scorecard.</p>
            </div>
            <div class="pt-2 border-top flex justify-between items-center">
                <span class="text-xs text-tertiary">Passing Marks: 60%</span>
                <button onclick="alert('Online MCQ quiz is starting...');" class="px-3 py-1.5 bg-emerald-600 text-white border-rounded font-bold text-xs hover:bg-emerald-700 transition">
                    Start Test Now
                </button>
            </div>
        </div>
    </div>
@endsection
