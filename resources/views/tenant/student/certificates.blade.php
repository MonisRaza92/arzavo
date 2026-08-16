@extends('layouts.student')
@section('title', 'My Certificates - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-award text-amber-500"></i> My Course Completion Certificates
            </h1>
            <p class="text-xs text-secondary mt-0.5">Official course completion and achievement certificates awarded to your student account.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-secondary text-primary border border-primary font-mono">
                Roll: {{ $user->username }}
            </span>
        </div>
    </div>

    <!-- CERTIFICATES CONTAINER -->
    <div class="p-12 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-3 bg-primary mb-6">
        <div class="w-14 h-14 rounded-full bg-amber-500/10 text-amber-600 flex items-center justify-center mx-auto text-2xl">
            <i class="fa-solid fa-award"></i>
        </div>
        <div class="space-y-1">
            <h3 class="font-bold text-primary text-sm">No course completion certificates issued yet</h3>
            <p class="text-secondary max-w-md mx-auto">Certificates are automatically awarded and available for PDF download once you complete 100% of the lectures in an enrolled course or batch curriculum.</p>
        </div>
    </div>
@endsection
