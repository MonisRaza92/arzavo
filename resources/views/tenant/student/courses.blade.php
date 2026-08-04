@extends('layouts.student')
@section('title', 'My Courses & Batches - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-emerald-500"></i> My Courses & Batches
            </h1>
            <p class="text-xs text-secondary mt-0.5">Access video lectures, chapter notes, and learning materials.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @if($courses->count() > 0)
            @foreach($courses as $course)
                <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-500/10 text-emerald-600 font-bold border border-emerald-500/20 uppercase">
                                {{ $course->category ? $course->category->name : 'Batch Course' }}
                            </span>
                            <span class="text-[10px] font-mono text-tertiary">Enrolled</span>
                        </div>

                        <h3 class="text-sm font-bold text-primary leading-snug">
                            {{ $course->title }}
                        </h3>

                        <p class="text-xs text-secondary leading-relaxed line-clamp-2">
                            {{ $course->description ?: 'Comprehensive video lectures and downloadable notes for class preparation.' }}
                        </p>

                        <div class="space-y-1.5 pt-2">
                            <div class="flex justify-between text-[11px] font-semibold">
                                <span class="text-tertiary">Course Progress</span>
                                <span class="text-emerald-600 font-mono">65%</span>
                            </div>
                            <div class="w-full bg-hover-secondary h-1.5 rounded-full overflow-hidden border border-primary">
                                <div class="bg-emerald-600 h-full rounded-full" style="width: 65%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <a href="#" onclick="alert('Opening video course player...'); return false;" 
                           class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-play"></i> Watch Lectures & Notes
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2 bg-primary">
                <i class="fa-solid fa-graduation-cap text-3xl text-tertiary"></i>
                <p class="font-semibold text-primary">No enrolled courses found.</p>
                <p>Contact academy admin or enroll in batch courses to start learning.</p>
            </div>
        @endif
    </div>

    <div class="pt-2">
        {{ $courses->links() }}
    </div>
@endsection
