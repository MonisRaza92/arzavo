@extends('layouts.admin')
@section('title', 'Blogs & Stories')

@section('content')
    @include('tenant.admin.blogs.partials.header')

    {{-- Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">

        @forelse($blogs as $blog)

            <div class="bg-primary border-primary border-rounded overflow-hidden flex flex-col">

                {{-- Image --}}
                <img src="{{ media($blog->featured_image) }}" class="w-full aspect-video object-cover">

                {{-- Content --}}
                <div class="p-3 flex flex-col flex-1">

                    {{-- Title --}}
                    <div class="font-semibold text-primary text-base mb-1 line-clamp-2">
                        {{ $blog->title }}
                    </div>

                    {{-- Slug --}}
                    <div class="text-xs text-tertiary mb-2">
                        {!! Str::limit(strip_tags($blog->content), 100) !!}
                    </div>

                    {{-- Status + Date --}}
                    <div class="flex items-center justify-between mb-3">

                        @if($blog->status === 'published')
                            <span class="px-2 py-1 text-xs bg-green-600 text-white border-rounded">
                                Published
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-500 text-white border-rounded">
                                Draft
                            </span>
                        @endif

                        <span class="text-xs text-tertiary">
                            {{ optional($blog->published_at)->format('d M Y') ?? '-' }}
                        </span>

                    </div>

                    {{-- Actions --}}
                    <div class="mt-auto flex gap-2">

                        <a href="{{ route('admin.blog.edit', ['blog' => $blog->slug]) }}"
                            class="w-full text-center px-3 py-2 border-primary text-xs hover:bg-gray-700! hover:text-white transition-all duration-300 bg-secondary border-rounded flex items-center justify-center">
                            Edit
                        </a>

                        <form action="{{ route('admin.blog.destroy', ['blog' => $blog->slug]) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('Delete this blog?')">

                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-2.25 border-red-500 text-xs bg-red-100 text-red-500 border-rounded">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </form>

                    </div>

                </div>

            </div>
        @empty

            <div class="col-span-full text-center text-tertiary p-6">
                No blogs found
            </div>

        @endforelse


    </div>

@endsection