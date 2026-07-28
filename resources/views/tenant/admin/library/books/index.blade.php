@extends('layouts.admin')
@section('title', 'Books & Notes Manage')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center py-3 px-4 bg-primary border-rounded border-primary">
    <div>
        <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-1"><i class="fa fa-book mr-1 text-base"></i>
            Books & Notes <span class="hidden sm:block">Management</span>
        </h2>
        <p class="text-sm text-secondary hidden sm:block">List and manage study materials and books</p>
    </div>

    <div class="right-content flex gap-2 items-center">
        <!-- Add Book Button -->
        <a href="{{ route('admin.books.create') }}"
            class="px-3 py-2 text-sm bg-invert text-invert border-primary border-rounded hover-invert flex items-center gap-1">
            Add New Book
            <i class="fa fa-plus"></i>
        </a>
    </div>
</div>

{{-- Books Table --}}
<div class="mt-6 bg-primary border-primary border-rounded overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-secondary text-secondary border-bottom">
                    <th class="p-3">Cover</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Library Category</th>
                    <th class="p-3">Academic Tagging</th>
                    <th class="p-3">Pricing</th>
                    <th class="p-3">Access</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr class="border-bottom hover-primary transition-all">
                    <td class="p-3">
                        @if($book->cover_image)
                            <img src="{{ media($book->cover_image) }}" alt="Cover" class="w-10 h-14 object-cover border-rounded shadow">
                        @else
                            <div class="w-10 h-14 bg-secondary flex items-center justify-center text-[10px] text-tertiary border-rounded">
                                No Cover
                            </div>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="font-semibold text-primary">{{ $book->title }}</div>
                        @if($book->author)
                            <div class="text-xs text-secondary">By {{ $book->author }}</div>
                        @endif
                    </td>
                    <td class="p-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-secondary text-primary">
                            {{ $book->bookCategory->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="p-3 text-xs text-secondary">
                        @if($book->academicCategory)
                            <div>Category: {{ $book->academicCategory->name }}</div>
                        @endif
                        @if($book->classCourse)
                            <div>Class: {{ $book->classCourse->name }}</div>
                        @endif
                        @if($book->subject)
                            <div>Subject: {{ $book->subject->name }}</div>
                        @endif
                        @if(!$book->academicCategory && !$book->classCourse && !$book->subject)
                            <span class="text-tertiary">Global Material</span>
                        @endif
                    </td>
                    <td class="p-3">
                        @if($book->price_type === 'free')
                            <span class="text-xs font-bold text-green-600 uppercase">Free</span>
                        @else
                            <div class="text-primary font-semibold">₹{{ number_format($book->sale_price, 2) }}</div>
                            @if($book->sale_price)
                                <div class="text-xs text-red-500 line-through">₹{{ number_format($book->price, 2) }}</div>
                            @endif
                        @endif
                    </td>
                    <td class="p-3 text-xs">
                        @if($book->access_type === 'public')
                            <span class="px-2 py-0.5 rounded bg-green-100 text-green-800">Public</span>
                        @elseif($book->access_type === 'students_only')
                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800">Students</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800">Enrolled Only</span>
                        @endif
                    </td>
                    <td class="p-3">
                        @if($book->is_active)
                            <span class="text-xs text-green-600 flex items-center gap-1"><i class="fa fa-circle text-[8px]"></i> Active</span>
                        @else
                            <span class="text-xs text-tertiary flex items-center gap-1"><i class="fa fa-circle text-[8px]"></i> Draft</span>
                        @endif
                    </td>
                    <td class="p-3 text-right">
                        <div class="flex justify-end gap-3 items-center">
                            {{-- Edit --}}
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="text-tertiary hover:text-black">
                                <i class="fa fa-edit text-base"></i>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Delete this book permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-tertiary hover:text-red-600">
                                    <i class="fa fa-trash text-base"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-tertiary py-8">
                        No books uploaded yet. Click "Add New Book" to begin.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($books->hasPages())
        <div class="p-3 border-top bg-secondary">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
