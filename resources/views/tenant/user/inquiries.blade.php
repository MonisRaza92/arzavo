@extends('layouts.user')
@section('title', 'Support Inquiries - Customer Portal')

@section('content')
    <div class="mb-4 p-4 border-rounded bg-primary border-primary shadow-xs flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-primary flex items-center gap-2">
                <i class="fa-solid fa-comments text-amber-500"></i> Support & Inquiries
            </h1>
            <p class="text-xs text-secondary mt-0.5">Track messages and inquiries submitted to the academy support team.</p>
        </div>
    </div>

    <div class="p-4 sm:p-5 border-rounded bg-primary border-primary space-y-4 shadow-xs">
        @if($inquiries->count() > 0)
            <div class="space-y-3">
                @foreach($inquiries as $inquiry)
                    <div class="p-4 border-rounded border-primary bg-hover-secondary space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-primary">{{ $inquiry->subject ?? 'General Inquiry' }}</span>
                            <span class="text-[10px] text-tertiary font-mono">{{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <p class="text-xs text-secondary leading-relaxed">{{ $inquiry->message }}</p>
                        <div class="pt-2 border-top flex items-center justify-between text-[11px]">
                            <span class="px-2 py-0.5 rounded font-bold bg-amber-500/10 text-amber-600 border border-amber-500/20">
                                Support Status: Received
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-3">
                {{ $inquiries->links() }}
            </div>
        @else
            <div class="p-8 text-center text-tertiary text-xs border-dashed border-rounded space-y-2">
                <i class="fa-solid fa-comments text-2xl text-tertiary"></i>
                <p class="font-semibold text-primary">No support inquiries found.</p>
                <p>Messages submitted through the contact form will appear here.</p>
            </div>
        @endif
    </div>
@endsection
