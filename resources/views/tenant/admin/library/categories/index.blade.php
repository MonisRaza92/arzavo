@extends('layouts.admin')
@section('title', 'Book Categories Manage')

@section('content')
{{-- Header --}}
@include('tenant.admin.library.categories.partials.header')

{{-- Cards Grid --}}
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

    @forelse($categories as $category)

    <div class="border-primary border-rounded bg-primary hover-primary transition-all overflow-hidden">

        {{-- Image --}}
        <div class="mb-2 relative">
            @if($category->image)
            <img
                src="{{ media($category->image) }}"
                alt="{{ $category->name }}"
                class="w-full aspect-video object-cover border-bottom">
            @else
            <div
                class="w-full aspect-video border-bottom flex items-center justify-center text-tertiary text-xs">
                No Image
            </div>
            @endif
            <div class="flex items-center justify-between left-2 absolute bottom-2 right-2">
                {{-- Status --}}
                @if($category->status)
                <span class="text-[11px] text-invert bg-invert shadow bg-opacity-20 px-4 py-1 rounded-full">
                    Active
                </span>
                @else
                <span class="text-[11px] text-primary bg-tertiary shadow bg-opacity-20 px-4 py-1 rounded-full">
                    Inactive
                </span>
                @endif
            </div>
        </div>

        <div class="px-3">
            {{-- Name --}}
            <h3 class="text-primary font-semibold text-xl mb-1">
                {{ $category->name }}
            </h3>

            {{-- Description --}}
            <p class="text-tertiary text-sm mb-3 line-clamp-3">
                {{ $category->description ?? 'No description available.' }}
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center border-top text-sm px-3 py-2">
            <!-- Created at -->
            <span class="text-tertiary">
                {{ $category->created_at->format('d M, Y') }}
            </span>

            <div class="flex gap-4 items-center">
                {{-- Edit --}}
                <button
                    onclick="openEditCategoryPopup({{ $category->id }})"
                    class="text-tertiary text-hover-primary">
                    <i class="fa fa-edit"></i>
                </button>

                {{-- Delete --}}
                <form action="{{ route('admin.book-categories.destroy', $category->id) }}"
                    method="POST"
                    onsubmit="return confirm('Delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-tertiary text-hover-primary">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>

    @empty
    <div class="col-span-full text-center text-tertiary py-6">
        No book categories found.
    </div>
    @endforelse

</div>

@include('tenant.admin.library.categories.partials.category-edit-form')
@include('tenant.admin.library.categories.partials.category-add-form')

<script>
    function openEditCategoryPopup(categoryId) {
        functionGetBookCategoryDetails(categoryId);
        document.getElementById('categoryEditPopup').classList.remove('hidden');
    }
</script>
@endsection
