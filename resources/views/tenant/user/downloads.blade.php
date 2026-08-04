@extends('layouts.user')
@section('title', 'My E-Books & Downloads - Customer Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-book-open text-purple-500"></i> My E-Books & Downloads
            </h1>
            <p class="text-xs text-secondary mt-0.5">Access your purchased digital books, notes, and study material.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @if($orders->count() > 0)
            @foreach($orders as $index => $order)
                <div class="p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] bg-purple-500/10 text-purple-600 font-bold border border-purple-500/20 uppercase">
                                Digital E-Book
                            </span>
                            <span class="text-[10px] font-mono text-tertiary">#{{ $order->order_number ?? 'PDF-' . ($index + 1) }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-primary leading-snug">
                            Study Guide & E-Book Material #{{ $index + 1 }}
                        </h3>
                        <p class="text-xs text-secondary leading-relaxed">
                            Official study guide for exam preparation. Full access included with purchase #ORD-{{ $order->id }}.
                        </p>
                    </div>

                    <div class="pt-2 border-top flex items-center gap-2">
                        <a href="#" onclick="alert('Digital PDF Viewer is opening...'); return false;" 
                           class="w-full py-2.5 px-3 bg-invert text-invert border-rounded font-bold text-xs hover-invert transition text-center flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-file-pdf"></i> Read PDF
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2 bg-primary">
                <i class="fa-solid fa-file-pdf text-3xl text-tertiary"></i>
                <p class="font-semibold text-primary">No digital downloads available.</p>
                <p>Purchased e-books and study material PDFs will appear here for instant reading.</p>
            </div>
        @endif
    </div>
@endsection
