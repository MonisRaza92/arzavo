@extends('layouts.admin')
@section('title', 'Admin - Student ID Card Generator')

@section('content')
<div class="my-4 space-y-6">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-primary">Student ID Card Generator</h2>
            <p class="text-xs text-secondary mt-1">Official printable student identification cards with academic credentials & roll number.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-invert text-invert px-4 py-2 border-rounded text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 transition hover:opacity-90">
                <i class="fa-solid fa-print"></i> Print All ID Cards
            </button>
        </div>
    </div>

    <!-- ID CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="idCardsContainer">
        @php
            $academyName = app('currentTenant')->name ?? 'Academy';
        @endphp

        @forelse($students as $student)
            <div class="border border-primary rounded-2xl shadow-md bg-primary p-5 flex flex-col justify-between relative overflow-hidden transition hover:shadow-lg" style="min-height: 240px;">
                <!-- TOP HEADER BAND -->
                <div class="flex items-center justify-between border-b border-primary pb-3 mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-xs tracking-wider text-primary uppercase">{{ $academyName }}</h3>
                            <span class="text-[9px] text-tertiary block font-mono">STUDENT IDENTITY CARD</span>
                        </div>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                        VERIFIED
                    </span>
                </div>

                <!-- STUDENT DETAILS BODY -->
                <div class="flex items-start gap-4 grow">
                    <!-- Photo / Avatar -->
                    <div class="w-20 h-24 rounded-xl bg-secondary/50 border border-primary flex items-center justify-center font-bold text-2xl text-primary shrink-0 shadow-xs uppercase">
                        {{ strtoupper(substr($student->fname, 0, 1)) }}{{ strtoupper(substr($student->lname, 0, 1)) }}
                    </div>

                    <!-- Information Ledger -->
                    <div class="space-y-1 text-xs grow min-w-0">
                        <h4 class="font-black text-sm text-primary truncate leading-tight">{{ $student->fname }} {{ $student->lname }}</h4>
                        
                        <div class="text-[11px] space-y-0.5 pt-1">
                            <p class="text-secondary flex justify-between">
                                <span class="text-tertiary font-bold">Roll / ID:</span>
                                <span class="font-mono font-bold text-primary">{{ $student->username }}</span>
                            </p>
                            <p class="text-secondary flex justify-between">
                                <span class="text-tertiary font-bold">Category:</span>
                                <span class="font-semibold text-primary">{{ $student->academicCategory->name ?? 'Null' }}</span>
                            </p>
                            <p class="text-secondary flex justify-between">
                                <span class="text-tertiary font-bold">Class / Sub:</span>
                                <span class="font-semibold text-primary truncate">{{ $student->class->name ?? 'Null' }} {{ $student->subject ? '('.$student->subject->name.')' : '' }}</span>
                            </p>
                            <p class="text-secondary flex justify-between">
                                <span class="text-tertiary font-bold">Contact:</span>
                                <span class="font-mono text-primary">{{ $student->number ?: 'Null' }}</span>
                            </p>
                            @if($student->aadhaar_number)
                                <p class="text-secondary flex justify-between">
                                    <span class="text-tertiary font-bold">Aadhaar:</span>
                                    <span class="font-mono text-primary">{{ $student->aadhaar_number }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- BOTTOM FOOTER BAND -->
                <div class="border-t border-primary pt-3 mt-3 flex justify-between items-center text-[10px]">
                    <span class="text-tertiary font-mono font-bold">VALID: {{ date('Y') }} - {{ date('Y') + 1 }}</span>
                    <a href="{{ route('admin.admin-student-profile', $student->username) }}" class="px-2.5 py-1 bg-secondary text-primary border border-primary border-rounded font-bold text-[10px] hover:bg-hover-secondary transition">
                        View Profile
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center text-tertiary text-xs border border-dashed border-primary border-rounded bg-primary space-y-2">
                <i class="fa-solid fa-id-card text-3xl opacity-40"></i>
                <h4 class="font-bold text-primary text-sm">No registered students found</h4>
                <p>Add students manually or approve admission applications to generate ID cards.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #idCardsContainer, #idCardsContainer * {
        visibility: visible;
    }
    #idCardsContainer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 16px !important;
    }
}
</style>
@endsection
