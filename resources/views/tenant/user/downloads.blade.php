@extends('layouts.user')
@section('title', 'My E-Books & Downloads - Customer Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-book-open text-purple-500"></i> My E-Books & Downloads
            </h1>
            <p class="text-xs text-secondary mt-0.5">Access all your purchased digital books, study materials and notes.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @if(($entitlements && $entitlements->count() > 0) || ($orders && $orders->count() > 0))
            {{-- 1. Real User Entitlements --}}
            @if($entitlements && $entitlements->count() > 0)
                @foreach($entitlements as $index => $entitlement)
                    @php
                        $item = $entitlement->entitable;
                        $isBook = $item instanceof \App\Models\Tenant\Book;
                        $isCourse = $item instanceof \App\Models\Tenant\Course;
                        $title = $item ? ($item->title ?? $item->name) : 'Purchased Digital Resource #' . ($index + 1);
                        $cover = $item ? ($item->cover_image ?? $item->thumbnail ?? null) : null;
                    @endphp
                    <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-purple-500/10 text-purple-600 font-bold border border-purple-500/20 uppercase">
                                    {{ $isBook ? 'Digital E-Book' : ($isCourse ? 'Online Course' : 'Digital Resource') }}
                                </span>
                                @if($entitlement->order)
                                    <span class="text-[10px] font-mono text-tertiary">#{{ $entitlement->order->order_number }}</span>
                                @endif
                            </div>

                            @if($cover)
                                <img src="{{ media($cover) }}" alt="{{ $title }}" class="w-full h-36 object-cover rounded-lg border border-primary">
                            @endif

                            <h3 class="text-sm font-bold text-primary leading-snug">
                                {{ $title }}
                            </h3>
                            
                            @if($item && !empty($item->description))
                                <p class="text-xs text-secondary leading-relaxed line-clamp-2">
                                    {{ strip_tags($item->short_description ?? $item->description) }}
                                </p>
                            @endif
                        </div>

                        <div class="pt-2 border-top flex items-center gap-2">
                            @if($isBook && $item)
                                <a href="{{ route('item.read', ['type' => 'book', 'id' => $item->id]) }}" target="_blank"
                                   class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-book-open"></i> Read Online
                                </a>
                                @if($item->is_downloadable || !empty($item->file_path))
                                    <a href="{{ route('item.download', ['type' => 'book', 'id' => $item->id]) }}" 
                                       class="py-2.5 px-3 bg-secondary text-primary border border-primary border-rounded font-bold text-xs hover:bg-primary transition text-center flex items-center justify-center" title="Download">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                @endif
                            @elseif($isCourse && $item)
                                <a href="{{ route('tenant.course', ['id' => $item->id]) }}" 
                                   class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-play"></i> Watch Course
                                </a>
                            @else
                                <a href="{{ route_to('home') }}" 
                                   class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-check"></i> Enrolled
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        @else
            <div class="col-span-full p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2 bg-primary">
                <i class="fa-solid fa-file-pdf text-3xl text-tertiary"></i>
                <p class="font-semibold text-primary">No digital downloads found.</p>
                <p>Purchased e-books and study material PDFs will appear here for instant reading and downloading.</p>
            </div>
        @endif
    </div>
@endsection
