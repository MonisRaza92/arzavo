@extends('layouts.student')
@section('title', 'Digital Books & Study Notes - Student Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-book-open text-purple-600"></i> Digital Books & Study Material
            </h1>
            <p class="text-xs text-secondary mt-0.5">Access your unlocked E-books, class notes, and curriculum study PDFs.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-purple-500/10 text-purple-600 border border-purple-500/20 border-rounded font-bold text-xs">
                {{ $entitlements->total() }} Digital Item(s)
            </span>
        </div>
    </div>

    <!-- DIGITAL BOOKS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @forelse($entitlements as $ent)
            @php $book = $ent->entitable; @endphp
            <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs flex flex-col justify-between hover-primary transition">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded text-[10px] bg-purple-500/10 text-purple-600 font-bold border border-purple-500/20 uppercase">
                            {{ $book->bookCategory->name ?? ($book->academicCategory->name ?? 'Digital Study Book') }}
                        </span>
                        <span class="text-[10px] font-mono text-tertiary">Order #{{ $ent->order_id }}</span>
                    </div>

                    <div class="flex gap-3.5 items-start">
                        @if($book && ($book->cover_image || $book->thumbnail))
                            <img src="{{ media($book->cover_image ?? $book->thumbnail) }}" class="w-16 h-22 object-cover rounded-lg border border-primary shadow-xs shrink-0">
                        @else
                            <div class="w-16 h-22 bg-secondary rounded-lg border border-primary flex items-center justify-center shrink-0 text-purple-600 text-2xl shadow-xs">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                        @endif

                        <div class="space-y-1 grow min-w-0">
                            <h3 class="text-sm font-bold text-primary leading-snug line-clamp-2">
                                {{ $book->title ?? ($book->name ?? ('Study Material #' . $ent->entitable_id)) }}
                            </h3>
                            @if($book && $book->author)
                                <p class="text-[11px] text-tertiary">By {{ $book->author }}</p>
                            @endif
                            <p class="text-[11px] text-secondary line-clamp-2 mt-1">
                                {{ $book->description ?? 'Official digital publication curriculum material.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top grid grid-cols-2 gap-2">
                    <a href="{{ route('item.read', ['type' => 'book', 'id' => $ent->entitable_id, 'order_id' => $ent->order_id]) }}" 
                       target="_blank"
                       class="py-2 px-3 bg-secondary text-primary border border-primary border-rounded font-bold text-xs hover:bg-hover-secondary text-center transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-eye"></i> Read Online
                    </a>
                    <a href="{{ route('item.download', ['type' => 'book', 'id' => $ent->entitable_id, 'order_id' => $ent->order_id]) }}" 
                       class="py-2 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert text-center transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center text-tertiary text-xs border border-dashed border-primary border-rounded space-y-3 bg-primary">
                <div class="w-14 h-14 rounded-full bg-purple-500/10 text-purple-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-primary text-sm">No digital books or notes unlocked yet</h3>
                    <p class="text-secondary max-w-md mx-auto">When you purchase course books, PDF notes, or study materials from the academy catalog, they will appear here with instant online reading and download access.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($entitlements->hasPages())
        <div class="pt-2">
            {{ $entitlements->links() }}
        </div>
    @endif
@endsection
