@extends('layouts.student')
@section('title', 'My Certificates - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-award text-amber-500"></i> My Course Completion Certificates
            </h1>
            <p class="text-xs text-secondary mt-0.5">View and download your official course completion certificates.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded text-[10px] bg-amber-500/10 text-amber-600 font-bold border border-amber-500/20 uppercase">
                        Verified Certificate
                    </span>
                    <span class="text-[10px] font-mono text-tertiary">#CERT-1092</span>
                </div>
                <h3 class="text-sm font-bold text-primary leading-snug">
                    Class 11th Physics Foundation Certificate
                </h3>
                <p class="text-xs text-secondary leading-relaxed">
                    Issued by Academy upon completing 100% course curriculum and passing final evaluation exam.
                </p>
            </div>

            <div class="pt-3 border-top">
                <button onclick="window.print();" 
                        class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-download"></i> Download PDF Certificate
                </button>
            </div>
        </div>
    </div>
@endsection
