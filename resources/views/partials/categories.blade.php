<section class="categories-section my-10 {{ $categories->count() ? '' : 'hidden' }}">
    <div class="container">
        <div class="section-heading mb-4">
            <h2 class="text-3xl font-bold uppercase text-primary">Explore Top Categories</h2>
            <p class="text-secondary">Discover a variety of subjects and skills with our top categories, designed to help you learn and grow.</p>
        </div>

        <div class="category-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($categories as $category)
            @php
            $style = $customizes['category_card_style'] ?? 'full_image';
            @endphp

            {{-- 🌟 FULL IMAGE CARD STYLE --}}
            @if ($style === 'full_image')
            <a href="#" class="category-card bg-primary border-rounded border-primary flex flex-col relative overflow-hidden">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="object-cover">
                <h3 class="p-4 pt-10 bg-gradient-to-b from-transparent to-black absolute bottom-0 left-0 text-md font-semibold w-full text-white">{{ $category->name }} <i class="fas fa-arrow-right text-sm"></i></h3>
            </a>
            @endif

            {{-- 🌑 BOTTOM TEXT CARD STYLE --}}
            @if ($style === 'bottom_text')
            <a href="#" class="category-card bg-primary border-primary border-rounded flex flex-col relative overflow-hidden">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class=" h-[350px] w-[240px] object-cover">
                <h3 class="p-2 border-rounded bg-primary text-md font-semibold w-full text-primary">{{ $category->name }} <i class="fas fa-arrow-right text-sm"></i></h3>
            </a>
            @endif

            {{-- 💠 NO TEXT CARD STYLE --}}
            @if ($style === 'no_text')
            <a href="#" class="category-card bg-primary border-rounded flex flex-col relative overflow-hidden">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class=" h-[350px] w-[240px] object-cover">
            </a>
            @endif
            @endforeach
        </div>
    </div>
</section>