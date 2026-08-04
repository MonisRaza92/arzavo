@extends('layouts.admin')
@section('title', 'Admin - ID Card Generator')
@section('content')
<div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
    <h2 class="text-2xl font-bold mb-2" style="color: var(--primary-color);">Student ID Card Generator</h2>
    <p class="text-sm text-gray-500 mb-6">Generate and print official student identification cards with photo & QR code.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="border rounded-lg shadow-sm bg-primary border-primary p-4 max-w-sm flex flex-col justify-between" style="aspect-ratio: 8.5 / 5.5;">
                <div class="flex items-center justify-between border-bottom pb-2 mb-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-indigo-500 text-lg"></i>
                        <span class="font-extrabold text-xs tracking-tight text-primary">ALSHIFA ACADEMY</span>
                    </div>
                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">STUDENT</span>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded bg-hover-secondary flex items-center justify-center font-bold text-lg text-primary border border-primary shrink-0">
                        {{ strtoupper(substr($student->fname, 0, 1)) }}{{ strtoupper(substr($student->lname, 0, 1)) }}
                    </div>
                    <div class="space-y-1 text-xs overflow-hidden">
                        <h4 class="font-extrabold text-sm text-primary truncate">{{ $student->fname }} {{ $student->lname }}</h4>
                        <p class="text-secondary"><span class="font-bold text-tertiary">Roll No:</span> STU-{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-secondary"><span class="font-bold text-tertiary">Class:</span> {{ $student->class->name ?? 'Not Assigned' }}</p>
                        <p class="text-secondary truncate"><span class="font-bold text-tertiary">Email:</span> {{ $student->email }}</p>
                    </div>
                </div>

                <div class="border-top pt-2 mt-2 flex justify-between items-center">
                    <span class="text-[9px] text-tertiary font-bold">VALID: 2026 - 2027</span>
                    <button onclick="window.print();" class="px-2.5 py-1 bg-hover-secondary text-primary border-primary border-rounded font-bold text-[10px] hover-primary transition">
                        <i class="fa-solid fa-print"></i> Print ID
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full p-8 text-center text-tertiary text-xs border-dashed border-rounded bg-primary">
                No students registered yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
