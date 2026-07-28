@php
    $book = $currentBook ?? null;

    // Fallback sample data if no book found or in Theme Builder mode
    if (!$book) {
        $book = (object) [
            'id' => 0,
            'title' => 'NCERT Physics Class 11',
            'slug' => 'ncert-physics-class-11',
            'author' => 'Monis Raza Khan',
            'publisher' => 'NCERT Publishing',
            'edition' => '2026 Edition',
            'isbn' => '978-3-16-148410-0',
            'description' => 'Comprehensive NCERT Physics textbook covering mechanics, thermodynamics, oscillations, and wave motion. Designed specifically for Class 11 and competitive exam preparations like NEET and JEE.',
            'pages_count' => 384,
            'cover_image' => null,
            'file_path' => null,
            'preview_file_path' => null,
            'price_type' => 'free',
            'price' => 0,
            'sale_price' => 0,
            'access_type' => 'public',
            'views_count' => 128,
            'downloads_count' => 45,
            'bookCategory' => (object) ['name' => 'Academic Books', 'slug' => 'academic-books'],
            'academicCategory' => (object) ['name' => 'NEET'],
            'classCourse' => (object) ['name' => 'Class 11'],
            'subject' => (object) ['name' => 'Physics']
        ];
    }

    $isPaid = ($book->price_type ?? '') === 'paid' || (($book->price ?? 0) > 0);
    $salePrice = $book->sale_price ?? null;
    $originalPrice = $book->price ?? null;
    $showRelated = filter_var($section->show_related ?? true, FILTER_VALIDATE_BOOLEAN);
    $relatedTitle = $section->related_title ?? 'Related Books & Notes';
@endphp

<section {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}"
    style="{{ $section->margin . $section->padding }}">
    <div class="section-content {{ $section->container }} relative z-30">

        {{-- 📚 MAIN BOOK DETAILS WRAPPER --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- 🖼️ LEFT COLUMN: COVER & ACTION BADGES --}}
            <div class="lg:col-span-4 flex flex-col items-center">
                <div
                    class="relative w-full max-w-sm rounded-xl overflow-hidden shadow-2xl border border-gray-100 group">
                    <img src="{{ image($book->cover_image ?? null) }}" alt="{{ $book->title }}"
                        class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">

                    {{-- PRICE BADGE --}}
                    <div class="absolute top-4 right-4 shadow-lg">
                        @if($isPaid)
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow">
                                ₹ {{ number_format($salePrice ?: $originalPrice, 2) }}
                            </span>
                        @else
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500 text-white shadow">
                                FREE
                            </span>
                        @endif
                    </div>
                </div>

                {{-- STATS BAR --}}
                <div
                    class="flex items-center justify-around w-full max-w-sm mt-4 p-3 bg-gray-50 rounded-lg text-xs text-gray-600 border border-gray-100">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-indigo-500"></i>
                        <span>{{ number_format($book->views_count ?? 0) }} Views</span>
                    </div>
                    <div class="h-4 w-px bg-gray-200"></div>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-download text-emerald-500"></i>
                        <span>{{ number_format($book->downloads_count ?? 0) }} Downloads</span>
                    </div>
                    @if($book->pages_count ?? null)
                        <div class="h-4 w-px bg-gray-200"></div>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-pdf text-rose-500"></i>
                            <span>{{ $book->pages_count }} Pages</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 📝 RIGHT COLUMN: CONTENT & METADATA --}}
            <div class="lg:col-span-8 flex flex-col gap-5">

                {{-- CATEGORY TAGS & PILLS --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($book->bookCategory->name ?? null)
                        <span
                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                            <i class="fa-solid fa-layer-group text-[10px] mr-1"></i>
                            {{ $book->bookCategory->name }}
                        </span>
                    @endif
                    @if($book->academicCategory->name ?? null)
                        <span
                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-purple-50 text-purple-700 border border-purple-100">
                            <i class="fa-solid fa-graduation-cap text-[10px] mr-1"></i>
                            {{ $book->academicCategory->name }}
                        </span>
                    @endif
                    @if($book->classCourse->name ?? null)
                        <span
                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                            <i class="fa-solid fa-book-open text-[10px] mr-1"></i>
                            {{ $book->classCourse->name }}
                        </span>
                    @endif
                    @if($book->subject->name ?? null)
                        <span
                            class="px-2.5 py-1 text-xs font-semibold rounded-md bg-amber-50 text-amber-700 border border-amber-100">
                            <i class="fa-solid fa-flask text-[10px] mr-1"></i>
                            {{ $book->subject->name }}
                        </span>
                    @endif
                </div>

                {{-- TITLE & AUTHOR --}}
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 leading-tight">
                        {{ $book->title }}
                    </h1>
                    @if($book->author)
                        <p class="text-sm font-medium text-gray-600 mt-1">
                            By <span class="text-gray-900 font-semibold">{{ $book->author }}</span>
                        </p>
                    @endif
                </div>

                {{-- PRICING SUMMARY --}}
                <div class="flex items-baseline gap-3">
                    @if($isPaid)
                        <span class="text-2xl font-bold text-gray-900">
                            ₹ {{ number_format($salePrice ?: $originalPrice, 2) }}
                        </span>
                        @if($salePrice && $salePrice < $originalPrice)
                            <span class="text-base text-gray-400 line-through">
                                ₹ {{ number_format($originalPrice, 2) }}
                            </span>
                        @endif
                    @else
                        <span class="text-2xl font-bold text-emerald-600">Free Download</span>
                    @endif
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-wrap gap-3 my-2">
                    @if($book->file_path)
                        <a href="{{ media($book->file_path) }}" target="_blank" download
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-sm bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 transition-all duration-200">
                            <i class="fa-solid fa-download"></i>
                            Download PDF
                        </a>
                    @endif

                    @if($book->preview_file_path || $book->file_path)
                        <a href="{{ media($book->preview_file_path ?: $book->file_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-bold text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-200 transition-all duration-200">
                            <i class="fa-solid fa-book-reader"></i>
                            Read / Preview Online
                        </a>
                    @endif
                </div>

                {{-- DESCRIPTION --}}
                @if($book->description)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">Description</h3>
                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $book->description }}
                        </div>
                    </div>
                @endif

                {{-- SPECIFICATIONS GRID --}}
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3">Book Specifications</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                        @if($book->publisher)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-400 block mb-0.5">Publisher</span>
                                <span class="font-semibold text-gray-800">{{ $book->publisher }}</span>
                            </div>
                        @endif
                        @if($book->edition)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-400 block mb-0.5">Edition</span>
                                <span class="font-semibold text-gray-800">{{ $book->edition }}</span>
                            </div>
                        @endif
                        @if($book->isbn)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-400 block mb-0.5">ISBN</span>
                                <span class="font-semibold text-gray-800">{{ $book->isbn }}</span>
                            </div>
                        @endif
                        @if($book->pages_count)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-400 block mb-0.5">Pages</span>
                                <span class="font-semibold text-gray-800">{{ $book->pages_count }}</span>
                            </div>
                        @endif
                        @if($book->access_type)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-400 block mb-0.5">Access Type</span>
                                <span class="font-semibold text-gray-800 uppercase">{{ $book->access_type }}</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- 📚 RELATED BOOKS SECTION --}}
        @if($showRelated && isset($relatedBooks) && count($relatedBooks) > 0)
            <div class="mt-16 pt-12 border-t border-gray-200">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        {{ $relatedTitle }}
                    </h2>
                    <a href="{{ route_to('books') }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        View All Books <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedBooks as $relatedBook)
                        <div
                            class="group relative bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <a href="{{ route_to('book', $relatedBook) }}"
                                class="block overflow-hidden aspect-[3/4] bg-gray-100">
                                <img src="{{ image($relatedBook->cover_image) }}" alt="{{ $relatedBook->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </a>

                            <div class="p-4 flex flex-col flex-grow justify-between gap-2">
                                <div>
                                    <h4
                                        class="font-bold text-sm text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition">
                                        <a href="{{ route_to('book', $relatedBook) }}">
                                            {{ $relatedBook->title }}
                                        </a>
                                    </h4>
                                    @if($relatedBook->author)
                                        <p class="text-xs text-gray-500 mt-1">
                                            By {{ $relatedBook->author }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-gray-50 mt-2">
                                    @if(($relatedBook->price_type ?? '') === 'paid' || ($relatedBook->price ?? 0) > 0)
                                        <span class="text-sm font-bold text-gray-900">
                                            ₹ {{ number_format($relatedBook->sale_price ?: $relatedBook->price, 2) }}
                                        </span>
                                    @else
                                        <span
                                            class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">FREE</span>
                                    @endif

                                    <a href="{{ route_to('book', $relatedBook) }}"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>